<?php
/* @var $this SessionController */
/* @var $model CoursePackageOptions */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/<?php echo Yii::app()->controller->id.'/index/id/'.$model->package->id?>';
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
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Cấu hình học phí gói' : 'Cấu hình học phí gói`';?>: <?php echo $model->package->title; ?></h2>
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
            <?php echo $form->labelEx($model,'type'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->dropDownList($model,'type',$model->getTypeStudent()); ?>
            <?php echo $form->error($model,'type'); ?>
        </div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo $form->labelEx($model,'student'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->dropDownList($model,'student',$model->getClassNumbers()); ?>
            <?php echo $form->error($model,'type'); ?>
        </div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo $form->labelEx($model,'tuition'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'tuition'); ?>
            <?php echo $form->error($model,'tuition'); ?>
        </div>
    </div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'sales'); ?>
		</div>
		<div class="col col-lg-9">
            <?php echo $form->textField($model,'sales'); ?>
			<?php echo $form->error($model,'sales'); ?>
		</div>
	</div>
    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo $form->labelEx($model,'each_'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'each_'); ?>
            <?php echo $form->error($model,'each_'); ?>
        </div>
    </div>
    <?php if($model->package->type == CoursePackage::TYPE_TRIAL): ?>
    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo CHtml::label('Giá nộp bằng thẻ điện thoại',''); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo CHtml::textField('Meta[mobicard_final_price]',$model->getOptionMeta('mobicard_final_price')); ?>
        </div>
    </div>
    <?php endif; ?>
	<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
