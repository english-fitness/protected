<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/calendar.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/schedule.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.min.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/lang_vi.js'></script>

<style>
.reservedSlot{
	-webkit-appearance:button;
	-moz-appearance:button;
}
.ui-autocomplete-input, .ui-menu, .ui-menu-item {
	z-index: 99999;
}
.fc-event{
	cursor:pointer;
}
.fc-time-grid-event .fc-time{
	display:none;
}
.fc-title{
	margin:auto 0 auto 0;
	vertical-align:middle;
	width: 100%;
	height:100%;
	text-align:center;
	overflow:hidden !important;
    text-overflow: ellipsis;
}
.fc-content{
	vertical-align:middle;
	height:100%;
}
.wday-select{
	float:left;
	margin: 5px;
}
.fc-toolbar button{
	display:none;
}
.week-nav{
	width: 40px;
	height: 54px;
	float: left;
	margin: 5px;
	font-size: 20px;
	font-weight: bold;
}
.calendar-holder{
	margin:35px;
	position:relative;
}
.calendar-loading-indicator{
	display:none;
	width:100%;
	height:1170px;
	position:absolute;
	top:49px;
	left:0;
	background: url("/media/images/icon/large-loader-128.gif") no-repeat center center;
	background-color: rgba(250,250,250,0.7);
	z-index: 99999;
}
</style>

<?php
	$classModels = Classes::model()->findAll(array('order'=>'name ASC'));
	$classes = array();
	foreach($classModels as $class){
		$classes[] = json_encode($class->getAttributes());
	}
?>

