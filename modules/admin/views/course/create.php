<?php
/* @var $this CourseController */
/* @var $model Course */
?>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/course.js"></script>
<script type="text/javascript">
	function cancel(){
		window.location = daykemBaseUrl+'/admin/course?type=1';
	}
	//Load suggest information from Preregister Course
	function loadSuggestCourse(){
		<?php if(isset($preCourse)): $classId = $preCourse->getClassId();?>
			$("#tutorClasses").val(<?php echo $classId;?>);
			ajaxLoads({'class_id': <?php echo $classId;?>, 'subject_id':<?php echo $preCourse->subject_id;?>}, 'divDisplaySubject', 'course/ajaxLoadSubjects');
			ajaxLoads({'subject_id': <?php echo $preCourse->subject_id;?>}, 'divDisplayTeacher', 'course/ajaxLoadTeachers');
		<?php endif;?>
	}	
	$(document).ready(function() {
		loadSuggestCourse();//Load suggest course from preregister course
		$(document).on("click",".datepicker",function(){
            $(this).datepicker({
                "dateFormat":"yy-mm-dd"
            }).datepicker("show");;
        });
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
	        <h2 class="page-title mT10">Tạo khóa học mới <?php echo isset($preCourse)? "từ đơn xin học":"";?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" id="btnCreate" name="form_action" type="button"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        </div>
	    </div>
	</div>
	<div class="row">
		<div class="col col-lg-12" id="validMessage"><span class="required"><?php echo isset($error)? $error: "";?></span></div>
	</div>
<fieldset>
	<legend>Thông tin khóa học</legend>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="Course_class">Lớp học</label>
		</div>
		<div class="col col-lg-9">
			<?php $selectedId = isset($_REQUEST['tutorClasses'])? $_REQUEST['tutorClasses']: "";?>
			<?php echo $this->renderPartial('widget/tutorClasses', array('selectedId'=>$selectedId));?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'subject_id'); ?>
		</div>
		<div class="col col-lg-9">
			<div id="divDisplaySubject">
				<select id="Course_subject_id" name="Course[subject_id]">				
					<option value=''>Chọn môn học...</option>
				</select>
			</div>
			<?php echo $form->error($model,'subject_id'); ?>
			<?php if(isset($preCourse)):?>
			<label class="hint">Gợi ý từ đơn xin học: <?php echo Subject::model()->displayClassSubject($preCourse->subject_id);?></label>
			<?php endif;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'type'); ?>
		</div>
		<div class="col col-lg-9">
			<?php if(isset($preCourse)) $model->type = $preCourse->course_type;?>
			<?php echo $form->dropDownList($model,'type', $model->typeOptions(), array()); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'title'); ?>
		</div>
		<div class="col col-lg-9">
			<?php if($model->title==NULL && isset($preCourse)) $model->title = $preCourse->title;?>
			<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'title'); ?>
			<?php if(isset($preCourse)):?>
			<label class="hint">Gợi ý từ đơn xin học: <?php echo $preCourse->title;?></label>
			<?php endif;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Kiểu lớp học (số HS, GV)</label>
		</div>
		<div class="col col-lg-9">
			<?php if(isset($preCourse)) $model->total_of_student = $preCourse->total_of_student;?>
			<?php echo $form->dropDownList($model,'total_of_student', $registration->totalStudentOptions(12), array()); ?>
			<?php echo $form->error($model,'total_of_student'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_type'); ?>
		</div>
		<div class="col col-lg-9">
			<?php if(isset($preCourse)) $model->payment_type = $preCourse->payment_type;?>
			<?php echo $form->dropDownList($model,'payment_type', ClsCourse::paymentTypes()); ?>
			<?php echo $form->error($model,'payment_type'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'final_price'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'final_price', array()); ?>
			<?php echo $form->error($model,'final_price'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'payment_status', ClsCourse::paymentStatuses()); ?>
			<?php echo $form->error($model,'payment_status'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'content'); ?>
		</div>
		<div class="col col-lg-9">
			<?php if($model->content==NULL && isset($preCourse)) $model->content = $preCourse->note;?>
			<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em;')); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>	
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Gán giáo viên, học sinh cho khóa học</legend>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Gán giáo viên</label>
		</div>
		<div class="col col-lg-9">
			<div id="divDisplayTeacher">
				<select id="Course_teacher_id" name="Course[teacher_id]">				
					<option value=''>Chọn giáo viên...</option>
				</select>
			</div>
			<?php echo $form->error($model,'teacher_id'); ?>
		</div>
	</div>	

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="Course_class">Gán học sinh</label>
		</div>
		<div class="col col-lg-9">
			<?php 
				$ajaxSearchUser = Yii::app()->request->getPost('ajaxSearchUser', "");
				if($ajaxSearchUser=="" && isset($preCourse)) $ajaxSearchUser = $preCourse->getEmail();
			?>
            <?php $this->renderPartial("widget/ajaxAddUser", array("ajaxSearchUser"=>$ajaxSearchUser)); ?>
            <?php if(isset($preCourse)):?>
			<label class="hint">Gợi ý từ đơn xin học: <?php echo $preCourse->getEmail();?></label>
			<?php else:?>
			<label class="hint">Gán các học sinh có nhu cầu tham gia vào khóa học này(tùy chọn hoặc gán sau)!</label>
			<?php endif;?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<fieldset>
	<legend>Kế hoạch các buổi học trong khóa</legend>
	<div class="col col-lg-12">
	<?php
		$suggestParams = array();
		if(isset($preCourse)){
			$suggestParams['preCourse'] = $preCourse;
			$suggestParams['suggestSchedules'] = $preCourse->getSuggestSchedules();
		}
		echo $this->renderPartial('widget/createSessions', $suggestParams);
	?>
	</div>
</fieldset>
<div class="clearfix h50">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
