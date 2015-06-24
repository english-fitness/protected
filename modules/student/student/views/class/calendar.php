<?php $this->renderPartial('courseTab',array('course'=>$course)); ?>
<div class="details-class">
    <div class="session">
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
                    allDaySlot:false,
                    eventClick: function(calEvent, jsEvent, view){
                        return false;
                    },
                    events:"<?php echo Yii::app()->baseUrl; ?>/student/class/AjaxCalendarSession/id/<?php echo  $course->id; ?>"
                });
            });
        </script>
        <div id="calendar"></div>
    </div>
</div>
<!--.class-->