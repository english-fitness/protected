<?php
	$classModels = Classes::model()->findAll(array('order'=>'name ASC'));
	$classes = array();
	foreach($classModels as $class){
		$classes[] = json_encode($class->getAttributes());
	}
?>

<div class="details-class">
	<div class="text-center pB10">
		<?php if (!isset($_GET['status']) || $_GET['status'] != Teacher::STATUS_TESTER):?>
			<a href="/admin/schedule/view?status=<?php echo Teacher::STATUS_TESTER?>">Lịch kiểm tra hệ thống</a>
		<?php else:?>
			<a href="/admin/schedule/view">Lịch học</a>
		<?php endif;?>
	</div>
	<?php $this->renderPartial('widgets/searchBox')?>
	<?php $this->renderPartial('widgets/colorLegend')?>
	<div style='margin:0 auto; text-align:center'>
		Trang <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<?php $this->renderPartial('widgets/wdaySelector', array('current_day'=>$current_day)) ?>
	<div style='clear:both'></div>
	<?php if ($page > $pageCount)
			echo "<div>Không có dữ liệu</div>"?>
			<div id="calendar-1" class="calendar-holder">
				<div class="calendar-loading-indicator"></div>
			</div>
			<div id="calendar-2" class="calendar-holder">
				<div class="calendar-loading-indicator"></div>
			</div>
			<div id="calendar-3" class="calendar-holder">
				<div class="calendar-loading-indicator"></div>
			</div>
	<div style='margin:0 auto; text-align:center'>
		Trang <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
