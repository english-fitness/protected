<?php
/* @var $this CourseController */
/* @var $model Course */
/* @var $form CActiveForm */
?>
<script src="/media/js/moment.min.js"></script>
<script type="text/javascript">
	function cancel(){
		<?php $type = (!$model->isNewRecord)? $model->type: 1;?>
		window.location = daykemBaseUrl + '/admin/course?type=<?php echo $type; ?>';
	}
	function enableTeacher(){
		$('#Course_teacher_id').removeAttr('disabled');
	}
	//Unassign student to Course
	function unassignStudent(student_id, course_id){
		var checkConfirm = confirm("Bạn có chắc chắn muốn loại học sinh này khỏi khóa học?");
		if(checkConfirm){
			window.location = daykemBaseUrl + '/admin/course/unassignStudent?student_id=' + student_id + '&course_id='+course_id;
		}
	}
	function removeCourse(courseId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn xóa khóa học này?");
		if(checkConfirm){
			window.location = daykemBaseUrl + '/admin/course/delete/id/'+courseId;
		}
	}
	function toggleChangeSchedule(show, mode)
	{
		if (show)
		{
			$("#update_schedule").removeAttr("disabled");
			$(".toggle_schedule_link").hide();
            switch (mode){
                case 'change':
                    $("#update_schedule > legend").html("Sửa lịch học (Lịch học của tất cả các buổi học còn lại sẽ được sửa theo lịch dưới đây)");
                    $("#number_of_session").prop("disabled", true).hide();
                    $("#start_date").prop("disabled", true).hide();
                    $(".assignedStudent").prop("disabled", true);
                    $("#switch_schedule_mode_link").html("Thêm buổi học").attr("href", "javascript: toggleChangeSchedule(true, 'add')");
                    $("#switch_schedule_mode").show();
                    $("#change_schedule_mode").val("change");
                    break;
                case 'add':
                    $("#update_schedule > legend").html("Thêm buổi học (Các buổi học mới sẽ được thêm vào sau buổi học cuối cùng theo lịch học dưới đây)");
                    $("#number_of_session").prop("disabled", false).show();
                    $("#start_date").prop("disabled", false).show();
                    $(".assignedStudent").prop("disabled", false);
                    $("#switch_schedule_mode_link").html("Sửa lịch học").attr("href", "javascript: toggleChangeSchedule(true, 'change')");
                    $("#switch_schedule_mode").show();
                    $("#change_schedule_mode").val("add");
                    break;
                default:
                    break;
            }
            $("#update_schedule").show();
		}
		else
		{
			$("#update_schedule").attr("disabled", true);
            $("#change_schedule_mode").hide();
			$("#update_schedule").hide();
			$(".toggle_schedule_link").show();
            $("#switch_schedule_mode").hide();
		}
        
        return false;
	}
    
    $(document).ready(function(){
        <?php if (!$model->isNewRecord):?>
            var now = moment('<?php echo date('Y-m-d')?>');
            var lastSessionDate = moment('<?php echo date('Y-m-d', strtotime($model->getLastSessionDate()))?>');
            console.log(now);
            console.log(lastSessionDate);
            var minDate = lastSessionDate > now ? lastSessionDate : now;
            $("#start_date > .col > .datepicker").val(minDate.format("YYYY-MM-DD"));
            
            $(document).on("click","#start_date > .col > .datepicker",function(){
                $(this).datepicker({
                    dateFormat:"yy-mm-dd",
                    minDate:minDate.format("YYYY-MM-DD"),
                }).datepicker("show");;
            });
        <?php endif;?>
    });
</script>
<div class="form">
<?php $registration = new ClsRegistration();?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Tạo khóa học mới' : 'Sửa thông tin khóa học';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && $model->status==Course::STATUS_PENDING):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeCourse(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa khóa học</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
	<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 errorMessage">Khóa học này đã bị hủy bỏ, để xóa hoàn toàn khóa học & các buổi học trong khóa, bạn hãy vui lòng nhấn tiếp "Xóa khóa học"!</div>
	</div>
	<?php endif;?>
