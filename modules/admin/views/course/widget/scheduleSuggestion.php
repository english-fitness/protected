<!-- Begin Partial Widget: Generate suggest day & hour -->
<?php $registration = new ClsRegistration();?>
<?php if(count($suggestedDays)>0):
	foreach($suggestedDays as $key=>$suggestedDay):?>
	<div class="col col-lg-12 date_register">
		<div class="col col-lg-4 pL0i">
			<span class="fL mT10"><b>Buổi <?php echo ($key+1);?>:&nbsp;</b></span>
			<?php echo CHtml::dropDownList('Session[dayOfWeek][]', $suggestedDay, $registration->daysOfWeek(), array('class'=>'w150'));?>
		</div>
		<div class="col col-lg-4"><span class="fL mT10"><b>Giờ:&nbsp;</b></span>
			<?php echo CHtml::dropDownList('Session[startHour][]', "19", $registration->hoursInDay(), array('class'=>'w100'));?>
		</div>
		<div class="col col-lg-4"><span class="fL mT10"><b>Phút:&nbsp;</b></span>
			<?php echo CHtml::dropDownList('Session[startMin][]', "30", $registration->minutesInHour(), array('class'=>'w100'));?>
		</div>
	</div>
<?php endforeach;
	endif;?>
<!-- End Partial Widget -->