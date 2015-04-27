<?php
/* @var $this PreregisterCourseController */
/* @var $model PreregisterCourse */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl.'/admin/preregisterCourse?status='.$model->status; ?>';
	}
	$(document).on("click",".datepicker",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
</script>
<div class="form">
<?php $registration = new ClsRegistration();?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'preregister-course-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm đơn xin học mới' : 'Sửa/phê duyệt đơn xin học';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        </div>
	    </div>
	</div>
<fieldset>
	<legend>Thông tin đơn xin học</legend>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Email học sinh <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php $email = Yii::app()->request->getPost('email', "");?> 
			<input name="email" id="PreregisterCourse_email" type="text" maxlength="256" value="<?php echo $email;?>">
			<label class="hint">Copy email của học sinh trong hệ thống DạyKèm123</label>
			<?php if(isset($emailError) && $emailError!=""):?>
			<div class="errorMessage"><?php echo $emailError;?></div>
			<?php endif;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Đăng ký môn học <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
		<?php $subjects = array(""=>"Chọn môn học...") + $subjects;?>
		<?php echo $form->dropDownList($model, 'subject_id', $subjects, array());?>
		<?php echo $form->error($model,'subject_id'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Chủ đề khóa học <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'title', array()); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Kiểu lớp học <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'total_of_student', $registration->totalStudentOptions(6), array()); ?>
			<?php echo $form->error($model,'total_of_student'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'total_of_session'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'total_of_session', array()); ?>
			<?php echo $form->error($model,'total_of_session'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Ngày bắt đầu dự kiến</label>
		</div>
		<div class="col col-lg-9">
			<?php if(isset($model->start_date) && $model->start_date) $model->start_date = date('Y-m-d', strtotime($model->start_date));?>
			<?php echo $form->textField($model,'start_date', array('class'=>'datepicker')); ?>
			<label class="hint">Ngày bắt đầu, định dạng yyyy-mm-dd. Ví dụ <b><?php echo date('Y-m-d')?></b></label>
			<?php echo $form->error($model,'start_date'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Kiểu khóa học</label>
		</div>
		<div class="col col-lg-9">
			<?php $courseTypes = Course::model()->typeOptions();?>
			<?php echo $form->dropDownList($model,'course_type', $courseTypes, array()); ?>
			<?php echo $form->error($model,'course_type'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'session_per_week'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $placeHolderAttrs = array('placeholder'=>'Ví dụ: {"Monday":"09:00 - 10:30","Wednesday":"09:00 - 10:30"}');?>
			<?php echo $form->textField($model,'session_per_week', $placeHolderAttrs); ?>
			<div class="fs11 clearfix pB5"><span class="error">Lịch học chi tiết: </span><?php echo ClsAdminHtml::displaySessionPerWeek($model->session_per_week); ?></div>
			<div class="fs11 clearfix"><span class="fL"><b>Ví dụ mẫu: </b>{"Monday":"09:00 - 10:30","Wednesday":"09:00 - 10:30"}</span> <span class="fR"><b>Ngày trong tuần:</b> Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday</span></div>
			<?php echo $form->error($model,'session_per_week'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'note', array('rows'=>6, 'cols'=>50, 'style'=>'height:6em')); ?>
			<?php echo $form->error($model,'note'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'status', $model->statusOptions(), array()); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Thông tin học phí khóa học</legend>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_type'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'payment_type', ClsCourse::paymentTypes(), array()); ?>
			<?php echo $form->error($model,'payment_type'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'final_price'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'final_price', array()); ?>
			<?php echo $form->error($model,'final_price'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'payment_status', ClsCourse::paymentStatuses(), array()); ?>
			<?php echo $form->error($model,'payment_status'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
					'model'=>$model,
					'attribute'=>'payment_note',
					'language'=>'en',
					'editorTemplate'=>'full',
				)); ?>
			<?php echo $form->error($model,'payment_note'); ?>
		</div>
	</div>		
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->