<div class="details-class">
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

	var minTime = '09:00';
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
			url:'<?php echo Yii::app()->baseUrl;?>/schedule/getSessions',
			data:{
				teachers:JSON.stringify(teachers),
				week_start:weekStart,
			},
			success:function(response){
				createCalendar(divId, response, teachers.length);
				$('#'+divId).fullCalendar('gotoDate', date);
			}
		});
	}

	function createCalendar(divId, data, size){
		var firstColumnWidth = 85;
		var calendarWidth = firstColumnWidth+(1000-firstColumnWidth)*(size/4);
		document.getElementById(divId).setAttribute("style","width:"+calendarWidth+"px; height:1300px;margin: 40px auto");
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
				setPaginationLinkDate(addDay(currentWeekStart, currentWday));

				if (view.start.format('YYYY-MM-DD') == today){
					$('#today-select').addClass('btn-primary');
				} else {
					$('#today-select').removeClass('btn-primary');
				}

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
						bindSearchBoxEvent("ajaxSearchStudent", searchStudent);
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
						bindSearchBoxEvent("ajaxSearchStudent", searchStudent);
					}
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
			url:'/schedule/getSessions',
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

	function searchStudent(keyword){
		$.ajax({
			url:"<?php echo Yii::app()->baseurl; ?>/admin/course/AjaxLoadStudent/keyword/"+keyword,
			type:"get",
			success: function(response) {
				var data = response[0];
				searchBoxAutocomplete('ajaxSearchStudent', data, ajaxLoadCourse);
			}
		});
	}
    
    //we'll look into it later
    // //bind scroll function
    // var calendarPos = [];
    // //cache those variable so we dont need to go grab them every scroll
    // var currentCalendarIndex = -1;
    // var currentCalendar;
    // var currentHeader = $("<div></div>");
    
    // function onScroll(){
        // var currentPos = window.pageYOffset;
        // var calendarId;
        
        // for (calendarId = 1; calendarId < 4; ++calendarId){
            // var calendarPosition = calendarPos[calendarId];
            // if (currentPos >= calendarPosition.top && currentPos <= calendarPosition.bottom){
                // break;
            // }
        // }
        
        // if (calendarId != 4){
            // if (currentCalendarIndex != calendarId){
                // currentCalendarIndex = calendarId;
                // currentHeader.css("position", "relative").css("top", "").css("width", "").css("margin-left","").find(".fc-axis").show();
                // currentCalendar = calendarPos[currentCalendarIndex];
                // currentHeader = $("#calendar-"+currentCalendarIndex).find(".fc-widget-header").first();
            // }
            // var currentHeaderTop = currentHeader.offset().top;
            // if ((currentHeaderTop <= currentPos ) && (currentHeaderTop + currentHeader.height() <= currentCalendar.bottom)){
                // console.log(currentHeader);
                // currentHeader.attr("style", "position:fixed; top:0; width:914px;margin-left:85px;background-color:white;z-index:999").find(".fc-axis").hide();
            // }
        // } else {
            // currentHeader.css("position", "relative").css("top", "").css("width", "").css("margin-left","").find(".fc-axis").show();
        // }
    // }
    
    // $(window).load(function(){
        // for (var i = 1; i < 4; i++){
            // var calendar = $('#calendar-'+i);
            // var calendarTop = calendar.offset().top;
            // var calendarBottom = calendarTop + calendar.height();
            
            // calendarPos[i] = {
                // top:calendarTop,
                // bottom:calendarBottom,
            // }
        // }
        
        // $(window).scroll(function(e){
            // onScroll();
        // });
        // onScroll();
    // });
    
    //calendar actions event handlers

	function createSessionSuccess(){
		reloadAll();
	}

	function createSessionError(response){
		if (response.existingSession){
			$("<div>Học sinh này đã đặt lịch học với một giáo viên khác trong cùng khung giờ. " +
			"Bạn có thể thay đổi giáo viên cho buổi học</div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"Thay đổi giáo viên": function(){
						changeTeacher(response)
						$(this).dialog('close');
					},
					"Hủy": function(){
						$(this).dialog('close');
					}
				}
			});
		} else {
			displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
		}
	}

	function updateSessionSuccess(){
		reloadAll();
	}

	function updateSessionError(response){
		if (response.existingSession){
			$("<div>Học sinh này đã đặt lịch học với một giáo viên khác trong cùng khung giờ. " +
			"Bạn có thể thay đổi giáo viên cho buổi học</div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"Thay đổi giáo viên": function(){
						duplicateSessionUpdate(response)
						$(this).dialog('close');
					},
					"Hủy": function(){
						$(this).dialog('close');
					}
				}
			});
		} else {
			displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
		}
	}

	function duplicateSessionUpdate(data){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/calendarUpdateSession',
			type:'post',
			data:{
				duplicateSession: true,
				existingSession: data.existingSession,
				currentSession: data.currentSession,
				courseId: data.currentCourse,
				subject: data.currentSubject,
				studentId: data.currentStudent,
			},
			success:function(response){
				if (response.success){
					reloadAll();
				} else {
					displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
				}
			}
		});
	}

	function changeTeacher(data){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/calendarUpdateSession',
			type:'post',
			data:{
				changeTeacher: true,
				session: data.existingSession,
				teacher: data.teacher
			},
			success:function(response){
				if (response.success){
					reloadAll();
				} else {
					displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
				}
			}
		});
	}

	function deleteSession(sessionId){
		$('<div>Bạn có muốn xóa buổi học này?</div>').dialog({
			modal:true,
			resizable:false,
			buttons: {
				"Xóa": function(){
					$.ajax({
						url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/calendarDeleteSession',
						type:'post',
						data:{
							id:sessionId,
						},
						success:function(){
							reloadAll();
						}
					});
					$(this).dialog('close');
				},
				"Hủy": function(){
					$(this).dialog('close');
				}
			}
		});
	}

	function approveSession(id){
		$('<div>Bạn có muốn xác nhận buổi học này?</div>').dialog({
			modal:true,
			resizable:false,
			buttons: {
				"Xác nhận": function(){
					$.ajax({
						url:'<?php echo Yii::app()->baseUrl?>/admin/session/ajaxApprove',
						type:'post',
						data:{
							session_id: id,
						},
						success:function(response){
							if (!response.success){
								displayConfirmDialog('Xác nhận buổi học', 'Đã có lỗi xảy ra, vui lòng thử lại sau', 'Đóng');
							} else {
								if (response.pendingCourse){
									$("<div>Buổi học của khóa học này vẫn chưa được xác nhận"+
									". Các buổi học của khóa học này sẽ không được nhìn thấy.<br>"+
									"Bạn có muốn xác nhận khóa học này?</div>").dialog({
										title:'Xác nhận khóa học',
										width:500,
										modal:true,
										resizable:false,
										buttons:{
											"Xác nhận":function(){
												$.ajax({
													url:'<?php echo Yii::app()->baseUrl?>/admin/course/ajaxApprove',
													type:'post',
													data:{
														course_id: response.pendingCourse,
													},
													success: function(response){
														if (!response.success){
															displayConfirmDialog('Xác nhận khóa học', 'Đã có lỗi xảy ra, vui lòng thử lại sau', 'Đóng');
														}
													}
												})
												$(this).dialog('close');
											},
											"Đóng":function(){
												$(this).dialog('close');
											},
										}
									});
								}
								reloadAll();
							}
						}
					});
					$(this).dialog('close');
				},
				"Hủy": function(){
					$(this).dialog('close');
				}
			}
		});
	}

	function endSession(id, endTime){
		var now = moment();
		var end = moment(endTime);

		if (end.diff(now) > 0){
			displayConfirmDialog("Kết thúc buổi học", "Buổi học vẫn chưa hết thời gian", "Đóng");
		} else {
			$('<div>Bạn có muốn kết thúc buổi học này?</div>').dialog({
				title:"Kết thúc buổi học",
				modal:true,
				resizable:false,
				buttons: {
					"Kết thúc": function(){
						$.ajax({
							url:'<?php echo Yii::app()->baseUrl?>/api/session/end',
							type:'get',
							data:{
								sessionId: id,
								forceEnd: true,
							},
							success:function(){
								reloadAll();
							}
						});
						$(this).dialog('close');
					},
					"Hủy": function(){
						$(this).dialog('close');
					}
				}
			});
		}
	}

	function toggleChangeSchedule(changing, session){
		changingSchedule = changing;
		if (changing){
			pendingChangeSession = session;
			$('#changingSchedule').show();
		} else {
			pendingChangeSession = null;
			$('#changingSchedule').hide();
		}
	}

	function changeSchedule(session, values){
		console.log(session);
		console.log(values);
		$('<div>Bạn có muốn thay đổi lịch học sang khung giờ này?</div>').dialog({
			title:"Đổi lịch học",
			modal:true,
			resizable:false,
			buttons:{
				"Đồng ý": function(){
					$.ajax({
						url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/changeSchedule',
						data:{
							sessionId: session,
							teacher:values.teacher,
							start:values.start,
						},
						type:"post",
						success:function(response){
							if (response.success){
								reloadAll();
							} else {
								if (response.reason == "duplicate_session"){
									displayConfirmDialog("Đổi lịch học", "Học sinh trong buổi học này đã có một buổi học"+
									" với giáo viên khác trong cùng khung giờ. Bạn không thể chuyển buổi học sang khung giờ này", "Đóng");
								} else {
									displayConfirmDialog("Đổi lịch học", "Đã có lỗi xảy ra, vui lòng thử lại sau", "Đóng");
								}
							}
						}
					});
					$(this).dialog('close');
					toggleChangeSchedule(false);
				},
				"Hủy": function(){
					$(this).dialog('close');
				}
			}
		});
	}

	function displayConfirmDialog(title, confirmText, confirmButton){
		var buttons = {};
		buttons[confirmButton] = function(){
			$(this).dialog('close');
		};
		$("<div>"+confirmText+"</div>").dialog({
			title:title,
			modal:true,
			resizable:false,
			buttons:buttons,
		});
	}

	function reloadAll(){
		for (var i = 0; i < teacherGroups.length; i++){
			reloadCalendar('calendar-'+(i+1), teacherGroups[i], currentWeekStart);
		}
	}
</script>
