<?php
/* @var $this SubjectController */
/* @var $model Subject */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/classes/';
	}
	function removeSubject(subjectId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn xóa môn học này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/subject/delete/id/'+subjectId;
		}
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm môn học' : 'Sửa môn học';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeSubject(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'class_id'); ?>
        </div>
		<div class="col col-lg-9">
			<?php $classAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'): array();?>
			<?php echo $form->dropDownList($model,'class_id', $classes, $classAttrs); ?>
			<?php echo $form->error($model,'class_id'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'name'); ?>
        </div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>128)); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'allow_to_teach'); ?>
        </div>
		<div class="col col-lg-9">
			<?php echo $form->checkBox($model, 'allow_to_teach', array()); ?>
			<?php echo $form->error($model,'allow_to_teach'); ?>
		</div>
	</div>
	<div class="clearfix h25">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->