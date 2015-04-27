<?php
/* @var $this CartController */
/* @var $model Cart */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cart-form',
    'htmlOptions'=>array('role'=>'form','class'=>'form-horizontal'),

	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model,'cart_price',array('class'=>'col-sm-2 control-label')); ?>
        <div class="col-sm-4">
            <div class="input-group">
                <?php echo $form->textField($model,'cart_price',array('class'=>'form-control')); ?>
                <span class="input-group-addon">VND</span>
            </div>
            <?php echo $form->error($model,'cart_price',array('class'=>'alert alert-danger')); ?>
        </div>

    </div>

	<div class="form-group buttons">
        <div class="col-sm-2">
            &nbsp;
        </div>
        <div class="col-sm-4">
            <?php echo TbHtml::submitButton($model->isNewRecord ? 'Tạo mã thẻ' : 'Sửa giá thẻ'); ?>
        </div>

	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->