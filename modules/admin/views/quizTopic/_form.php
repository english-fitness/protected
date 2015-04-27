<?php
/* @var $this QuizTopicController */
/* @var $model QuizTopic */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/quizTopic/';
	}
	function removeTopic(topicId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa chủ đề này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/quizTopic/delete/id/'+topicId;
		}
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quiz-topic-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm chủ đề lý thuyết' : 'Sửa chủ đề lý thuyết';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && $model->countChildren()==0 && $model->status==QuizTopic::STATUS_PENDING):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeTopic(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Lớp/môn học</label><span class="required">*</span>
		</div>
		<div class="col col-lg-9">
			<?php if($model->parent_id>0 && $model->subject_id>0):
				echo '<b>'.Subject::model()->displayClassSubject($model->subject_id).'</b>';
				if($model->isNewRecord):
					echo $form->hiddenField($model,'subject_id',array());
					echo $form->hiddenField($model,'parent_id',array());
					echo $form->hiddenField($model,'parent_path',array());
				endif;
			else:
				$disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();
				echo $form->dropDownList($model,'subject_id', $subjects, $disabledAttrs);
				echo $form->error($model,'subject_id');
			endif;
			?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Đường dẫn</label></div>
		<div class="col col-lg-9">
			<?php echo $model->displayBreadcrumbs('/admin/quizTopic?parent_id=', '&nbsp;>&nbsp;', 'Chủ đề môn học');?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'name'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'name'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'status', $model->statusOptions(), array()); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'content'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$model,
				'attribute'=>'content',
				'language'=>'en',
				'editorTemplate'=>'full',
			)); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->