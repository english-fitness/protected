<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link rel="stylesheet" type="text/css" href="/media/css/calendar.css">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/utils.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/moment.min.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.js'></script>
<?php if(Yii::app()->language=="vi"):?>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/lang_vi.js'></script>
<?php endif;?>

<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;"><?php echo Yii::t('lang', 'schedule')?></p></div>
<?php $this->renderPartial('student.views.class.myCourseTab'); ?>
<div class="details-class">
    <div style="margin:0 150px;">
        <?php $this->renderPartial("widgets/searchBox")?>
    </div>
	<div style='margin:10px auto; text-align:center'>
		<?php echo Yii::t('lang', 'page')?> <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<?php $this->renderPartial('widgets/wdaySelector', array('current_day'=>$current_day)) ?>
	<?php if ($page > $pageCount)
			echo "<div>" . Yii::t('lang', '') . "</div>"?>
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
		<?php echo Yii::t('lang', 'page')?> <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<?php $this->renderPartial('widgets/colorLegend');?>
</div>
<script>
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
			url:'<?php echo Yii::app()->baseUrl;?>/student/schedule/getSessions',
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
		document.getElementById(divId).setAttribute("style","width:"+1000*(size/4)+"px; height:1300px;margin: 40px auto");
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
				
			},
			eventClick: function(event, jsEvent, view){
				if (event.className == 'reservedSlot'){
					var values = {
						actionUrl: '<?php echo Yii::app()->baseUrl?>/student/schedule/bookSession',
						teacher: event.teacher,
						start:event.start.format('YYYY-MM-DD HH:mm:ss'),
					}
					$("<div><?php echo Yii::t('lang', 'student_confirm_book_session')?></div>").dialog({
						title:"<?php echo Yii::t('lang', 'student_book_session')?>",
						modal:true,
						resizable:false,
						buttons:{
							"<?php echo Yii::t('lang', 'button_accept')?>": function(){
								bookSession(values, bookSuccess, bookError);
								$(this).dialog('close');
							},
							"<?php echo Yii::t('lang', 'button_cancel')?>": function(){
								$(this).dialog('close');
							}
						}
					});
				} else if (event.className == 'unbookable'){
					data = {
						id:event.id,
						start:event.start,
						title:event.subject,
					};
					displayDetail(data);
				}
			},
			minTime: minTime,
			maxTime: maxTime, //plus one minute so end time could be displayed
			slotDuration: '00:40:00',
			defaultView: 'resourceDay',
			resources: data.teachers,
			allDaySlot:false,
			// timezone:'isUTC',
			now:'<?php echo date('Y-m-d')?>',
			axisFormat: 'H:mm',
			timeFormat: 'H:mm',
			columnFormat: 'ddd D/M',
			firstDay: 1,
			events: sessions,
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
			url:'<?php echo Yii::app()->baseUrl?>/student/schedule/getSessions',
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
	
	function bookSuccess(){
		$("<div><?php echo Yii::t('lang', 'student_book_success')?></div>").dialog({
			modal:true,
			resizable:false,
			buttons:{
				"<?php echo Yii::t('lang', 'button_close')?>": function(){
					//reload calendar
					reloadAll();
					$(this).dialog('close');
				},
			}
		});
	}
	
	function bookError(response){
		if (response.canRebook){
			$("<div><?php echo Yii::t('lang', 'student_book_duplicate_pending')?></div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"<?php echo Yii::t('lang', 'student_change_teacher')?>": function(){
						changeTeacher(response)
						$(this).dialog('close');
					},
					"<?php echo Yii::t('lang', 'button_cancel')?>": function(){
						$(this).dialog('close');
					}
				}
			});
		} else if (response.canRebook === false){
			$("<div><?php echo Yii::t('lang', 'student_book_duplicate_approved')?></div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"<?php echo Yii::t('lang', 'button_close')?>": function(){
						$(this).dialog('close');
					},
				}
			});
		} else if(response.reason == "no_active_course"){
			$("<div><?php echo Yii::t('lang', 'no_active_course')?></div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"<?php echo Yii::t('lang', 'button_close')?>": function(){
						$(this).dialog('close');
					},
				}
			});
		} else{
			$("<div><?php echo Yii::t('lang', 'book_error')?></div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"<?php echo Yii::t('lang', 'button_close')?>": function(){
						$(this).dialog('close');
					},
				}
			});
		}
	}
	
	function changeTeacher(data){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/student/schedule/changeTeacher',
			type:'post',
			data:{
				session: data.existingSession,
				teacher: data.teacher
			},
			success:function(response){
				if (response.success){
					$("<div><?php echo Yii::t('lang', 'student_book_success')?></div>").dialog({
						modal:true,
						resizable:false,
						buttons:{
							"<?php echo Yii::t('lang', 'button_close')?>": function(){
								//reload calendar
								reloadAll();
								$(this).dialog('close');
							},
						}
					});
				} else {
					$("<div><?php echo Yii::t('lang', 'book_error')?></div>").dialog({
						modal:true,
						resizable:false,
						buttons:{
							"<?php echo Yii::t('lang', 'button_close')?>": function(){
								$(this).dialog('close');
							},
						}
					});
				}
			}
		});
	}
	
	function displayDetail(data){
		var detail = "<div><?php echo Yii::t('lang', 'session')?>: " + data.title + "</div>";
		detail += "<div><?php echo Yii::t('lang', 'session_date')?>: " + data.start.format('DD-MM-YYYY') + "</div>";
		detail += "<div><?php echo Yii::t('lang', 'session_time')?>: " + data.start.format('HH:mm') + "</div>";
		detail = "<div>" + detail + "</div>";
		$(detail).dialog({
			title:"<?php echo Yii::t('lang', 'session_detail')?>",
			modal:true,
			resizable:false,
			buttons:{
				"<?php echo Yii::t('lang', 'cancel_session')?>":function(){
					unbookSchedule(data.id);
					$(this).dialog('close');
				},
				"<?php echo Yii::t('lang', 'button_close')?>":function(){
					$(this).dialog('close');
				}
			}
		});
	}
	
	function unbookSchedule(sessionId){
		$("<div><?php echo Yii::t('lang', 'student_unbook_confirm')?></div>").dialog({
			title:"Hủy buổi học",
			modal:true,
			resizable:false,
			buttons:{
				"<?php echo Yii::t('lang', 'button_yes')?>": function(){
					$.ajax({
						url:'<?php echo Yii::app()->baseUrl?>/student/schedule/unbookSession',
						type:'post',
						data:{
							session: sessionId,
						},
						success:function(){
							reloadAll();
						}
					});
					$(this).dialog('close');
				},
				"<?php echo Yii::t('lang', 'button_no')?>": function(){
					$(this).dialog('close');
				}
			}
		});
	}
	
	function reloadAll(){
        for (var i = 0; i < teacherGroups.length; i++){
            reloadCalendar('calendar-'+(i+1), teacherGroups[i], moment(currentWeekStart).add(currentWday, 'days').format("YYYY-MM-DD"));
        }
    }
</script>
