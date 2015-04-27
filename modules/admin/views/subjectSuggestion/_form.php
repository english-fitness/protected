<?php
/* @var $this SubjectSuggestionController */
/* @var $model SubjectSuggestion */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/subjectSuggestion/';
	}
	function removeSuggest(suggestId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa gợi ý này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/subjectSuggestion/delete/id/'+suggestId;
		}
	}
	$(document).ready(function() {
		$('#tutorClasses').change(function(){
			//Load ajax subjects by class
			var data = {'class_id': $(this).val()};
			$.ajax({
				url: "<?php echo Yii::app()->baseUrl; ?>" + "/admin/subjectSuggestion/ajaxLoadSubject",
				type: "POST", dataType: 'html',data:data,
				success: function(data) {
					$('#divDisplaySubject').html(data);
				}
			});
		});
	});
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'subject-suggestion-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
		<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm gợi ý chủ đề khóa học' : 'Sửa gợi ý chủ đề khóa học';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeSuggest(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
            <label>Lớp học</label>
        </div>
		<div class="col col-lg-9">
			<?php 
				$classes = CHtml::listData(Classes::model()->getAll(false), 'id', 'name');
				$classes = array(""=>"Chọn lớp...") + $classes;
				$selectedClassId = "";
				if(isset($_REQUEST['tutorClasses'])){
					$selectedClassId = $_REQUEST['tutorClasses'];
				}elseif(!$model->isNewRecord){
					$selectedClassId = $model->displayClassSubject('class')->id;
				}
				echo CHtml::dropDownList('tutorClasses', $selectedClassId, $classes, array('id'=>'tutorClasses'));
			?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'subject_id'); ?>
		</div>
		<div class="col col-lg-9">
			<span id="divDisplaySubject">
            <?php
            	$classSubjects = array(""=>"Chọn môn...");
				if($selectedClassId!=""){
					$classSubjects = array(""=>"Chọn môn...") + CHtml::listData(Subject::model()->findAllByAttributes(array('class_id'=>$selectedClassId)), 'id', 'name');
				}
				$selectedSubjectId = isset($model->subject_id)? $model->subject_id: "";
				echo CHtml::dropDownList('SubjectSuggestion[subject_id]', $selectedSubjectId, $classSubjects, array('id'=>'SubjectSuggestion_subject_id]'));
			?>
            </span>
			<?php echo $form->error($model,'subject_id'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'title'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'description'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em')); ?>
			<?php echo $form->error($model,'description'); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->