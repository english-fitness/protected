<!-- Begin Partial Widget: Create Session for Course-->
<?php 
	$registration = new ClsRegistration();//Init register session
	$session = Yii::app()->request->getPost('Session', array());//Get session
?>
<div id="number_of_session" class="form-element-container row dpn">
	<div class="col col-lg-3">
		<label>Số buổi học</label>
	</div>
	<div class="col col-lg-9">
        <input type="text" name="Session[numberOfSession]" value="1" />
	</div>
</div>
<div id="start_date" class="form-element-container row dpn">
    <div class="col col-lg-3">
		<label>Ngày bắt đầu&nbsp;</label>
	</div>
	<div class="col col-lg-9">
		<input type="text" class="datepicker" name="Session[startDate]">
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