<?php
/* @var $this SessionController */
/* @var $model Session */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'session-note-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm ghi chú mới' : 'Sửa ghi chú';?></h2>
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
			<?php echo $form->labelEx($model,'using_platform'); ?>
		</div>
		<div class="col col-lg-9">
			<?php
				$htmlOptions = array();
				echo $form->checkBox($model, 'using_platform', array('checked'=>$model->isNewRecord ? true : $model->using_platform));
			?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50, 'style'=>'height:6em')); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->