<?php
/* @var $this DailyRecordController */
/* @var $model TeachingDay */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fine_record_form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'New Teacher Penalty Record' : 'Update Teacher Penalty Record';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeRecord();"><i class="btn-remove"></i>Xóa</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
<fieldset>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'teacher_id'); ?>
		</div>
		<div class="col col-lg-9">
			<div>
				<span><?php echo $model->teacher->fullname()?></span>
			</div>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'points'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'points', TeacherFine::model()->getPointOptions()); ?>
			<?php echo $form->error($model,'points'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'notes'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em;', 'value'=>$model->notes, 'name'=>'TeacherFine[notes]')); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
	function cancel(){
		window.location = daykemBaseUrl+'/admin/teacherFine/fineRecords';
	}
</script>