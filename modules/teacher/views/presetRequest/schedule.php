<!-- Begin Partial Widget: Generate suggest day & hour -->
<?php $registration = new ClsRegistration();?>
<?php if(count($suggestedDays)>0):
	foreach($suggestedDays as $key=>$suggestedDay):?>
	<div class="col col-lg-12 mT5" style='border:1px solid #CCCCCC;'>
		<b class="fL mT15">Day&nbsp;<?php echo ($key+1);?>:&nbsp;</b>
		<?php echo CHtml::dropDownList('Session[dayOfWeek][]', $suggestedDay, $registration->daysOfWeek(), array('class'=>'w150 fL fs13'));?>
		<b class="fL mL15 mT15">Time slot:&nbsp;</b>
		<?php echo CHtml::dropDownList('Session[startHour][]', "", $registration->timeFrames($planDuration), array('class'=>'w200 fL fs13'));?>
	</div>
<?php endforeach;?>	
<?php endif;?>
<label class="hint">Note: Schedule details are not identical in week days and hours learning framework</label>
<!-- End Partial Widget -->