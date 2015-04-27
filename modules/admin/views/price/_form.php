<?php
/* @var $this SessionController */
/* @var $model Session */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/<?php echo Yii::app()->controller->id?>';
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
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm giá khóa học' : 'Sửa giá khóa học';?></h2>
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
			<?php echo $form->labelEx($model,'total_student'); ?>
		</div>
		<div class="col col-lg-9">
            <?php echo $form->textField($model,'total_student',array('style'=>'width:200px;')); ?>
			<?php echo $form->error($model,'total_student'); ?>
		</div>
	</div>

    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo $form->labelEx($model,'hoc_thu'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'hoc_thu',array('style'=>'width:200px;')); ?>
            <?php echo $form->error($model,'hoc_thu'); ?>
        </div>
    </div>

    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo $form->labelEx($model,'hoc_thu_banking'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'hoc_thu_banking',array('style'=>'width:200px;')); ?>
            <?php echo $form->error($model,'hoc_thu_banking'); ?>
        </div>
    </div>

    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo $form->labelEx($model,'Giá gốc một buổi'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'tuition',array('style'=>'width:200px;')); ?>
            <?php echo $form->error($model,'tuition'); ?>
        </div>
    </div>

    <div class="form-element-container row">
        <div class="col col-lg-3">
            <?php echo $form->labelEx($model,'sale'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'sale',array('style'=>'width:200px;')); ?>
            <?php echo $form->error($model,'sale'); ?>
        </div>
    </div>

	<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
