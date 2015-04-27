<?php $this->renderPartial('courseTab',array('course'=>$course)); ?>
<div class="details-class">
    <script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.min.js'></script>
    <script>
        $(document).ready(function() {
            var calendar = jQuery('#calendar').fullCalendar({
			    height: 300,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                firstDay:1,
                eventClick: function(calEvent, jsEvent, view) {
                    loadPopupCalendar.editSession(calEvent,'<?php echo Yii::app()->baseurl?>/teacher/class/ajaxEditSessionById/id/'+calEvent.id);
                    loadPopupCalendar.ajaxForm(calEvent,calendar);
                },
                allDaySlot:false,
                events:"<?php echo Yii::app()->baseUrl; ?>/teacher/class/ajaxLoadSessionsByCourseId/id/<?php echo  $course->id; ?>"
            });
        });

    </script>
    <div id="calendar"></div>
</div>