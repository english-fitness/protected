<!-- Begin Partial Widget: Generate suggest day & hour -->
<?php $registration = new ClsRegistration();?>
<?php if(count($suggestedDays)>0):
	foreach($suggestedDays as $key=>$suggestedDay):?>
	<div class="date_register row ">
		<b>Buổi&nbsp;<?php echo ($key+1);?>:&nbsp;</b>
		<?php echo CHtml::dropDownList('Session[dayOfWeek][]', $suggestedDay, $registration->daysOfWeek(), array('style'=>'width:100px;'));?>
		<b>Giờ:&nbsp;</b>
		<?php echo CHtml::dropDownList('Session[startHour][]', "19", $registration->hoursInDay(), array('style'=>'width:70px;'));?>
		<b>Phút:&nbsp;</b>
		<?php echo CHtml::dropDownList('Session[startMin][]', "30", $registration->minutesInHour(), array('style'=>'width:70px;'));?>
		<b>Thời lượng: &nbsp;&nbsp;90 phút</b>
	</div>
<?php endforeach;
	endif;?>
<!-- End Partial Widget -->