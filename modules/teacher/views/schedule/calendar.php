<style type="text/css">
	.fc-button{
		display: block !important;
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
    <?php $this->renderPartial('colorLegend')?>
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
			var thisMonth = moment(parseInt($(this).val()) + 1, 'MM');
			start = moment.utc(thisMonth).startOf('month');
			end = moment.utc(thisMonth).endOf('month');
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
			url:'<?php echo Yii::app()->baseUrl?>/teacher/schedule/calendar',
			type:'get',
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