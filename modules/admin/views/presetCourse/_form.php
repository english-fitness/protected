<?php
/* @var $this PresetCourseController */
/* @var $model PresetCourse */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	//Cancel button
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl.'/admin/presetCourse';?>';
	}
	//Remove preset course
	function removePreset(presetId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa đơn/khóa tạo trước này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/presetCourse/delete/id/'+presetId;
		}
	}
	//Change subject dropdownlist
	function changeSubject(){
		var subjectId = $("#PresetCourse_subject_id").val();
		if(subjectId!=""){
			window.location.href = "/admin/presetCourse/create?subject_id="+subjectId;
		}
	}
	//Allow edit html object field
	function allowEdit(htmlObject){
		$(htmlObject).removeAttr('readonly');
	}
	//Create course from preset course
	function createPresetCourse(presetId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn khóa học thực tế cho khóa tạo trước này?");
		if(checkConfirm){
			$("body").append('<div id="popup_background"></div>' );
			var data = {'preset_id': presetId};
			$.ajax({
				url: daykemBaseUrl + "/admin/presetCourse/ajaxCreateCourse",
				type: "POST", dataType: 'json', data:data,
				success: function(data) {
					if(data.success){
						alert("Tạo khóa học thực tế thành công!");
						window.location.href = "/admin/course/?Course[status]=<?php echo Course::STATUS_PENDING;?>&type=<?php echo Course::TYPE_COURSE_PRESET;?>";
					}else{
						alert("Chưa tạo được khóa học thực tế, vui lòng kiểm tra lại ngày bắt đầu & thông tin khác!");
					}
					document.getElementById('popup_background').remove();
				}
			});
		}
	}
	$(document).on("click",".datepicker",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'preset-course-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm đơn/khóa tạo trước' : 'Sửa đơn/khóa tạo trước';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin() && $model->status==PresetCourse::STATUS_PENDING):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removePreset(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 errorMessage">Đơn/khóa tạo trước này đã bị xóa/hủy bỏ, để xóa hoàn toàn vui lòng nhấn "Xóa bản ghi"!</div>
	</div>
<?php endif;?>
<?php 
	$readOnlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'allowEdit(this)'): array();
	$readOnlyAttrStr = (!$model->isNewRecord)? 'readonly="readonly" ondblclick="allowEdit(this)"': "";
	if(!$model->isNewRecord && $model->status==PresetCourse::STATUS_ACTIVATED){
		$readOnlyAttrs = array('disabled'=>'disabled');//Disabled activated preset course
		$readOnlyAttrStr = 'disabled="disabled"';
	}
