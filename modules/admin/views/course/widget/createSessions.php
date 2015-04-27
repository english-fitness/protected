<!-- Begin Partial Widget: Create Session for Course-->
<?php 
	$registration = new ClsRegistration();//Init register session
	$session = Yii::app()->request->getPost('Session', array());//Get session
?>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Tổng số buổi học/khóa&nbsp;<span class="required">*</span></label>
	</div>
	<div class="col col-lg-9">
		<?php $suggestedNumber = isset($preCourse)? $preCourse->totalOfSession: 3;?>
		<?php echo CHtml::textField('Session[numberOfSession]', $suggestedNumber, array('id'=>'numberOfSession','maxlength'=>'3'));?>
		<?php if(isset($preCourse)):?>
			<label class="hint">Gợi ý từ đơn xin học: <?php echo $preCourse->total_of_session;?> buổi</label>
		<?php endif;?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Thời lượng 1 buổi học (phút)</label>
	</div>
	<div class="col col-lg-9">
		<?php $durationSelected = isset($session['plan_duration'])? $session['plan_duration']: Session::DEFAULT_DURATION;?>		
		<?php echo CHtml::dropDownList('Session[plan_duration]', $durationSelected, Session::model()->planDurationOptions(), array('id'=>'sessionPlanDuration'));?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Ngày bắt đầu khóa học&nbsp;<span class="required">*</span></label>
	</div>
	<div class="col col-lg-9">
		<?php
			$suggestedStartDate = isset($preCourse)? date('Y-m-d', strtotime($preCourse->start_date)): date('Y-m-d');
			$startDate = isset($session['startDate'])? $session['startDate']: $suggestedStartDate;
		?>
		<input type="text" class="datepicker" name="Session[startDate]" id="startDate" value="<?php echo $startDate;?>">
		<label class="hint"><?php if(isset($preCourse)) echo "Gợi ý từ đơn xin học: ".date('Y-m-d', strtotime($preCourse->start_date));?> (Định dạng yyyy-mm-dd & phải > hoặc = ngày hiện tại)</label>
	</div>
</div>
<?php $suggestSchedules = (isset($suggestSchedules) && count($session)==0)? $suggestSchedules: $session; ?>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Số buổi học/1 tuần&nbsp;<span class="required">*</span></label>
	</div>
	<div class="col col-lg-9">
		<?php
			$numberSelected = isset($session['numberSessionPerWeek'])? $session['numberSessionPerWeek']: "";
			if($numberSelected=="" && isset($suggestSchedules['dayOfWeek'])){
				$numberSelected = count($suggestSchedules['dayOfWeek']);
			}
		?>
        <?php echo CHtml::dropDownList('Session[numberSessionPerWeek]', $numberSelected, $registration->numberSessionsPerWeek(7), array('id'=>'numberSessionPerWeek', 'onchange'=>'generateSchedule();'));?>
        <?php if(isset($preCourse)):?>
			<label class="hint">Gợi ý từ đơn xin học: <?php echo ClsAdminHtml::displaySessionPerWeek($preCourse->session_per_week);?></label>
		<?php endif;?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label class="mT10">Kế hoạch chi tiết trong tuần</label>
	</div>
	<div class="col col-lg-9 pL0i" id="selectedSchedule">
		<?php			
			if(isset($suggestSchedules['dayOfWeek'])):
				foreach($suggestSchedules['dayOfWeek'] as $key=>$dayOfWeek):
		?>
			<div class="col col-lg-12 date_register">
				<div class="col col-lg-4 pL0i">
					<span class="fL mT10"><b>Buổi <?php echo ($key+1);?>:&nbsp;</b></span>
					<?php echo CHtml::dropDownList('Session[dayOfWeek][]', $dayOfWeek, $registration->daysOfWeek(), array('class'=>'w100'));?>
				</div>
				<div class="col col-lg-4"><span class="fL mT10"><b>Giờ:&nbsp;</b></span>
					<?php echo CHtml::dropDownList('Session[startHour][]', $suggestSchedules['startHour'][$key], $registration->hoursInDay(), array('class'=>'w100'));?>
				</div>
				<div class="col col-lg-4"><span class="fL mT10"><b>Phút:&nbsp;</b></span>
					<?php echo CHtml::dropDownList('Session[startMin][]', $suggestSchedules['startMin'][$key], $registration->minutesInHour(), array('class'=>'w100'));?>
				</div>
			</div>
		<?php endforeach;
		endif;?>
	</div>
</div>
<!-- End Partial Widget -->