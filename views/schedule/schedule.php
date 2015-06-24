<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/media/css/calendar.css" />
<link rel="stylesheet" type="text/css" href="/media/css/bootstrap-theme.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/calendar.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.js'></script>

<style>
.reservedSlot{
	-webkit-appearance:button;
	-moz-appearance:button;
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
</style>

<?php
	$registration = new ClsRegistration();
	$hours = json_encode($registration->hoursInDay());
	$minutes = json_encode($registration->minutesInHour());
	// $timezone = 8;
?>

<div class="details-class">
	<div style='margin:0 auto; text-align:center'>
		Trang <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<div style='margin:0 auto; text-align:center; width:770px;'>
		<button class='wday-select btn' day='0'>Thứ hai<br><?php echo date('d-m-Y', strtotime('monday this week'))?></button>
		<button class='wday-select btn' day='1'>Thứ ba<br><?php echo date('d-m-Y', strtotime('tuesday this week'))?></button>
		<button class='wday-select btn' day='2'>Thứ tư<br><?php echo date('d-m-Y', strtotime('wednesday this week'))?></button>
		<button class='wday-select btn' day='3'>Thứ năm<br><?php echo date('d-m-Y', strtotime('thursday this week'))?></button>
		<button class='wday-select btn' day='4'>Thứ sáu<br><?php echo date('d-m-Y', strtotime('friday this week'))?></button>
		<button class='wday-select btn' day='5'>Thứ bảy<br><?php echo date('d-m-Y', strtotime('saturday this week'))?></button>
		<button class='wday-select btn' day='6'>Chủ nhật<br><?php echo date('d-m-Y', strtotime('sunday this week'))?></button>
	</div>
	<div style='clear:both'></div>
	<?php if ($page > $pageCount)
			echo "<div>Không có dữ liệu</div>"?>
    <div id="calendar-1" style="width:1000px; margin:35px"></div>
	<div id="calendar-2" style="width:1000px; margin:35px"></div>
	<div id="calendar-3" style="width:1000px; margin:35px"></div>
	<div id="calendar-4" style="width:1000px; margin:35px"></div>
	<div style='margin:0 auto; text-align:center'>
		Trang <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
</div>
<script>
	//global variables	
	var options = {
		hours: <?php echo $hours;?>,
		minutes: <?php echo $minutes;?>
	}
	
	var minTime = '09:00';
	var maxTime = '22:21';
	
	var currentWday;
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
		$('.wday-select').click(function(){
			var weekStart = '<?php echo date('Y/m/d', strtotime('monday this week'))?>';
			
			var targetDate = addDay(weekStart, $(this).attr('day'));
			
			for (var i = 1; i <= 4; i++){
				var calendar = $('#calendar-'+i);
				calendar.fullCalendar('gotoDate', targetDate);
			}
		});
	});
	
	//functions
	function loadCalendar (divId, teachers){
		$.ajax({
			type:'get',
			url:'<?php echo Yii::app()->baseUrl;?>/schedule/getSessions',
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
				currentWday = (view.start._d.getDay() + 6) % 7;
				
				//change weekday selection
				var currentSelect = $('.wday-select.btn-primary').attr('day');
				if (currentSelect != currentWday){
					$('.wday-select.btn-primary').removeClass('btn-primary');
					$('.wday-select[day='+currentWday+']').addClass('btn-primary');
				}
				
			},
			minTime: minTime,
			maxTime: maxTime, //plus one minute so end time could be displayed
			slotDuration: '00:40:00',
			defaultView: 'resourceDay',
			resources: data.teachers,
			allDaySlot:false,
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
</script>
