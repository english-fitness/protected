<?php
/* @var $this CartController */
/* @var $model Cart */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cart_id'); ?>
		<?php echo $form->textField($model,'cart_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cart_type'); ?>
		<?php echo $form->textField($model,'cart_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cart_code'); ?>
		<?php echo $form->textField($model,'cart_code'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cart_price'); ?>
		<?php echo $form->textField($model,'cart_price'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cart_status'); ?>
		<?php echo $form->textField($model,'cart_status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->