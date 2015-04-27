<?php
/* @var $this SessionController */
/* @var $model Session */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/headerScript';
	}
</script>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'session-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm cấu hình đầu trang' : 'Sửa cấu hình đầu trang';?></h2>
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
			<?php echo $form->labelEx($model,'condition'); ?>
		</div>
		<div class="col col-lg-9 inputCondition">
			<div class="pull-left hostInfo"><?php echo Yii::app()->request->hostInfo; ?></div>
			<div class="pull-left"><?php echo $form->textField($model,'condition',array('size'=>60,'maxlength'=>255)); ?></div>
			<div class="clearfix">&nbsp;</div>
			<?php echo $form->error($model,'condition'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'value'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'value'); ?>
			<?php echo $form->error($model,'value'); ?>
		</div>
	</div>
	
	<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
