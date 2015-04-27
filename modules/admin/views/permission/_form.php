<?php
/* @var $this PermissionController */
/* @var $model Permission */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/permission/';
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'permission-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm quyền truy cập' : 'Sửa quyền truy cập';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        </div>
	    </div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'title'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'controller'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'controller',array('size'=>60,'maxlength'=>80)); ?>
			<?php echo $form->error($model,'controller'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'action'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'action',array('size'=>60,'maxlength'=>80)); ?>
			<?php echo $form->error($model,'action'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'description'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em;')); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->