<fieldset>
	<legend>Thông tin khóa học</legend>
	<?php $disabledAttrs = (!$model->isNewRecord && $model->status==Course::STATUS_ENDED)? array('disabled'=>'disabled'):array();?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="Course_class">Lớp/môn học</label>
		</div>
		<div class="col col-lg-9">
			<?php echo Subject::model()->displayClassSubject($model->subject_id);?>
		</div>
	</div>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'type'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'type', $model->typeOptions(), array('disabled'=>'disabled')); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'title'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'title',array_merge(array('size'=>60,'maxlength'=>256),$disabledAttrs)); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Kiểu lớp học</label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'total_of_student', $registration->totalStudentOptions(12, false, $model->total_of_student), $disabledAttrs); ?>
			<?php echo $form->error($model,'total_of_student'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'level'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'level',array_merge(array('size'=>60,'maxlength'=>50))); ?>
			<?php echo $form->error($model,'level'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'curriculum'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'curriculum',array_merge(array('size'=>60,'maxlength'=>50))); ?>
			<?php echo $form->error($model,'curriculum'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Nội dung khóa học</label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em;')); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>
	<?php if(!$model->isNewRecord):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Trạng thái khóa học</label>
		</div>
		<div class="col col-lg-9">
			<?php $statusOptions = Course::statusOptions();?>
			<?php echo $form->dropDownList($model,'status', $statusOptions, $disabledAttrs); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
	<?php endif;?>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Gán giáo viên, học sinh cho khóa học</legend>	
	<?php $endedDisplayCss = (!$model->isNewRecord && $model->status==Course::STATUS_ENDED)? "dpn":"";?>
	<?php if(!$model->isNewRecord):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="Course_class">Giáo viên đã được gán</label>
		</div>
		<div class="col col-lg-9">
			<select id="Course_teacher_id" name="Course[teacher_id]" <?php echo ($model->teacher_id>0)? "disabled='disabled'": "";?>>				
				<option value=''>Chọn giáo viên...</option>
				<?php foreach($availableTeachers as $teacher):?>
					<?php $priorityLabel = isset($priorityTeachers[$teacher->id])? " - Ưu tiên ".$priorityTeachers[$teacher->id]: "";?>
					<option value='<?php echo $teacher->id?>' <?php echo ($teacher->id==$model->teacher_id)? "selected='selected'": "";?>>
					<?php echo $teacher->fullName().' ('.$teacher->email.')'.$priorityLabel;?>
					</option>
				<?php endforeach;?>				
				
			</select>
			<?php if($model->teacher_id>0):?>
			<div class="fR"><a class="fs12 errorMessage <?php echo $endedDisplayCss;?>" href="javascript: enableTeacher();">Thay đổi giáo viên cho khóa học</a></div>
			<?php endif;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="Course_class">Học sinh đã được gán</label>
		</div>
		<div class="col col-lg-9">
			<div id="divAssignedStudents" class="class-subjects">
				<?php 
					foreach($availableStudents as $student):
				?>
					<div class="assignStudents">
						<span class="fL w10">&nbsp;</span><input type="checkbox" checked="checked" disabled="disabled"
						 class="fL mL10" name="assignStudents[]" value="<?php echo $student->id?>"/>
						<span class="fL">&nbsp;<?php echo $student->lastname.' '.$student->firstname.' ('.$student->email.')';?></span>
						<?php if($model->status!=Course::STATUS_ENDED):?>
						<a class="pL20 errorMessage" href="javascript: unassignStudent(<?php echo $student->id.','.$model->id?>);" con>Hủy gán học sinh</a>
						<?php endif;?>
						<br/>
					</div>
				<?php endforeach;?>
			</div>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label for="Course_class">Tìm kiếm & gán thêm học sinh</label></div>
		<div class="col col-lg-9">
			<?php $ajaxSearchUser = Yii::app()->request->getPost('ajaxSearchUser', "");?>
            <?php $this->renderPartial("widget/ajaxAddUser",array("ajaxSearchUser"=>$ajaxSearchUser)); ?>
            <label class="hint">Gán thêm các học sinh có nhu cầu tham gia vào khóa học này!</label>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'teacher_form_url'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'teacher_form_url', array()); ?>
			<?php echo $form->error($model,'teacher_form_url'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'student_form_url'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'student_form_url', array()); ?>
			<?php echo $form->error($model,'student_form_url'); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<?php endif;?>
<?php if ($action == 'update'):?>
<div class="clearfix h20">&nbsp;</div>
<div class="fR toggle_schedule_link">
    <a id="add_schedule_link" class="fs12 errorMessage <?php echo $endedDisplayCss;?>" href="javascript: toggleChangeSchedule(true, 'add');">Thêm buổi học</a>
    |
    <a id="change_schedule_link" class="fs12 errorMessage <?php echo $endedDisplayCss;?>" href="javascript: toggleChangeSchedule(true, 'change');">Thay đổi lịch học</a>
</div>
<div class="fR dpn" id="switch_schedule_mode">
    <a id="switch_schedule_mode_link" class="fs12 errorMessage <?php echo $endedDisplayCss;?>" href="javascript: toggleChangeSchedule(true);"></a>
</div>
<div class="clearfix"></div>
<fieldset id="update_schedule" style="display:none" disabled>
    <input id="change_schedule_mode" name="scheduleChange" type="hidden"/>
    <?php foreach($availableStudents as $student):?>
        <input type="hidden" name="Student[]" class="assignedStudent" disabled value="<?php echo $student->id?>">
    <?php endforeach;?>
	<legend>Kế hoạch các buổi học trong khóa</legend>
	<div class="col col-lg-12">
		<?php
				echo $this->renderPartial('widget/updateCourseSessions');
		?>
	</div>
	<div class="fR"><a class="fs12 errorMessage <?php echo $endedDisplayCss;?>" href="javascript: toggleChangeSchedule(false);">Hủy thay đổi lịch học</a></div>
</fieldset>
<?php endif;?>
<div class="clearfix h50">&nbsp;</div>	
<?php $this->endWidget(); ?>

</div><!-- form -->