<?php
/* @var $this QuizItemController */
/* @var $model QuizItem */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/quizItem/';
	}
	function removeItem(itemId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa câu hỏi này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/quizItem/delete/id/'+itemId;
		}
	}
	//Generate sub items to parent quizItem
	function addNewSubItem(parentId){
		var data = {'parent_id': parentId};
		$.ajax({
			url: daykemBaseUrl + "/admin/quizItem/ajaxAddSubItem",
			type: "POST", dataType: 'html',data:data,
			success: function(data) {
				$('#subItemAnswers').append(data);
			}
		});
	}
	//Generate sub items to parent quizItem
	function deleteSubItem(subItemId){
		var data = {'item_id': subItemId};
		var checkConfirm = confirm("Bạn có chắc chắn xóa câu hỏi con này?");
		if(checkConfirm){
			$.ajax({
				url: daykemBaseUrl + "/admin/quizItem/ajaxDeleteSubItem",
				type: "POST", dataType: 'JSON', data:data,
				success: function(data) {
					document.getElementById('divItemId'+subItemId).remove();
				}
			});
		}
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quiz-item-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm câu hỏi mới' : 'Chỉnh sửa câu hỏi';?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
        	<?php if(!$model->isNewRecord && $model->status==QuizItem::STATUS_PENDING):?>
        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeItem(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
        	<?php endif;?>
        </div>
    </div>
</div>
<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
<div class="form-element-container row">
	<div class="col col-lg-3">&nbsp;</div>
	<div class="col col-lg-9 errorMessage">Câu hỏi này đã bị hủy bỏ, để xóa hẳn câu hỏi này, bạn hãy vui lòng nhấn tiếp "Xóa bản ghi"!</div>
</div>
<?php endif;?>
<?php if(isset($writingExams) && count($writingExams)>0):?>
<fieldset>
	<legend>Danh sách đề thi đang soạn(chờ ghép câu hỏi trắc nghiệm)</legend>
	<div class="form-element-container row writingExam">
		<div class="col col-lg-3"><label>Chọn đề thi để ghép thêm</label></div>
	    <div class="col col-lg-9">
	    	<?php $assignedExamIds = $model->getAssignedExamIds();?>
	        <?php $this->renderPartial("/quizExam/widget/writingExam", array("writingExams"=>$writingExams, 'checkedExamIds'=>$assignedExamIds)); ?>
	    </div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php endif;?>
<fieldset>
	<legend>Thông tin - Nội dung câu hỏi chính</legend>
	<?php if(!$model->isNewRecord && isset($assignedQuizExams)):?>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Đã ghép vào đề thi</label></div>
		<div class="col col-lg-9">
			<?php if(count($assignedQuizExams)>0):?>
				<?php $this->renderPartial("/quizExam/widget/assignedExam", array("assignedExams"=>$assignedQuizExams)); ?>
			<?php else:?>
			<span>Chưa được ghép vào đề thi nào!</span>
			<?php endif;?>
		</div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'subject_id'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();?>
			<?php echo $form->dropDownList($model, 'subject_id', $subjects, $disabledAttrs);?>
			<?php echo $form->error($model,'subject_id');?>
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
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Tập câu trả lời trắc nghiệm chính</legend>
	<?php echo $this->renderPartial("/quizItem/widget/itemAnswers", array('quizItem'=>$model)); ?>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php if(!$model->isNewRecord):?>
	<fieldset id="subItemAnswers">
		<legend>Tập các câu trả lời trắc nghiệm thuộc các câu hỏi con (nếu có)</legend>
		<div class="form-element-container row">
			<p class="pL15"><a href="javascript: addNewSubItem(<?php echo $model->id;?>)" class="error"><i class="icon-plus"></i>Tạo thêm câu hỏi con & tập câu trả lời trắc nghiệm tương ứng</a></p>
		</div>
		<?php 
			$subItems = $model->getSubItems();
			if(count($subItems)>0):
				foreach($subItems as $quizItem):
					echo $this->renderPartial("/quizItem/widget/itemAnswers", array('quizItem'=>$quizItem, 'parentStatus'=>$model->status));
				endforeach;
			endif;
		?>
		</fieldset>
	<div class="clearfix h20">&nbsp;</div>
<?php endif;?>
<fieldset>
	<legend>Thông tin khác về câu hỏi chính</legend>
	<?php if(!$model->isNewRecord):?>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Gán vào chủ đề?</label></div>
		<div class="col col-lg-9">
			<?php $quizTopics = QuizTopic::model()->generateTopicsBySubject($model->subject_id, "--");?>
			<?php echo CHtml::dropDownList('itemTopic', $model->getAssignedTopicId(), $quizTopics, array());?>
		</div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'level'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'level', $model->levelOptions(), array()); ?>
			<?php echo $form->error($model,'level'); ?>
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
			<?php echo $form->labelEx($model,'tags'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'tags',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'tags'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'suggestion'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$model,
				'attribute'=>'suggestion',
				'language'=>'en',
				'editorTemplate'=>'full',
			)); ?>
			<?php echo $form->error($model,'suggestion'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'explaination'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$model,
				'attribute'=>'explaination',
				'language'=>'en',
				'editorTemplate'=>'full',
			)); ?>
			<?php echo $form->error($model,'explaination'); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<?php $this->endWidget(); ?>

</div><!-- form -->