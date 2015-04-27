<?php
/* @var $this PreregisterCourseController */
/* @var $model PreregisterCourse */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl.'/admin/preregisterCourse?status='.$model->status; ?>';
	}
	function removeRequest(reqId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa đơn xin học này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/preregisterCourse/delete/id/'+reqId;
		}
	}
	function openSelectedCourse(){
		var courseId = $("#waitingMergedCourse").val();
        if(courseId!=""){
        	window.open('<?php echo Yii::app()->baseUrl; ?>/admin/session?course_id='+courseId);
        }
	}
	function allowEdit(htmlObject){
		$(htmlObject).removeAttr('readonly');
	}
	$(document).on("click",".datepicker",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
</script>
<div class="form">
<?php $registration = new ClsRegistration();?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'preregister-course-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm đơn xin học mới' : 'Sửa/phê duyệt đơn xin học';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin() && $model->status==PreregisterCourse::STATUS_PENDING):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeRequest(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 errorMessage">Đơn xin học này đã bị xóa/hủy bỏ, để xóa hoàn toàn đơn xin học này vui lòng nhấn "Xóa bản ghi"!</div>
	</div>
	<?php endif;?>	
<fieldset>
	<legend>Thông tin đơn xin học</legend>
	<?php 
		$disabledAttrs = array();
		if(!$model->isNewRecord && $model->payment_status==PreregisterCourse::PAYMENT_STATUS_PAID):
			$disabledAttrs = array('disabled'=>'disabled');
		endif;
	?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Học sinh đăng ký <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<span><?php echo $model->getStudent("/admin/student/view/id");?></span>
			<span>(<b>Lớp/môn học:</b> <?php echo Subject::model()->displayClassSubject($model->subject_id);?>)</span>
		</div>
	</div>
	<?php $readOnlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'allowEdit(this)'): array();?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Chủ đề khóa học <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'title', $readOnlyAttrs); ?>
			<label class="hint"><span class="error">Click đúp vào trường cần sửa, để cho phép thay đổi giá trị</span></label>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Kiểu lớp học <span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'total_of_student', $registration->totalStudentOptions(6, false, $model->total_of_student), $disabledAttrs); ?>
			<?php echo $form->error($model,'total_of_student'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'totalOfSession'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'totalOfSession', array_merge($readOnlyAttrs, $disabledAttrs)); ?>
			<?php echo $form->error($model,'totalOfSession'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Ngày bắt đầu dự kiến</label>
		</div>
		<div class="col col-lg-9">
			<?php if(isset($model->start_date)) $model->start_date = date('Y-m-d', strtotime($model->start_date));?>
			<?php echo $form->textField($model,'start_date', array('class'=>'datepicker', 'readonly'=>'readonly')); ?>
			<?php echo $form->error($model,'start_date'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Kiểu khóa học</label>
		</div>
		<div class="col col-lg-9">
			<?php $courseTypes = Course::model()->typeOptions();?>
			<?php echo $form->dropDownList($model,'course_type', $courseTypes, $disabledAttrs); ?>
			<?php echo $form->error($model,'course_type'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'session_per_week'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'session_per_week', $readOnlyAttrs); ?>
			<div class="fs11 clearfix pB5"><span class="error">Lịch học chi tiết: </span><?php echo ClsAdminHtml::displaySessionPerWeek($model->session_per_week); ?></div>
			<div class="fs11 clearfix"><span class="fL"><b>Ví dụ mẫu: </b>{"Monday":"09:00 - 10:30","Wednesday":"09:00 - 10:30"}</span> <span class="fR"><b>Ngày trong tuần:</b> Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday</span></div>
			<?php echo $form->error($model,'session_per_week'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'note', array_merge($readOnlyAttrs, array('rows'=>6, 'cols'=>50, 'style'=>'height:6em'))); ?>
			<?php echo $form->error($model,'note'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'status', $model->statusOptions(), $disabledAttrs); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Học phí, khóa học & ghép lớp</legend>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Khóa học/Ghép lớp?</label><br/>
			
		</div>
		<div class="col col-lg-9">
			<?php 
				$actualCourse = $model->displayActualCourse();
				if($actualCourse!==NULL): echo $actualCourse;
				elseif($model->total_of_student>1):
					$mergeCourses = array(""=>"--- Danh sách khóa học đang chờ ghép lớp ---") + $mergeCourses;
					echo CHtml::dropDownList('PreregisterCourse[course_id]', "", $mergeCourses, array('id'=>'waitingMergedCourse'));
			?>
				<?php if($model->course_type==Course::TYPE_COURSE_PRESET):?>
					<span><a id="viewSelectedCourse" href="javascript:openSelectedCourse();">[Xem khóa học đã chọn để ghép lớp]</a></span>
				<?php else:?>
					<span class="fR"><a href="/admin/course/create?preCourseId=<?php echo $model->id;?>">[Tạo khóa học mới cho đơn xin học]</a></span>
				<?php endif;?>
			<?php elseif($model->course_type!=Course::TYPE_COURSE_PRESET):?>
				<span><a href="/admin/course/create?preCourseId=<?php echo $model->id;?>">[Tạo khóa học mới cho đơn xin học này]</a></span>
			<?php elseif($model->preset_course_id && !$model->course_id):?>
				<?php $presetCourse = PresetCourse::model()->findByPk($model->preset_course_id);?>
				<span>Thuộc đơn/khóa tạo trước: <a href="/admin/presetCourse/view/id/<?php echo $presetCourse->id;?>"><?php echo $presetCourse->title;?></a></span>
				<span class="fR error">Đang chờ ghép & bắt đầu khai giảng!</span>
			<?php endif;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_type'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'payment_type', ClsCourse::paymentTypes(), $disabledAttrs); ?>
			<?php echo $form->error($model,'payment_type'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'final_price'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'final_price', array_merge($readOnlyAttrs, $disabledAttrs)); ?>
			<?php echo $form->error($model,'final_price'); ?>
		</div>
	</div>
	<?php if(!$model->isNewRecord && in_array($model->course_type, array(Course::TYPE_COURSE_PRESET, Course::TYPE_COURSE_TRAINING))):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'mobicard_final_price', array('style'=>'color:red;')); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'mobicard_final_price', array_merge($readOnlyAttrs, $disabledAttrs)); ?>
			<?php echo $form->error($model,'mobicard_final_price'); ?>
		</div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'payment_status', ClsCourse::paymentStatuses(), $disabledAttrs); ?>
			<?php echo $form->error($model,'payment_status'); ?>
			<span><a href="/admin/preregisterPayment?precourse_id=<?php echo $model->id;?>">[Xem lịch sử thanh toán học phí]</a></span>
			<span class="fR"><a href="/admin/preregisterPayment/create?precourse_id=<?php echo $model->id;?>" class="error">Thêm lịch sử thanh toán(HS chuyển khoản qua ngân hàng)</a></span>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
					'model'=>$model,
					'attribute'=>'payment_note',
					'language'=>'en',
					'editorTemplate'=>'advanced',
					'toolbar' => array(
	                    array('-','Source','-','Bold','Italic','Underline','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote','-','Link','Unlink','-','SpecialChar','-','Cut','Copy','Paste','-','Undo','Redo','-','Maximize','-','About'),
	                ),
				)); ?>
			<?php echo $form->error($model,'payment_note'); ?>
		</div>
	</div>		
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->