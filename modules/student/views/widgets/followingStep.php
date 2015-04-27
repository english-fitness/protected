<?php $user = User::model()->findByPk(Yii::app()->user->id);
	if($user->status < User::STATUS_REGISTERED_COURSE && $user->role==User::ROLE_STUDENT):
		$currentStatus = $user->status;//Current step as user status
		if($currentStatus<User::STATUS_APPROVED) $currentStatus = User::STATUS_APPROVED;
?>
<div class="stepNotification fR pA10">
	<span class="fL"><b class="error">Các bước cần làm</b>&nbsp;</span><span class="hand-point-icon"></span>&nbsp;&nbsp;&nbsp;
	<?php $followingSteps = Student::model()->getMainFollowingSteps();
		if($currentStatus<User::STATUS_ENOUGH_PROFILE){
			unset($followingSteps[User::STATUS_REGISTERED_COURSE]);
		}
		foreach($followingSteps as $key=>$step):
			$stepStyle = 'style="color:gray; cursor:default;"';
			if($currentStatus>=($key-1)) $stepStyle = 'style="color:#325DA7;"';
				if($currentStatus<$key):
	?>
	<b><a href="<?php echo $step['link'];?>" <?php echo $stepStyle;?> class="fs12"><?php echo $step['title'];?></a></b>
		<?php if($key<User::STATUS_REGISTERED_COURSE) echo '&nbsp;>>&nbsp;';?>
	<?php endif; endforeach;?>
</div>
<?php endif;?>
<?php if($user->status == User::STATUS_REGISTERED_COURSE):?>
	<?php $countPreCourse = PreregisterCourse::model()->countByAttributes(array('student_id'=>$user->id, 'deleted_flag'=>0));
		if($countPreCourse>0):
	?>
	<div class="stepNotification fR pA10">
		<span class="fL"><b class="error">Các bước cần làm</b>&nbsp;</span><span class="hand-point-icon"></span>&nbsp;&nbsp;&nbsp;
		Bạn đã đăng ký học thành công, vui lòng chờ xếp lớp <a href="/student/courseRequest/list" style="color:#325DA7;">(xem khoá học đã đăng ký)</a>
	</div>	
	<?php endif;?>
<?php endif;?>