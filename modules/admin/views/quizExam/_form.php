<?php
/* @var $this QuizExamController */
/* @var $model QuizExam */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/quizExam/';
	}
	function removeExam(examId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa đề thi này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/quizExam/delete/id/'+examId;
		}
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quiz-exam-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm đề thi mới' : 'Chỉnh sửa đề thi';?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
        	<?php if(!$model->isNewRecord && $model->status==QuizExam::STATUS_PENDING):?>
        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeExam(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
        	<?php endif;?>
        </div>
    </div>
</div>
<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
<div class="form-element-container row">
	<div class="col col-lg-3">&nbsp;</div>
	<div class="col col-lg-9 errorMessage">Đề thi này đã bị hủy bỏ, để xóa hẳn đề thi này, bạn hãy vui lòng nhấn tiếp "Xóa bản ghi"!</div>
</div>
<?php endif;?>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'subject_id'); ?>
	</div>
	<div class="col col-lg-9">
		<?php $disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();?>
		<?php echo $form->dropDownList($model,'subject_id', $subjects, $disabledAttrs);?>
		<?php echo $form->error($model,'subject_id');?>
	</div>
</div>
<?php if(!$model->isNewRecord):?>
<div class="form-element-container row">
	<div class="col col-lg-3"><label>Gán vào chủ đề?</label></div>
	<div class="col col-lg-9">
		<?php $quizTopics = QuizTopic::model()->generateTopicsBySubject($model->subject_id, "--");?>
		<?php echo CHtml::dropDownList('examTopic', $model->getAssignedTopicId(), $quizTopics, array());?>
	</div>
</div>
<?php endif;?>
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
		<?php echo $form->labelEx($model,'type'); ?>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->dropDownList($model,'type', $model->typeOptions(), array()); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'duration'); ?>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->dropDownList($model,'duration', $model->durationOptions(), array()); ?>
		<?php echo $form->error($model,'duration'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'level'); ?>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->dropDownList($model,'level', $model->levelOptions(), array()); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>
</div>
<?php if(($model->isNewRecord || $model->status<=QuizExam::STATUS_WRITING) && $model->deleted_flag!=1):?>
<div class="form-element-container row">
	<div class="col col-lg-3"><label class="clrRed">Tôi đang soạn đề này?</label></div>
	<div class="col col-lg-9">
		<input type="checkbox" name="chkWriting" value="1" <?php if($model->isActivatedWritingExam()):?> checked="checked" <?php endif;?>/>
		<span class="error">Tại một thời điểm chỉ chọn 1 / tổng số các đề có trạng thái "Đề đang soạn"!</span>
	</div>
</div>
<?php endif;?>
<div class="clearfix h20">&nbsp;</div>	
<?php $this->endWidget(); ?>

</div><!-- form -->