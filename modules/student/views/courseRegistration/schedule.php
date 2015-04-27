<?php $this->renderPartial('step'); ?>
<script type="text/javascript">
	//Generate schedule session with suggest day & hour
	function generateSchedule(){
		var nPerWeek = parseInt($('#numberSessionPerWeek').val());
		//Load ajax subjects by class
		var data = {'nPerWeek': nPerWeek};
		$.ajax({
			url: "<?php echo Yii::app()->baseUrl; ?>" + "/student/courseRegistration/ajaxSuggestSchedules",
			type: "POST", dataType: 'html',data:data,
			success: function(data) {
				$('#selectedSchedule').html(data);
			}
		});
	}
	function backStep(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/student/courseRegistration/index';
	}
	$(document).ready(function() {
		$('#btnNextStep').click(function(){			
			var wnumber, startDate, today, startD;
			var displayStartDate = $('#displayStartDate').val();
			startD = displayStartDate.split("/");
			startDate = startD[2]+'-'+startD[1]+'-'+startD[0];
			today = "<?php echo date('Y-m-d')?>";
			$("#startDate").val(startDate);
			wnumber = $("#numberSessionPerWeek").val();
			if(wnumber!="" && startDate>=today && startDate<="2100-01-01"){
				$("#formStep2").submit();
			}else{
				$("#validMessage").html('<span class="required">Vui lòng kiểm tra lại những trường dữ liệu bắt buộc* (ngày bắt đầu, số buổi/tuần, thời gian học)!</span>');
			}
		});
	});
</script>
<?php 
	$registration = new ClsRegistration();//Init register session
	$session = $registration->getSession('session');//Get session
?>
<div class="form">
    <form id="formStep2" action="<?php echo $_SERVER['REQUEST_URI']?>" method="post"  class="form-horizontal">
        <div class="form_notice" id="validMessage">
			<?php if(!$checkValid):?>
			<span class="required">Vui lòng kiểm tra lại những trường dữ liệu bắt buộc* (ngày bắt đầu, số buổi/tuần, thời gian học)!</span>
			<?php endif;?>
		</div>
        <div class="row-form">
            <div class="col-sm-3 label">Ngày bắt đầu khóa học&nbsp;<span class="required">*</span></div>
            <div class="col-sm-7 value">
                <?php $startDate = isset($session['startDate'])? $session['startDate']: date('Y-m-d');?>
                <input style="width: 100px;" class="datepicker" type="text" id="displayStartDate" value="<?php echo date('d/m/Y', strtotime($startDate)); ?>" readonly="readonly">
                <input type="hidden" name="Session[startDate]" id="startDate" value="<?php echo $startDate; ?>">
                <label class="hint">Định dạng ngày bắt đầu dd/mm/yyyy</label>
            </div>
        </div>
        <div class="row-form">
            <div class="col-sm-3 label">Số buổi học/tuần&nbsp;<span class="required">*</div></label>
            <div class="col-sm-7 value">
                <?php $numberSelected = isset($session['numberSessionPerWeek'])? $session['numberSessionPerWeek']: "";?>
                <?php echo CHtml::dropDownList('Session[numberSessionPerWeek]', $numberSelected, $registration->numberSessionsPerWeek(), array('id'=>'numberSessionPerWeek', 'onchange'=>'generateSchedule();',"style"=>"width:auto"));?>
                <label class="hint">Chọn số buổi có thể học trong tuần</label>
            </div>
        </div>
        <div class="row-form">
            <div class="col-sm-3 label">Thời gian học phù hợp: </div>
            <div class="col-lg-8 value" id="selectedSchedule">
                <?php
                if(isset($session['dayOfWeek'])):
                    foreach($session['dayOfWeek'] as $key=>$dayOfWeek):
                        $index = $key+1;
                        ?>
                        <div class="date_register">
                            <b>Buổi&nbsp;<?php echo $index;?>:&nbsp;</b>
                            <?php echo CHtml::dropDownList('Session[dayOfWeek][]', $dayOfWeek, $registration->daysOfWeek(), array('style'=>'width:100px;'));?>
                            <b>Giờ:&nbsp;</b>
                            <?php echo CHtml::dropDownList('Session[startHour][]', $session['startHour'][$key], $registration->hoursInDay(), array('style'=>'width:70px;'));?>
                            <b>Phút:&nbsp;</b>
                            <?php echo CHtml::dropDownList('Session[startMin][]', $session['startMin'][$key], $registration->minutesInHour(), array('style'=>'width:70px;'));?>
                            <b>Thời lượng:<b>&nbsp;&nbsp; 90 phút</b>
                        </div>
                <?php endforeach; endif;?>

            </div>
        </div>        
        <div class="row-form">
            <div class="col-sm-3 label">&nbsp;</div>
            <div class="col-sm-7 value">
                <button class="btn btn-default prev-step" name="prevStep " type="button" onclick="backStep();">Quay lại</button>
                <button id="btnNextStep" class="btn btn-primary next-step" name="nextStep" type="button">Tiếp tục</button>
            </div>
        </div>
    </form>
</div>
