<style>
.datepicker[readonly]{
	background-color: white;
}
</style>
<script src="/media/js/utils.js"></script>
<?php $readOnlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'$(this).removeAttr("readonly")'): array();?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course_report_form',
    "htmlOptions"=>array(
		"enctype"=>"multipart/form-data"
	),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Báo cáo mới' : 'Sửa báo cáo';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        </div>
	    </div>
	</div>
<fieldset>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="teacher_search">Giáo viên <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
            <?php 
                $teacherName = $model->isNewRecord ? $model->course->teacher->fullname() : $model->reportingTeacher->fullname();
                $teacherId = $model->isNewRecord ? $model->course->teacher->id : $model->course->teacher->id;
                $readonly = !$model->isNewRecord ? 'ondblclick="allowEdit(this)" readonly' : '';
            ?>
            <input type="text" id="teacher-search" <?php echo 'value="'.$teacherName.'" '.$readonly?>>
            <span class="fs12 errorMessage" style="color:grey">Nhập tên giáo viên để tìm kiếm và chọn giáo viên từ kết quả</span>
            <input type="hidden" id="hidden-teacher-id" name="CourseReport[reporting_teacher]" value="<?php echo $teacherId?>">
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model, 'student_id')?>
		</div>
		<div class="col col-lg-9">
            <?php echo $form->dropdownList($model, 'student_id', $model->course->getAssignedStudentsArrs())?>
            <?php echo $form->error($model, 'student_id')?>
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model, 'report_date')?>
		</div>
		<div class="col col-lg-9">
            <?php echo $form->textField($model, 'report_date', array("class"=>"datepicker", "value"=>date("Y-m-d"), "readonly"=>true))?>
            <?php echo $form->error($model, 'report_date')?>
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model, 'report_file')?>
		</div>
		<div class="col col-lg-9">
            <input type="file" name="report_file" style="line-height:10px">
            <?php echo $form->error($model, 'report_file')?>
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model, 'student_comment')?>
		</div>
		<div class="col col-lg-9">
            <?php echo $form->textArea($model, 'student_comment', array_merge($readOnlyAttrs, array("maxlength"=>1000)))?>
            <?php echo $form->error($model, 'student_comment')?>
		</div>
	</div>
    <?php if (!$model->isNewRecord):?>
    <div class="form-element-container row">
        <span>Tạo bởi <b><?php echo $model->createdUser->fullname()?></b> lúc <?php echo $model->created_date?></span>
        <br>
        <span>Sửa lần cuối bởi <b><?php echo $model->lastModifiedUser->fullname()?></b> lúc <?php echo $model->last_modified_date?></span>
    </div>
    <?php endif;?>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
    $(document).ready(function() {
        $(document).on("click",".datepicker",function(){
            $(this).datepicker({
                "dateFormat":"yy-mm-dd",
                "firstDay":1,
            }).datepicker("show");
        });
        
        SearchBox.bindSearchEvent("#teacher-search", AjaxCall.searchTeacher, displaySearchResults);
    });
    
    function displaySearchResults(results){
        SearchBox.autocomplete({
            searchBox:'#teacher-search',
            results:results,
            resultDisplay:'usernameAndFullName',
            selectCallback:function(id){
                $('#hidden-teacher-id').val(id);
            }
        });
    }

    function cancel(){
    	window.location.href = "<?php echo Yii::app()->request->urlReferrer?>";
    }

    function allowEdit(element){
    	$(element).prop('readonly', false);
    }
</script>