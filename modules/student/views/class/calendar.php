<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/calendar.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.js'></script>

<style>
.reservedSlot{
	cursor:pointer;
	-webkit-appearance:button;
	-moz-appearance:button;
}
.ui-autocomplete-input, .ui-menu, .ui-menu-item {
	z-index: 99999;
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
</style>

<?php
	$registration = new ClsRegistration();
	$hours = json_encode($registration->hoursInDay());
	$minutes = json_encode($registration->minutesInHour());
	// $timezone = 8;
?>
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;"><?php echo Yii::t('lang', 'schedule')?></p></div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
	<form class="form-inline" role="form" method="get">
		<div class="form-group" style="margin:0 150px;">
			<label class="form-label"><?php echo Yii::t('lang', 'search_teacher')?>: </label>
			<input id="teacherSearchBox" type="text" class="form-control" placeholder="<?php echo Yii::t('lang', 'student_search_teacher_placeholder')?>" style="width:500px;">
			<input id="teacherId" type="hidden" name="teacher">
			<input type="submit" value="<?php echo Yii::t('lang', 'search')?>" class="btn" style="margin-top: 0px">
		</div>
	 </form>
	<div style='margin:10px auto; text-align:center'>
		<?php echo Yii::t('lang', 'page')?> <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<div style='clear:both'></div>
	<div style='margin:20px auto; text-align:center; width:870px;'>
		<button class='week-nav btn btn-primary' nav='prev'><</button>
		<button class='wday-select btn' day='0'><?php echo Yii::t('lang', 'monday')?><br></button>
		<button class='wday-select btn' day='1'><?php echo Yii::t('lang', 'tuesday')?><br></button>
		<button class='wday-select btn' day='2'><?php echo Yii::t('lang', 'wednesday')?><br></button>
		<button class='wday-select btn' day='3'><?php echo Yii::t('lang', 'thursday')?><br></button>
		<button class='wday-select btn' day='4'><?php echo Yii::t('lang', 'friday')?><br></button>
		<button class='wday-select btn' day='5'><?php echo Yii::t('lang', 'saturday')?><br></button>
		<button class='wday-select btn' day='6'><?php echo Yii::t('lang', 'sunday')?><br></button>
		<button class='week-nav btn btn-primary' nav='next'>></button>
	</div>
	<div style='clear:both'></div>
	<?php if ($page > $pageCount)
			echo "<div>" . Yii::t('lang', '') . "</div>"?>
    <div id="calendar-1" style="width:1000px; margin:35px"></div>
	<div id="calendar-2" style="width:1000px; margin:35px"></div>
	<div id="calendar-3" style="width:1000px; margin:35px"></div>
	<div id="calendar-4" style="width:1000px; margin:35px"></div>
	<div style='margin:0 auto; text-align:center'>
		<?php echo Yii::t('lang', 'page')?> <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<div style="position:fixed;top:460px;left:10px;width:250px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;">
		<span style="margin:3px"><b><?php echo Yii::t('lang', 'color_legend')?></b></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:yellow;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'timeslot_available')?></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:dodgerblue;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'timeslot_booked')?></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkgray;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'timeslot_closed')?></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:lime;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'session_approved')?></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkgreen;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'session_pending')?></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:turquoise;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'session_ongoing')?></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkorange;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'session_ended')?></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:red;float:left;margin:3px"></div><span style="float:left"><?php echo Yii::t('lang', 'session_canceled')?></span>
		<div style="clear:both"></div>
	</div>
</div>
<script>
	//global variables	
	var options = {
		hours: <?php echo $hours;?>,
		minutes: <?php echo $minutes;?>
	}
	
	var minTime = '09:00';
	var maxTime = '22:51';
	
	var currentWday;
	var currentWeekStart;
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
	
	$(document).ready(function(){
		currentWeekStart = moment().startOf('isoWeek').format('YYYY-MM-DD');
		setHeader();
		$('.wday-select').click(function(){		
			var targetDate = addDay(currentWeekStart, $(this).attr('day'));
			
			for (var i = 1; i <= 4; i++){
				var calendar = $('#calendar-'+i);
				calendar.fullCalendar('gotoDate', targetDate);
			}
		});
		$('.week-nav').click(function(){
			var value = $(this).attr('nav');
			if (value == 'prev'){
				currentWeekStart = addDay(currentWeekStart, -7);
			} else if (value == 'next'){
				currentWeekStart = addDay(currentWeekStart, 7);
			}
			loading.created();
			for (var i = 0; i < teacherGroups.length; i++){
				reloadCalendar('calendar-'+(i+1), teacherGroups[i], currentWeekStart);
				$('#calendar-'+(i+1)).fullCalendar('gotoDate', currentWeekStart);
			}
			loading.removed();
		})
		bindSearchBoxEvent("teacherSearchBox", searchTeacher);
	});
	
	//functions
	function loadCalendar (divId, teachers){
		$.ajax({
			type:'get',
			url:'<?php echo Yii::app()->baseUrl;?>/student/class/getSessions',
			data:{
				teachers:JSON.stringify(teachers),
			},
			success:function(response){
				createCalendar(divId, response, teachers.length);
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
				currentWday = view.start.weekday();
				
				//change weekday selection
				var currentSelect = $('.wday-select.btn-primary').attr('day');
				if (currentSelect != currentWday){
					$('.wday-select.btn-primary').removeClass('btn-primary');
					$('.wday-select[day='+currentWday+']').addClass('btn-primary');
				}
				
			},
			eventClick: function(event, jsEvent, view){
				if (event.className == 'reservedSlot'){
					var values = {
						actionUrl: '<?php echo Yii::app()->baseUrl?>/student/class/bookSession',
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
	
	//may need rework
	function reloadCalendar(calendarDiv, teachers, weekStart){
		var calendar = $('#'+calendarDiv);
		calendar.fullCalendar( 'removeEvents');
		calendar.fullCalendar('refetchEvents');
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/student/class/getSessions',
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
				if (weekStart){
					calendar.fullCalendar('gotoDate', addDay(currentWeekStart, currentWday));
					setHeader();
				}
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
			url:'<?php echo Yii::app()->baseUrl?>/student/class/changeTeacher',
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
						url:'<?php echo Yii::app()->baseUrl?>/student/class/unbookSession',
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
	
	function setHeader(){
		$('.wday-select').each(function(){
			var thisOne = $(this);
			var day = thisOne.attr('day');
			var html = thisOne.html();
			thisOne.html(html.substr(0, html.indexOf('<br>') + 4) + ' ' + addDay(currentWeekStart, parseInt(day)));
		});
	}
	
	function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/student/class/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				searchBoxAutocomplete('teacherSearchBox', data, function(id){$('#teacherId').val(id);});
			}
		});
	}
	
	function reloadAll(){
		loading.created();
		for (var i = 0; i < teacherGroups.length; i++){
			reloadCalendar('calendar-'+(i+1), teacherGroups[i], currentWeekStart);
		}
		loading.removed();
	}
</script>
