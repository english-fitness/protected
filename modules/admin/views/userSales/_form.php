<?php
/* @var $this UserSalesController */
/* @var $model UserSalesHistory */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-sales-history-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'preregister_user_id'); ?>
		<?php echo $form->textField($model,'preregister_user_id'); ?>
		<?php echo $form->error($model,'preregister_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sale_date'); ?>
		<?php echo $form->textField($model,'sale_date'); ?>
		<?php echo $form->error($model,'sale_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'next_sale_date'); ?>
		<?php echo $form->textField($model,'next_sale_date'); ?>
		<?php echo $form->error($model,'next_sale_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sale_note'); ?>
		<?php echo $form->textField($model,'sale_note',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'sale_note'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sale_status'); ?>
		<?php echo $form->textField($model,'sale_status',array('size'=>60,'maxlength'=>80)); ?>
		<?php echo $form->error($model,'sale_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sale_question'); ?>
		<?php echo $form->textField($model,'sale_question',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'sale_question'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'user_answer'); ?>
		<?php echo $form->textField($model,'user_answer',array('size'=>60,'maxlength'=>256)); ?>
		<?php echo $form->error($model,'user_answer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_user_id'); ?>
		<?php echo $form->textField($model,'created_user_id'); ?>
		<?php echo $form->error($model,'created_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modified_user_id'); ?>
		<?php echo $form->textField($model,'modified_user_id'); ?>
		<?php echo $form->error($model,'modified_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'deleted_flag'); ?>
		<?php echo $form->textField($model,'deleted_flag'); ?>
		<?php echo $form->error($model,'deleted_flag'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_date'); ?>
		<?php echo $form->textField($model,'created_date'); ?>
		<?php echo $form->error($model,'created_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'modified_date'); ?>
		<?php echo $form->textField($model,'modified_date'); ?>
		<?php echo $form->error($model,'modified_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->