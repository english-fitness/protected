<?php
/* @var $this UserSalesController */
/* @var $model UserSalesHistory */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'preregister_user_id'); ?>
		<?php echo $form->textField($model,'preregister_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sale_date'); ?>
		<?php echo $form->textField($model,'sale_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'next_sale_date'); ?>
		<?php echo $form->textField($model,'next_sale_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sale_note'); ?>
		<?php echo $form->textField($model,'sale_note',array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sale_status'); ?>
		<?php echo $form->textField($model,'sale_status',array('size'=>60,'maxlength'=>80)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sale_question'); ?>
		<?php echo $form->textField($model,'sale_question',array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_answer'); ?>
		<?php echo $form->textField($model,'user_answer',array('size'=>60,'maxlength'=>256)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created_user_id'); ?>
		<?php echo $form->textField($model,'created_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modified_user_id'); ?>
		<?php echo $form->textField($model,'modified_user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'deleted_flag'); ?>
		<?php echo $form->textField($model,'deleted_flag'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created_date'); ?>
		<?php echo $form->textField($model,'created_date'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'modified_date'); ?>
		<?php echo $form->textField($model,'modified_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->