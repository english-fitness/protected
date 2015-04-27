<?php
/* @var $this SessionCommentController */
/* @var $model SessionComment */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/sessionComment/';
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'session-comment-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Create New Comment' : 'Update Comment';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<?php $btnValue = $model->isNewRecord ? 'Create' : 'Save';?>
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i><?php echo $model->isNewRecord ? 'Create' : 'Save';?></button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Cancel</button>
	        </div>
	    </div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'session_id'); ?>
        </div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'session_id', $sessions, array()); ?>
			<?php echo $form->error($model,'session_id'); ?>
		</div>	
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'comment'); ?>
        </div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'comment',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'comment'); ?>
		</div>		
	</div>
	
<?php $this->endWidget(); ?>

</div><!-- form -->