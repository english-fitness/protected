<?php
/* @var $this SessionController */
/* @var $model Session */
/* @var $form CActiveForm */
?>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/session.js"></script>
<script type="text/javascript">
	function cancel(){
		window.location = daykemBaseUrl + '/admin/session?course_id=<?php echo $modelCourse->id; ?>';
	}
	function cancelSession(sessionId){
		window.location = '/admin/session/cancel/id/'+sessionId;
	}
	function enableTeacher(){
		$('#Session_teacher_id').removeAttr('disabled');
	}
	function changeTypeSession(){
		$('#Session_type').removeAttr('disabled');
	}
	function removeWhiteboard(sessionId, whiteboard){
		deleteBoard(sessionId, whiteboard);
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
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm buổi học mới' : 'Sửa thông tin buổi học';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<?php $btnValue = $model->isNewRecord ? 'Create' : 'Save';?>
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin() && $model->status<Session::STATUS_APPROVED):?>
        			<button class="btn btn-default remove" name="form_action" type="button" onclick="removeSession(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa buổi học</button>
	        	<?php endif;?>
	        	<?php if(!$model->isNewRecord && $model->status==Session::STATUS_APPROVED):?>
	        		<button class="btn btn-default remove" name="form_action" type="button" onclick="cancelSession(<?php echo $model->id;?>);"><i class="icon-undo"></i>Báo hoãn/hủy buổi học</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
	<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 errorMessage">Buổi học này đã bị hủy bỏ, để xóa hoàn toàn buổi học này, bạn hãy vui lòng nhấn tiếp "Xóa buổi học"!</div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'course_id'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->hiddenField($model,'course_id',array('value'=>$modelCourse->id)); ?>
			<input type="text" id="courseTitle" value="<?php echo $modelCourse->title?>" readonly="readonly"/>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'subject'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'subject',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'subject'); ?>
		</div>
	</div>
	<?php if(!$model->isNewRecord):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'type'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $typeDisabledArrs = ($model->type!=Session::TYPE_SESSION_TESTING)? array('disabled'=>'disabled'): array();?>
			<?php echo $form->dropDownList($model,'type', $model->typeOptions(), $typeDisabledArrs); ?>
			<?php echo $form->error($model,'type'); ?>
			<?php if($model->type!=Session::TYPE_SESSION_TESTING && $model->status<=Session::STATUS_WORKING):?>
			<div class="fR"><a class="fs12 errorMessage" href="javascript: changeTypeSession();">Thay đổi kiểu buổi học</a></div>
			<?php endif;?>
		</div>
	</div>
	<div id="displayWhiteboard" class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'whiteboard'); ?>
		</div>
		<div class="col col-lg-9" id="whiteboard<?php echo $model->id;?>">
			<?php if($model->whiteboard && $model->status<=Session::STATUS_WORKING):?>
				<span><?php echo Yii::app()->board->generateUrl($model->whiteboard); ?></span>
				<a href="javascript:removeWhiteboard('<?php echo $model->id;?>', '<?php echo $model->whiteboard;?>')" class="fR pR5 clrRed">Xóa lớp ảo </a>
			<?php else:?>
				<!--<a href="javascript: createBoard('<?php echo $model->id;?>', 0, 1, 0, 2);">Lớp nhỏ P2P Bình thường</a> hoặc-->
				<a href="javascript: createBoard('<?php echo $model->id;?>', 0, 0, 0, 2);">Lớp nhỏ Server nhỏ Bình thường</a> hoặc
				
				<a href="javascript: createBoard('<?php echo $model->id;?>', 0, 0, 1, 2);">Lớp nhỏ Server lớn Bình thường</a> hoặc
				<a href="javascript: createBoard('<?php echo $model->id;?>', 1, 0, 1, 2);">Lớp lớn Bình thường</a>
				
                                <br>
                                <!--<a href="javascript: createBoard('<?php echo $model->id;?>', 0, 1, 0, 1);">Lớp nhỏ P2P Đặc biệt</a> hoặc-->
				<a href="javascript: createBoard('<?php echo $model->id;?>', 0, 0, 0, 1);">Lớp nhỏ Server nhỏ Đặc biệt</a> 
				<!--hoặc
				
				<a href="javascript: createBoard('<?php echo $model->id;?>', 0, 0, 1, 1);">Lớp nhỏ Server lớn Đặc biệt</a> hoặc
				<a href="javascript: createBoard('<?php echo $model->id;?>', 1, 0, 1, 1);">Lớp lớn Đặc biệt</a>
				-->
			<?php endif;?>
                                
		</div>
	</div>
	<?php $endedDisplayCss = (!$model->isNewRecord && $model->status==Session::STATUS_ENDED)? "dpn":"";?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="Course_class">Giáo viên đã được gán</label>
		</div>
		<div class="col col-lg-9">
			<select id="Session_teacher_id" name="Session[teacher_id]" <?php echo ($model->teacher_id>0)? "disabled='disabled'": "";?>>
				<option value=''>Chọn giáo viên...</option>
				<?php foreach($availableTeachers as $teacher):?>
					<?php $priorityLabel = isset($priorityTeachers[$teacher->id])? " - Ưu tiên ".$priorityTeachers[$teacher->id]: "";?>
					<option value='<?php echo $teacher->id?>' <?php echo ($teacher->id==$model->teacher_id)? "selected='selected'": "";?>>
					<?php echo $teacher->fullName().' ('.$teacher->email.')'.$priorityLabel;?>
					</option>
				<?php endforeach;?>
			</select>
			<?php if($model->teacher_id>0):?>
			<div class="fR"><a class="fs12 errorMessage <?php echo $endedDisplayCss;?>" href="javascript: enableTeacher();">Thay đổi giáo viên cho buổi học</a></div>
			<?php endif;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="Course_class">Học sinh đã được gán</label>
		</div>
		<div class="col col-lg-9">
			<div class="class-subjects">
				<?php
					foreach($availableStudents as $student):
						$className = Student::model()->displayClass($student->id);
						if($className==null) $className = 'Lớp chưa xác định';
				?>
					<div class="assignStudents">
						<span class="fL w10">&nbsp;</span><input type="checkbox" checked="checked" disabled="disabled"
						 class="fL mL10" name="assignStudents[]" value="<?php echo $student->id?>"/>
						<span class="fL">&nbsp;<?php echo $student->lastname.' '.$student->firstname.' ('.$student->email.') - '.$className;?></span>
						<?php if($model->status!=Session::STATUS_ENDED):?>
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
            <?php $this->renderPartial("/course/widget/ajaxAddUser",array("ajaxSearchUser"=>$ajaxSearchUser)); ?>
            <label class="hint">Gán thêm các học sinh có nhu cầu tham gia vào buổi học này!</label>
		</div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'content'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'content',array('rows'=>6, 'cols'=>50, 'style'=>'height:6em')); ?>
			<?php echo $form->error($model,'content'); ?>
		</div>
	</div>
	<?php $disabledAttrs = (!$model->isNewRecord && in_array($model->status, array(Session::STATUS_ENDED, Session::STATUS_CANCELED)))? array('disabled'=>'disabled'):array();?>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Thời lượng (phút)</label></div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'plan_duration', $model->planDurationOptions(), $disabledAttrs); ?>
			<?php echo $form->error($model,'plan_duration'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Ngày bắt đầu</label>
		</div>
		<div class="col col-lg-9">
			<div class="w250 fL">
				<?php if(!isset($model->plan_start)) $model->plan_start = date('Y-m-d H:i:s');?>
				<?php $planStart = ($model->plan_start)? date('Y-m-d', strtotime($model->plan_start)): date('Y-m-d');?>
				<?php echo $form->textField($model,'plan_start', array_merge($disabledAttrs, array('class'=>'datepicker', 'style'=>'width:200px;', 'value'=>$planStart, 'readonly'=>'readonly'))); ?>
			</div>
			<div class="w120 fL">
				<span class="fL mT5"><b>Giờ:</b>&nbsp;</span>
				<?php echo CHtml::dropDownList('startHour', date('H', strtotime($model->plan_start)), $hoursInDayArrs, array_merge($disabledAttrs, array('style'=>'width:70px;')));?>
			</div>
			<div class="w120 fL">
	            <span class="fL mT5"><b>Phút:</b>&nbsp;</span>
	            <?php echo CHtml::dropDownList('startMin', date('i', strtotime($model->plan_start)), $minutesInHourArrs, array_merge($disabledAttrs, array('style'=>'width:70px;')));?>
			</div>
			<?php echo $form->error($model,'plan_start'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Tùy chọn</label>
		</div>
		<div class="col col-lg-9">
			<div class="w150 fL">
				<b>Ghi âm buổi học:</b>&nbsp;</span>
				<?php
					$htmlOptions = array();
					if (!$model->isNewRecord && in_array($model->status, array(Session::STATUS_ENDED, Session::STATUS_CANCELED)))
						$htmlOptions['disabled'] = 'disabled';
					if ($model->record == true)
						$htmlOptions['checked'] = 'checked';
					$htmlOptions['value'] = 1;
					// $checked = $model->record == true ? array('checked'=>'checked') : array();
					echo $form->checkBox($model, 'record', $htmlOptions);
				?>
			</div>
		</div>
	</div>
	<?php if(!$model->isNewRecord):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Trạng thái buổi học</label>
		</div>
		<div class="col col-lg-9">
			<?php $statusOptions = Session::statusOptions();?>
			<?php echo $form->dropDownList($model,'status', $statusOptions, $disabledAttrs); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>
		<?php if($model->status==Session::STATUS_CANCELED):?>
		<div class="form-element-container row">
			<div class="col col-lg-3">
				<label>Lý do hoãn/hủy buổi học</label>
			</div>
			<div class="col col-lg-9">
				<?php echo $form->textField($model,'status_note',array('size'=>60,'maxlength'=>256)); ?>
				<?php echo $form->error($model,'status_note'); ?>
			</div>
		</div>
		<?php endif;?>
	<?php endif;?>
	<?php if(!$model->isNewRecord && $model->status==Session::STATUS_PENDING && $model->plan_start>date('Y-m-d H:i:s') && $model->type!=2):?>
	<div class="fR pR15 dpn" id="divModifyLink">
		<a class="fs12 errorMessage" href="javascript: allowChangeSchedule();">Cho phép thay đổi lịch học của cả khóa học?</a>
	</div>
	<div id="divModifySchedule" class="form-element-container row" style="display:none;">
		<div class="col col-lg-3">
			<label class="errorMessage">Thay đổi lịch học cho cả khóa học</label><br/>
			<label class="errorMessage">Áp dụng cho tất cả các buổi học</label>
		</div>
		<div class="col col-lg-9">
			<label>Hoãn/lùi lịch 1 buổi:&nbsp;=></label><a class="errorMessage " href="javascript: modifySchedule(<?php echo $modelCourse->id;?>,1);" con="">Dời 1 buổi học về tương lai</a>
			<label class="pL20">Đẩy lịch sớm hơn 1 buổi:&nbsp;=></label><a class="errorMessage " href="javascript: modifySchedule(<?php echo $modelCourse->id;?>,-1);" con="">Dời 1 buổi học về gần thời điểm hiện tại hơn</a><br/>
			<label>Chú ý: Thay đổi lịch học sẽ áp dụng cho tất cả các buổi học khác chưa diễn ra trong khóa học này!</label>
		</div>
	</div>
	<?php endif;?>
	<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->