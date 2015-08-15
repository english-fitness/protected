<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
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
	height:32.5px;
	vertical-align:middle;
	max-width:120px;
	text-align:center;
	overflow:hidden !important;
    text-overflow: ellipsis;
}
</style>
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Schedule</p></div>
<?php $this->renderPartial('teacher.views.class.myCourseTab'); ?>
<div class="details-class">
	<form class="form-inline" role="form" style="padding-top:10px">
		<div class="form-group">
			<label class="form-label" for="month-selection">Month: </label>
			<select id="month-selection" class="form-control" style="width:150px">
				<?php
					$thisMonth = date('m');
					for ($i = 1; $i <= 12; $i++){
						$highlight = ($thisMonth == $i) ? "selected style='background-color:rgba(50, 93, 167, 0.2)'" : "";
						echo "<option value=" . $i . " " . $highlight . ">" . date('F', strtotime('2000-'.$i.'-01')) . "</option>";
					}
				?>
			</select>
		</div>
	</form>
    <div id="calendar"></div>
	<div style="position:fixed;top:540px;left:10px;width:250px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;">
		<span style="margin:3px"><b>Color legend</b></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:lime;float:left;margin:3px"></div><span style="float:left">Approved Session</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkgreen;float:left;margin:3px"></div><span style="float:left">Pending Session</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:turquoise;float:left;margin:3px"></div><span style="float:left">Ongoing Session</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkorange;float:left;margin:3px"></div><span style="float:left">Ended Session</span>
		<div style="clear:both"></div>
	</div>
</div>
<script>
	var start = new Date('<?php echo date('Y-m-01')?>');
	var end = new Date('<?php echo date('Y-m-t')?>');
	
	var minTime = '09:00';
	var maxTime = '22:51';
	
	<?php if (isset($timezone)){
		echo 	'var convertedRange = convertRangeByTimezone(minTime, maxTime, 7, '. $timezone . ');
				minTime = convertedRange.minTime;
				maxTime = convertedRange.maxTime;';
	}?>
	
	$(document).ready(function() {
		$('#calendar').fullCalendar({
			height: 1120,
			header: {
				left: 'prev,next',
				center: 'title',
				right: 'prev,next',
			},
			viewRender: function(view,element) {
				//clamp view to current month (still not ok)
				clampView(view)
			},
			eventRender: function (event, element) {
				element.find('.fc-title').html(event.title);
			},
			now:'<?php echo date('Y-m-d')?>',
			minTime: minTime,
			maxTime: maxTime, //plus one minute so end time could be displayed
			slotDuration: '00:40:00',
			defaultView: 'agendaWeek',
			allDaySlot:false,
			axisFormat: 'H:mm',
			timeFormat: 'H:mm',
			columnFormat: 'ddd D/M',
			firstDay: 1,
			events:<?php echo $sessions;?>
		});
		
		$('.fc-title').css('cursor','default');
		
		$('#month-selection').on('change', function(){
			var thisMonth = moment($(this).val(), 'MM');
			start = moment(thisMonth).startOf('month');
			end = moment(thisMonth).endOf('month');
			reloadCalendar();
			var calendar = $('#calendar');
			calendar.fullCalendar('gotoDate', start.format('YYYY-MM-DD'));
			$(this).blur();
			clampView(calendar.fullCalendar('getView'));
		});
	});
	
	$('body').css('overflow-x', 'hidden');
	
	$(document).keyup(function(e) {
		switch(e.which) {
			case 37: // left
			$('.fc-left').children('.fc-button-group').children('.fc-prev-button').click();
			break;

			case 39: // right
			$('.fc-left').children('.fc-button-group').children('.fc-next-button').click();
			break;

			default: return;
		}
	});
	
	function clampView(view){
		if ( end <= view.end) {
			$("#calendar .fc-next-button").addClass('fc-state-disabled');
			return false;
		}
		else {
			$("#calendar .fc-next-button").removeClass('fc-state-disabled');
		}
		
		if ( view.start <= start) {
			$("#calendar .fc-prev-button").addClass('fc-state-disabled');
			return false;
		}
		else {
			$("#calendar .fc-prev-button").removeClass('fc-state-disabled');
		}
	}
	
	function reloadCalendar(){
		loading.created();
		$('#calendar').fullCalendar('removeEvents');
		$('#calendar').fullCalendar('refetchEvents');
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/teacher/class/calendar',
			type:'post',
			data:{
				refresh:true,
				month:document.getElementById('month-selection').value,
			},
			success: function(response){
				var newEvents = response.sessions;
				$('#calendar').fullCalendar('addEventSource', newEvents);
				$('#calendar').fullCalendar('refetchEvents');
				loading.removed();
			}
		});
	}
</script>