<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/calendar.js"></script>

<?php
	$registration = new ClsRegistration();
	$hours = json_encode($registration->hoursInDay());
	$minutes = json_encode($registration->minutesInHour());
?>

<div class="details-class">
	<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
    <script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.js'></script>
    <script>
		var options = {
			hours: <?php echo $hours;?>,
			minutes: <?php echo $minutes;?>
		}
		
        $(document).ready(function() {
            var calendar = jQuery('#calendar').fullCalendar({
			    height: 1083,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                },
				viewRender: function(view,element) {
					var start = new Date();
					var end = new Date();
					var now = new Date();
					start.setMonth(now.getMonth() - 1); //last 1 months
					end.setMonth(now.getMonth() + 1); //next 1 months

					//clamp view to +- 1 month
					
					if ( end < view.end) {
						$("#calendar .fc-next-button").hide();
						$("#calendar .fc-next-button").addClass('fc-state-disabled');
						return false;
					}
					else {
						$("#calendar .fc-next-button").show();
						$("#calendar .fc-next-button").removeClass('fc-state-disabled');
					}

					if ( view.start < start) {
						$("#calendar .fc-prev-button").hide();
						$("#calendar .fc-prev-button").addClass('fc-state-disabled');
						return false;
					}
					else {
						$("#calendar .fc-prev-button").show();
						$("#calendar .fc-prev-button").removeClass('fc-state-disabled');
					}
				},
				selectable: true,
				select: function(start, end)
				{
					CalendarSessionHandler.newSession(start, 'yay', options);
				},
				minTime: '09:00:00',
				maxTime: '23:01:00',
				slotDuration: '00:40:00',
				defaultView: 'agendaWeek',
				allDaySlot:false,
				axisFormat: 'H:mm',
				timeFormat: 'H:mm',
				columnFormat: 'ddd D/M',
				firstDay: 1,
                events:"<?php echo Yii::app()->baseUrl; ?>/admin/session/loadSessionsByTeacherAndFilter?teacher=<?php echo $teacher;?>"
            });
        });
		
		$('body').css('overflow-x', 'hidden');
		
		$(document).keyup(function(e) {
			switch(e.which) {
				case 37: // left
				$('.fc-prev-button').click();
				e.preventDefault();
				break;

				case 39: // right
				$('.fc-next-button').click();
				e.preventDefault();
				break;

				default: return;
			}
		});
    </script>
    <div id="calendar" style="width:1000px"></div>
</div>
