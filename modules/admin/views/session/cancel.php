<?php
/* @var $this SessionController */
/* @var $model Session */
/* @var $form CActiveForm */
?>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/session.js"></script>
<script type="text/javascript">
	function cancel(){
		window.location = daykemBaseUrl + '/admin/session?course_id=<?php echo $model->course_id; ?>';
	}
	function cancelSession(cancelType){
		if(cancelType==0){
			var checkConfirm = confirm("Bạn có chắc chắn muốn hủy buổi học này và không tạo buổi học bù?");
		}else{
			var checkConfirm = confirm("Bạn có chắc chắn muốn hủy buổi học này và có tạo buổi học bù?");
		}
		if(checkConfirm){
			$("#chkAddNewSession").val(cancelType);
			$("#session-form").submit();
		}
	}
	$(document).ready(function(){
        $(document).on("click",".datepicker",function(){
            $(this).datepicker({
                "dateFormat":"yy-mm-dd"
            }).datepicker("show");;
        });
    });
</script>
<?php 
	$registration = new ClsRegistration();//Init register session
	$hoursInDayArrs = $registration->hoursInDay();//Hours in day array
	$minutesInHourArrs = $registration->minutesInHour();//Minutes in Hour
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'session-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Báo hoãn/hủy buổi học</h2>
    </div>
</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'course_id'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo CHtml::link($model->course->title, Yii::app()->createUrl("admin/session?course_id=$model->course_id"));?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Chủ đề buổi học</label></div>
		<div class="col col-lg-9"><?php echo $model->subject;?></div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'type'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $typeOptions = $model->typeOptions();?>
			<?php echo $typeOptions[$model->type];?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Giáo viên</label></div>
		<div class="col col-lg-9">
			<?php echo $model->getTeacher("/admin/teacher/view/id");?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Học sinh được gán</label></div>
		<div class="col col-lg-9">
			<?php echo implode(", ", $model->getAssignedStudentsArrs("/admin/student/view/id"));?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Lý do hoãn/hủy buổi học</label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'status_note',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'status_note'); ?>
			<?php echo $form->hiddenField($model,'status', array('value'=>Session::STATUS_CANCELED)); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Lịch học bù (nếu có học bù)</label>
		</div>
		<div class="col col-lg-9">
			<div class="w250 fL">
				<?php $course = Course::model()->findByPk($model->course_id);?>
				<?php 
					$lastPlanStart = $course->getFirstDateInList("DESC", 'Y-m-d H:i:s');
					$nextPlanStart = date('Y-m-d H:i:s', strtotime($lastPlanStart)+7*86400);
				?>
				<?php echo CHtml::textField('planStart[date]', date('Y-m-d', strtotime($nextPlanStart)), array('class'=>'datepicker w200', 'readonly'=>'readonly')); ?>
			</div>
			<div class="w120 fL">
				<span class="fL mT5"><b>Giờ:</b>&nbsp;</span>
				<?php echo CHtml::dropDownList('planStart[hour]', date('H', strtotime($nextPlanStart)), $hoursInDayArrs, array('style'=>'width:70px;'));?>
			</div>
			<div class="w120 fL">	
	            <span class="fL mT5"><b>Phút:</b>&nbsp;</span>
	            <?php echo CHtml::dropDownList('planStart[min]', date('i', strtotime($nextPlanStart)), $minutesInHourArrs, array('style'=>'width:70px;'));?>
			</div>
			<label class="hint mT10">(Mặc định sẽ bằng lịch học của buổi cuối cùng trong khóa + 7 ngày)</label>
		</div>
	</div>
	<div class="form-element-container row">
	    <div class="col col-lg-3"><input type="hidden" id="chkAddNewSession" name="chkAddNewSession" value="0"/></div>
	    <div class="col col-lg-9 pT10 pB10">
        	<button class="btn btn-primary" name="form_action" type="button" onclick="cancelSession(0);"><i class="icon-undo"></i>Hủy buổi học, không học bù</button>
        	<button class="btn btn-primary mL15" name="form_action" type="button" onclick="cancelSession(1);"><i class="icon-plus"></i>Hủy buổi học, tạo buổi học bù</button>
	    </div>
	</div>
	<div class="clearfix h20"></div>
<?php $this->endWidget(); ?>

</div><!-- form -->