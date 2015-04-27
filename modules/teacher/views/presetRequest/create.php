<div class="page-title">
	<label class="tabPage">Registration and schedule training courses</label>
</div>
<script type="text/javascript">
	//Cancel button
	function cancel(){
		window.location = '/teacher/presetRequest/index';
	}
	//Generate schedule session with suggest day & hour
	function generateSchedule(){
	    var nPerWeek = parseInt($('#numberSessionPerWeek').val());
	    var planDuration = $('#planDuration').val();
	    //Load ajax subjects by class
	    var data = {'nPerWeek': nPerWeek, 'planDuration': planDuration};
	    $.ajax({
	        url: daykemBaseUrl + "/teacher/presetRequest/ajaxCreateSchedules",
	        type: "POST", dataType: 'html',data:data,
	        success: function(data) {
	            $('#selectedSchedule').html(data);
	        }
	    });
	}
	$(document).on("click",".start_date",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
</script>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'preset-course-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
<?php 
	$session = Yii::app()->request->getPost('Session', array());//Get session
	$registration = new ClsRegistration();//Init register session
?>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Class/subjective&nbsp;<span class="required">*</span></label>
	</div>
	<div class="col col-lg-9">
		<div class="col col-lg-6 pL0i">
			<?php $subjects = array(""=>"Chọn môn dạy...") + $abilitySubjects;?>
			<?php echo $form->dropDownList($model, 'subject_id', $subjects, array('style'=>'width:300px;font-size:13px;'));?>
			<?php echo $form->error($model,'subject_id'); ?>
		</div>
		<div class="col col-lg-6 pL0i">
			<span class="fL mT15"><a href="/teacher/subjectRegister/index"><b style="color:#325DA7;">[Cập nhật, bổ sung thêm môn dạy]</b></a></span>
		</div>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Subjective&nbsp;<span class="required">*</span></label>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->textField($model,'title', array('size'=>60,'maxlength'=>256, 'style'=>'font-size:13px;', 'placeholder'=>'Ví dụ: Ôn luyện thi đại học môn toán')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Number of classes / courses, tuition, class type&nbsp;<span class="required">*</span></label>
	</div>
	<div class="col col-lg-9">
		<div class="col col-lg-3 pL0i">
			<b class="mL5">Total number of classes / courses&nbsp;<span class="required">*</span></b>
			<?php echo $form->textField($model,'total_of_session', array('placeholder'=>'Số buổi >=4')); ?>
			<?php echo $form->error($model,'total_of_session'); ?>
		</div>
		<div class="col col-lg-4 pL0i">
			<b class="mL5">Fee/1 student/session&nbsp;<span class="required">*</span></b>
			<?php echo $form->textField($model,'price_per_student', array('placeholder'=>'Ví dụ: 250000')); ?>
			<?php echo $form->error($model,'price_per_student'); ?>
		</div>
		<div class="col col-lg-5 pL0i">
			<b class="mL5">Style classes (students / class) <span class="required">*</span></b>
			<?php echo $form->dropDownList($model, 'max_student', $registration->totalStudentPresetOptions(), array('class'=>'fs13'));?>
		</div>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Information teaching schedule</label>
	</div>
	<div class="col col-lg-9">
		<div class="col col-lg-3 pL0i">
			<?php $nSessionSelected = isset($session['session_per_week'])? $session['session_per_week']: "";?>
			<b class="mL5">Number of class/week <span class="required">*</span></b><?php echo CHtml::dropDownList('Session[session_per_week]', $nSessionSelected, $registration->numberSessionsPerWeek(), array('id'=>'numberSessionPerWeek',"class"=>"fs13",'onchange'=>'generateSchedule();'));?>
			
		</div>
		<div class="col col-lg-4 pL0i">
			<b class="mL5">Date of scheduled start <span class="required">*</span></b>
			<?php echo $form->textField($model,'start_date', array('class'=>'start_date', 'readonly'=>'readonly')); ?>
			<?php echo $form->error($model,'start_date'); ?>
		</div>
		<div class="col col-lg-5 pL0i">
			<?php 
				$durationSelected = isset($session['plan_duration'])? $session['plan_duration']: 90;
				$durationOptions = array(90=>'90', 120=>'120', 150=>'150');
			?>
			<b class="mL5">Duration 1 day (minutes)</b><?php echo CHtml::dropDownList('Session[plan_duration]', $durationSelected, $durationOptions, array('id'=>'planDuration', 'class'=>'fs13', 'onchange'=>'generateSchedule();'));?>
		</div>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Time to teach appropriate&nbsp;<span class="required">*</span></label>
	</div>
	<div class="col col-lg-9" id="selectedSchedule">
		<?php
		if(isset($session['dayOfWeek'])):
			foreach($session['dayOfWeek'] as $key=>$dayOfWeek):
		?>
			<div class="col col-lg-12 mT5" style='border:1px solid #CCCCCC;'>
				<b class="fL mT15">Number of classes&nbsp;<?php echo ($key+1);?>:&nbsp;</b>
				<?php echo CHtml::dropDownList('Session[dayOfWeek][]', $dayOfWeek, $registration->daysOfWeek(), array('class'=>'w150 fL fs13'));?>
				<b class="fL mT15 mL15">Frame teaching hours:&nbsp;</b>
				<?php echo CHtml::dropDownList('Session[startHour][]', $session['startHour'][$key], $registration->timeFrames(), array('class'=>'w200 fL fs13'));?>
			</div>
		<?php endforeach;
		else:?>
		<p><span <?php echo (isset($_POST['PresetCourse']) && !$model->session_per_week)? 'class="error"': "";?>>
			Please select the number of sessions / week and set schedule details!</span>
		</p>
		<?php endif;?>
		<label class="hint">Note: Schedule details are not identical in week days and hours learning framework</label>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Short description</label>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->textArea($model,'short_description',array('rows'=>6,'cols'=>50,'style'=>'height:6em','class'=>'fs13','placeholder'=>'Ví dụ: Khóa học dành cho các học sinh muốn đạt điểm cao môn Toán trong kỳ thi đại học!')); ?>
		<?php echo $form->error($model,'short_description'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Full description</label>
	</div>
	<div class="col col-lg-9">
		<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$model,
				'attribute'=>'description',
				'language'=>'en',
				'editorTemplate'=>'advanced',
				'toolbar' => array(
                    array('-','Source','-','Bold','Italic','Underline','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote','-','Link','Unlink','-','SpecialChar','-','Cut','Copy','Paste','-','Undo','Redo','-','Maximize','-','About'),
                ),
			)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3"></div>
	<div class="col col-lg-9">
		<input type="submit" name="btnRegister" class="btn btn-primary fs13 pA5 fsBold" style="width:200px" value="Đăng ký lịch dạy"/>
	</div>
</div>
<?php $this->endWidget(); ?>

</div><!-- form -->