</div>
<script>
	//global variables
	var classes = [<?php echo implode(',', $classes)?>];

	var changingSchedule = false;
	var pendingChangeSession;

	var minTime = '07:00';
	var maxTime = '22:51';

	//end global variables
	var allTeachers = <?php echo $teachers?>;
	var teacherGroups = [];
	for (var i = 0; i < allTeachers.length; i+=4){
		var group = allTeachers.slice(i, i + 4);

		teacherGroups.push(group);
	}

	for (var i = 0; i < teacherGroups.length; i++){
		loadCalendar('calendar-'+(i+1), teacherGroups[i]);
	}

	<?php if (isset($timezone)){
		echo 	'var convertedRange = convertRangeByTimezone(minTime, maxTime, 7, '. $timezone . ');
				minTime = convertedRange.minTime;
				maxTime = convertedRange.maxTime;';
	}?>

	//functions
	function loadCalendar (divId, teachers, date){
		if (!date){
			var date = currentDate;
			var weekStart = currentWeekStart;
		} else {
			var weekStart = moment(date).startOf('isoWeek').format('YYYY-MM-DD');
		}
		$.ajax({
			type:'get',
			url:'<?php echo Yii::app()->baseUrl;?>/admin/schedule/getSessions',
			data:{
				teachers:JSON.stringify(teachers),
				week_start:weekStart,
			},
			success:function(response){
				createCalendar(divId, response, teachers.length);
				$('#'+divId).fullCalendar('gotoDate', date);
                $.event.trigger({
                    type:"calendarLoaded",
                    calendar:divId,
                });
			}
		});
	}

	function createCalendar(divId, data, size){
		var firstColumnWidth = 85;
		var calendarWidth = firstColumnWidth+(1000-firstColumnWidth)*(size/4);
		document.getElementById(divId).setAttribute("style","width:"+calendarWidth+"px; height:1400px;margin: 40px auto");
		var sessions = data.sessions;
		var availableSlots = data.availableSlots;

		var calendarDiv = $('#'+divId);

		calendarDiv.fullCalendar({
			height: 'auto',
			header: {
				left: 'prev today',
				right:'next',
				center: 'title',
			},
			viewRender: function(view,element) {
				currentWday = (view.start._d.getDay() + 6) % 7;

				//change weekday selection
				var currentSelect = $('.wday-select.selected').attr('day');
				if (currentSelect != currentWday){
					var selected = $('.wday-select.selected');
					selected.removeClass('btn-primary selected');
					$('.wday-select[day='+currentWday+']').addClass('btn-primary selected');
				}
				setPaginationLinkDate(moment(currentWeekStart).add(currentWday, "days").format("YYYY-MM-DD"));

				if (view.start.format('YYYY-MM-DD') == today){
					$('.today-select').addClass('btn-primary');
				} else {
					$('.today-select').removeClass('btn-primary');
				}
                
                $("#secondary-datepicker").val(view.start.format("DD-MM-YYYY"));

			},
			eventClick: function(event, jsEvent, view){
				if (changingSchedule){
					if (event.className == 'reservedSlot'){
						var values = {
							teacher: event.teacher,
							start: event.start.format('YYYY-MM-DD HH:mm:ss'),
						}
						changeSchedule(pendingChangeSession, values);
					}
				}else {
					if (event.className == 'reservedSlot'){
						var preset = {
							action: '<?php echo Yii::app()->baseUrl;?>/admin/schedule/calendarCreateSession',
							teacher: event.teacher,
							start: event.start,
						}
						CalendarSessionHandler.newSession(preset, createSessionSuccess, createSessionError);
					} else if (['approvedSession', 'ongoingSession', 'pendingSession'].indexOf(event.className[0]) > -1){
						var data = {
							action: '<?php echo Yii::app()->baseUrl;?>/admin/schedule/calendarUpdateSession',
							sessionId: event.id,
							teacher: event.teacher,
							start: event.start,
							teacherName: event.teacherName,
							subject: event.subject,
							course_id: event.course_id,
							student: event.student,
							className: event.className,
							end: event.end,
						}
						CalendarSessionHandler.editSession(data, updateSessionSuccess, updateSessionError);
					}
                    SearchBox.bindSearchEvent("#ajaxSearchStudent", AjaxCall.searchStudent, displayStudentSearchResult);
				}
			},
			minTime: minTime,
			maxTime: maxTime, //plus one minute so end time could be displayed
			slotDuration: '00:40:00',
			defaultView: 'resourceDay',
			resources: data.teachers,
			allDaySlot:false,
			timezone:false,
			axisFormat: 'H:mm',
			timeFormat: 'H:mm',
			columnFormat: 'ddd D/M',
			firstDay: 1,
			events: sessions,
            lang:'vi',
		});

		<?php
			if (!isset($timezone)){
				echo "$('#'+divId).fullCalendar('addEventSource', getAvailableTimeslot(availableSlots));";
			} else {
				echo "$('#'+divId).fullCalendar('addEventSource', getAvailableTimeslot(availableSlots, ".$timezone."));";
			}
		?>
	}

	$('body').css('overflow-x', 'hidden');
	$('.fc-title').css('cursor','default');

	$(document).keyup(function(e) {
		switch(e.which) {
			case 37: // left
			if (currentWday != 0){
				$('.fc-prev-button').click();
			}
			break;

			case 39: // right
			if (currentWday != 6){
				$('.fc-next-button').click();
			}
			break;

			default: return;
		}
	});

	function reloadCalendar(calendarDiv, teachers, date){
		var calendar = $('#'+calendarDiv);
		var loadingIndicator = calendar.children('.calendar-loading-indicator');
		loadingIndicator.show();
		calendar.fullCalendar( 'removeEvents');
		calendar.fullCalendar('refetchEvents');
		var weekStart = moment(date).startOf('isoWeek').format('YYYY-MM-DD');
		$.ajax({
			url:'/admin/schedule/getSessions',
			type:'get',
			data:{
				teachers: JSON.stringify(teachers),
				week_start: weekStart,
			},
			success: function(response){
				var newEvents = response.sessions;
				var newSlots = response.availableSlots;
				var teacher = response.teachers;
				calendar.fullCalendar('addEventSource', newEvents);
				calendar.fullCalendar('addEventSource', getAvailableTimeslot(newSlots));
				calendar.fullCalendar('refetchEvents');
				loadingIndicator.hide();
				calendar.fullCalendar('gotoDate', moment(date).format('YYYY-MM-DD'));
			}
		});
	}

	function ajaxLoadCourse(studentId){
		document.getElementById('hiddenStudentId').value = studentId;
		$.ajax({
			url:"<?php echo Yii::app()->baseurl; ?>/admin/schedule/ajaxLoadCourse/student/"+studentId,
			type:"get",
			success: function(courses){
				//clearing old options
				document.getElementById("courseSelect").innerHTML = "";
				//setting new options
				var options = "";
				for (var i in courses){
					options += "<option value=" + courses[i].id + ">" + courses[i].title + "</option>";
				}
				document.getElementById("courseSelect").innerHTML = options;
				$("#courseSelect").change();
			}
		});
	}

	function autoFillSessionTitle(courseId){
        $("#sessionTitle").prop('disabled', true).val("Generating...");
		$.ajax({
			url:"<?php echo Yii::app()->baseUrl?>/admin/schedule/countSession",
			type:"get",
			data:{
				course: courseId,
			},
			success:function(response){
                document.getElementById("sessionTitle").disabled = false;
				document.getElementById("sessionTitle").value = "Session " + (parseInt(response.sessionCount) + 1);
			}
		});
	}
    
    function displayStudentSearchResult(results){
        SearchBox.autocomplete({
            searchBox:'#ajaxSearchStudent',
            results:results,
            resultLabel:'usernameAndFullName',
            selectCallback:ajaxLoadCourse,
        });
    }
    
    function reloadAll(){
        for (var i = 0; i < teacherGroups.length; i++){
            reloadCalendar('calendar-'+(i+1), teacherGroups[i], moment(currentWeekStart).add(currentWday, 'days').format("YYYY-MM-DD"));
        }
    }
</script>