?>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'subject_id'); ?>
	</div>
	<div class="col col-lg-9">
		<?php 
			$disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'): array();
			$subjects = array(""=>"Chọn môn học...")+$subjects;
		?>
		<?php echo $form->dropDownList($model, 'subject_id', $subjects, array_merge($disabledAttrs, array('onchange'=>"changeSubject();")));?>
		<?php echo $form->error($model,'subject_id'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'teacher_id'); ?>
	</div>
	<div class="col col-lg-9">
		<?php $teacherDisabledAttr = (!$model->isNewRecord && $model->teacher_id>0)? 'disabled="disabled"':'';?>
		<select id="PresetCourse_teacher_id" name="PresetCourse[teacher_id]" <?php echo $teacherDisabledAttr;?>>
			<option value=''>Chọn giáo viên...</option>
			<?php if(isset($availableTeachers) && count($availableTeachers)>0):
				 foreach($availableTeachers as $teacher):?>
				<option value="<?php echo $teacher->id?>" <?php echo ($teacher->id==$model->teacher_id)? 'selected="selected"': '';?>>
				<?php echo $teacher->fullName().' ('.$teacher->email.')';?>
				</option>
			<?php endforeach;
			endif;?>
		</select>
		<?php echo $form->error($model,'teacher_id'); ?>
		<?php if(!$model->isNewRecord && $model->teacher_id>0):?>
			<div class="fR"><a class="fs12 errorMessage" href="javascript: $('#PresetCourse_teacher_id').removeAttr('disabled');">Thay đổi giáo viên cho đơn/khóa tạo trước</a></div>
		<?php endif;?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'title'); ?>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->textField($model,'title', array_merge($readOnlyAttrs, array('size'=>60,'maxlength'=>256))); ?>
		<?php if(!$model->isNewRecord):?>
		<label class="hint"><span class="error">Click đúp vào trường cần sửa, để cho phép thay đổi giá trị</span></label>
		<?php endif;?>
		<?php echo $form->error($model,'title'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'min_student'); ?>
	</div>
	<div class="col col-lg-9">
		<div class="col col-lg-3 pL0i pR0i">
			<?php echo $form->textField($model,'min_student', $readOnlyAttrs); ?>
			<?php echo $form->error($model,'min_student'); ?>
		</div>
		<div class="col col-lg-9 pL0i pR0i">
			<div class="col col-lg-4 pL0i text-right">
				<?php echo $form->labelEx($model,'max_student', array('class'=>'mT10')); ?>
			</div>
			<div class="col col-lg-8 pL0i pR0i">
				<?php echo $form->textField($model,'max_student', $readOnlyAttrs); ?>
				<?php echo $form->error($model,'max_student'); ?>
			</div>
		</div>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'total_of_session'); ?>
	</div>
	<div class="col col-lg-9">
		<div class="col col-lg-3 pL0i pR0i">
			<?php echo $form->textField($model,'total_of_session', $readOnlyAttrs); ?>
			<?php echo $form->error($model,'total_of_session'); ?>
		</div>
		<div class="col col-lg-9 pL0i pR0i">
			<div class="col col-lg-4 pL0i text-right">
				<?php echo $form->labelEx($model,'start_date', array('class'=>'mT10')); ?>
			</div>
			<div class="col col-lg-8 pL0i pR0i">
				<?php echo $form->textField($model,'start_date', array_merge(array('class'=>'datepicker'),$readOnlyAttrs)); ?>
				<?php echo $form->error($model,'start_date'); ?>
			</div>
		</div>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'price_per_student'); ?>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->textField($model,'price_per_student', $readOnlyAttrs); ?>
		<?php echo $form->error($model,'price_per_student'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'session_per_week'); ?>
	</div>
	<div class="col col-lg-9">
		<?php $placeHolderAttrs = array('placeholder'=>'Ví dụ: {"Monday":"09:00 - 10:30","Wednesday":"09:00 - 10:30"}');?>
		<?php echo $form->textField($model,'session_per_week', array_merge($readOnlyAttrs, $placeHolderAttrs)); ?>
		<label class="hint"><b>Lịch học trong tuần: </b><?php echo ClsAdminHtml::displaySessionPerWeek($model->session_per_week); ?></label>
		<div class="fs11"><span class="fL"><b>Ngày trong tuần:</b> Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday</span>, <span class="fR"><b>Ví dụ mẫu: </b>{"Monday":"09:00 - 10:30","Wednesday":"09:00 - 10:30"}</span></div>
		<?php echo $form->error($model,'session_per_week'); ?>		
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
		<?php echo $form->labelEx($model,'short_description'); ?>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->textArea($model,'short_description',array_merge($readOnlyAttrs, array('rows'=>6, 'cols'=>50, 'style'=>'height:6em'))); ?>
		<?php echo $form->error($model,'short_description'); ?>
	</div>
</div>
<?php if(!$model->isNewRecord && isset($model->id) && $model->status>=PresetCourse::STATUS_REGISTERING):?>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Số đơn đăng ký học?</label>
	</div>
	<div class="col col-lg-9">
		<?php echo CHtml::link($model->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID)."/".$model->countRegisteredStudents()." đơn đăng ký học", Yii::app()->createUrl("admin/preregisterCourse?preset_id=$model->id"));?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Khóa học thực tế đã tạo?</label>
	</div>
	<div class="col col-lg-9">
		<?php 
			$actualCourse = $model->displayActualCourse();
			if($actualCourse!==NULL): echo $actualCourse;
			else:
		?>
		<span class="error fL fs12">Khóa học thực tế chưa tạo, hãy cập nhật và "Lưu lại" thông tin trước khi tạo khóa học thực tế!</span>
		<span class="fR"><a href="javascript:createPresetCourse(<?php echo $model->id;?>)"><i class="icon-plus"></i> Click vào đây để tạo khóa học thực tế</a></span>
		<?php endif;?>
	</div>
</div>
<?php endif;?>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $form->labelEx($model,'description'); ?>
	</div>
	<div class="col col-lg-9">
		<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$model,
				'attribute'=>'description',
				'language'=>'en',
				'editorTemplate'=>'advanced',
				'toolbar' => array(
                    array('-','Source','-','Bold','Italic','Underline','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote','-','Link','Unlink','-','SpecialChar','-','Cut','Copy','Paste','-','Undo','Redo','-','Maximize','-','About'),
                ),
			)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Ghi chú khóa học, học phí...</label>
	</div>
	<div class="col col-lg-9">
		<?php echo $form->textArea($model,'note',array_merge($readOnlyAttrs, array('rows'=>6, 'cols'=>50, 'style'=>'height:6em'))); ?>
		<?php echo $form->error($model,'note'); ?>
	</div>
</div>
<?php $this->renderPartial("/presetCourse/priceRules", array('stepPriceRules'=>$model->generatePriceRules(), 'readOnlyAttrStr'=>$readOnlyAttrStr)); ?>
<?php $this->endWidget(); ?>

</div><!-- form -->