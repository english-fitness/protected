<?php $this->renderPartial('step'); ?>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.min.js'></script>

<script type="text/javascript">
	function backStep(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/student/courseRegistration/schedule';
	}
    $(document).ready(function() {

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var calendar = jQuery('#calendar').fullCalendar({
		    height: 300,
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            firstDay:1,
            allDaySlot:false,
            eventDrop: function(calEvent)
            {
                var start = $.fullCalendar.formatDate(calEvent.start,"yyyy-MM-dd HH:mm:ss");
                $.ajax({

                    type: "POST",
                    url: "<?php echo Yii::app()->baseUrl; ?>/student/courseRegistration/AjaxSessionEditDay",
                    data: "id=" + calEvent.id + "&start=" + start,
                    success: function(result) {
                        if (result.success){
                            $("#feedback input").attr("value", ""); // clear all the input fields on success
                        }else{
                            calEvent.start = result.start;
                            calEvent.end = result.end;
                            calendar.fullCalendar('updateEvent',calEvent);
                        }

                    },
                    error: function(req, status, error) {

                    }
                });
            },
            eventClick: function(calEvent, jsEvent, view) {
               loadPopupCalendar.registerCourseUpdateCalendar(calEvent);
                /*popup edit calendar title*/
                $("form[name='calendarEditTitle'] button[name='save']").click(function(){
                    var title = $("input[name='sessionTitle']").val();
                    var PlanStartDate = $("input[name='sessionPlanStartDate']").val();
                    var PlanStartTime = $("select[name='sessionPlanStartTime']").val();
                    var startD = PlanStartDate.split("/");
					var PlanStart = startD[2]+'-'+startD[1]+'-'+startD[0]+" "+PlanStartTime+":00";
                    var seconds = new Date($.fullCalendar.parseDate( PlanStart )).getTime() / 1000;
                    seconds = parseInt(seconds)+5400;
                    if(title){
                        $.ajax({

                            type: "POST",
                            url: "<?php echo Yii::app()->baseUrl; ?>/student/courseRegistration/AjaxSessionEdit",
                            data: "id=" + calEvent.id + "&title=" + title + "&start="+PlanStart,
                            success: function(result) {
                                if (result.success){
                                   calEvent.title = title;
                                   calEvent.start =PlanStart;
                                   calEvent.end = $.fullCalendar.parseDate( seconds );
                                   removePopupByID("popupAll");
                                }else{
                                    $(".noticeForm").html('<div class="alert alert-danger">Thời gian bắt đầu không phù hợp</div>');
                                }
                                calendar.fullCalendar('updateEvent',calEvent);
                            },
                            error: function(req, status, error) {

                            }
                        });

                    }
                    return false;
                });

            },
            editable:true,
            events:"<?php echo Yii::app()->baseUrl; ?>/student/courseRegistration/AjaxSessionList"
        });

    });

</script>

<div id="calendar"></div>
<div class="formReview">
    <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post"  class="form-horizontal" role="formReview">
        <div class="row-form">
            <div class="w200 fL">&nbsp;</div>
            <div class="col-sm-7 value">
                <button class="btn btn-default prev-step" name="prevStep " type="button" onclick="backStep();">Quay lại</button>
                <button id="btnNextStep" class="btn btn-primary next-step" name="nextStep" type="submit">Tiếp tục</button>
            </div>
        </div>
    </form>
	<div class="clearfix h30"></div>
</div>

