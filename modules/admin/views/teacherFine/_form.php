<?php
/* @var $this DailyRecordController */
/* @var $model TeachingDay */
/* @var $form CActiveForm */
?>
<style>
.datepicker[readonly]{
	background-color: white;
}
</style>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'fine_record_form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'New Teacher Penalty Record' : 'Update Teacher Penalty Record';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeRecord();"><i class="btn-remove"></i>Xóa</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
<fieldset>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'created_date'); ?>
		</div>
		<div class="col col-lg-9">
			<?php
				$created_date = isset($model['created_date'])? $model['created_date']: date('Y-m-d');
			?>
			<input type="text" class="datepicker" name="TeacherFine[created_date]" id="created_date" value="<?php echo $created_date?>" readonly>
			<?php echo $form->error($model,'created_date'); ?>
		</div>
	</div>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'teacher_id'); ?>
		</div>
		<div class="col col-lg-9">
			<input type="text" id="teacher_search" <?php if(!$model->isNewRecord) echo 'disabled value="' . User::model()->findByPk($model->teacher_id)->fullname() . '"'?>>
			<input type="hidden" id="hidden_teacher_id" name="TeacherFine[teacher_id]" <?php if(!$model->isNewRecord) echo 'disabled';?>>
			<span id="teacher_id_warning" class="fs12 errorMessage pB10" style="display:none">
				Hãy nhập tên giáo viên và chọn từ menu tìm kiếm.
			</span>
			<?php if(!$model->isNewRecord):?>
			<div class="fR">
				<a id="toggle_change_teacher_link" class="fs12 errorMessage" href="javascript: toggleChangeTeacher(true);">Thay đổi giáo viên</a>
			</div>
			<?php endif;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'points'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'points', TeacherFine::model()->getPointOptions()); ?>
			<?php echo $form->error($model,'points'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'notes'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'notes',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em;', 'value'=>$model->notes, 'name'=>'TeacherFine[notes]')); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
	function cancel(){
		window.location = daykemBaseUrl+'/admin/teacherFine/fineRecords';
	}
	$(document).ready(function() {
		$(document).on("click",".datepicker",function(){
            $(this).datepicker({
                "dateFormat":"yy-mm-dd",
            }).datepicker("show");
        });
		bindSearchBoxEvent('teacher_search', searchTeacher);
		$('#fine_record_form').submit(function(){
			var teacher_id = $('#hidden_teacher_id');
			if (teacher_id.prop('disabled') == false && teacher_id.val() == ""){
				$('#teacher_id_warning').show();
				return false;
			} else {
				$('#teacher_id_warning').hide();
				return true;
			}
		});
	});
	
	//search box handler
	function bindSearchBoxEvent(searchBoxId, searchFunction){
		$("#"+searchBoxId).keyup(function(){
			var keyword =  $(this).val();
			if(keyword.length<=3 && keyword.length>0) {
				searchFunction.call(undefined, keyword);
			}
		});
	}

	function searchBoxAutocomplete(searchBox, results, selectCallback){
		var searchBox = $('#'+searchBox)
		if (selectCallback){
			searchBox.autocomplete({
				source: formatSearchResult(results),
				height:'50',
				select:function(e, ui){
					selectCallback.call(undefined, ui.item.id);
				},
			});
		} else {
			searchBox.autocomplete({
				source: formatSearchResult(results),
				height:'50',
			});
		}
	}

	function formatSearchResult(result){
		var formattedData = [];
		result.forEach(function(value,key){
			formattedData[formattedData.length] = {
				'value': value.usernameAndFullName,
				'id': value.id,
			}
		});
		return formattedData;
	}
	
	function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				searchBoxAutocomplete('teacher_search', data, function(id){$('#hidden_teacher_id').val(id);});
			}
		});
	}
	
	var oldTeacherNameValue = $('#teacher_search').val();
	function toggleChangeTeacher(display){
		if (display){
			$('#teacher_search').prop('disabled', false);
			$('#hidden_teacher_id').prop('disabled', false);
			var changeTeacherLink = $('#toggle_change_teacher_link');
			changeTeacherLink.attr('href', 'javascript:toggleChangeTeacher(false);');
			changeTeacherLink.html('Hủy thay đổi giáo viên');
		} else {
			$('#teacher_search').prop('disabled', true);
			$('#hidden_teacher_id').prop('disabled', true);
			var changeTeacherLink = $('#toggle_change_teacher_link');
			$('#teacher_search').val(oldTeacherNameValue);
			changeTeacherLink.attr('href', 'javascript:toggleChangeTeacher(true);');
			changeTeacherLink.html('Thay đổi giáo viên');
		}
	}
</script>