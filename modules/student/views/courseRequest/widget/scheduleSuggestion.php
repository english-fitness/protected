<!-- Begin Partial Widget: Generate suggest day & hour -->
<?php $registration = new ClsRegistration();?>
<?php if(count($suggestedDays)>0):
	foreach($suggestedDays as $key=>$suggestedDay):?>
	<div class="date_register row">
		<div class="col-xs-5">
			<label  class="col-sm-3 control-label">Buổi&nbsp;<?php echo ($key+1);?>: &nbsp;</label>
			<div class="col-sm-9">
				<?php echo CHtml::dropDownList('Session[dayOfWeek][]', $suggestedDay, $registration->daysOfWeek(), array('class'=>'form-control'));?>
			</div>
		</div>
		<div class="col-xs-7">
			<label  class="col-sm-4 control-label">Khung giờ:</label>
			<div class="col-sm-8">
				<?php echo CHtml::dropDownList('Session[startHour][]', "", $registration->timeFrames(), array('class'=>'form-control'));?>
			</div>
		</div>
	</div>
<?php endforeach;?>	
<?php else:?>
<p>Hãy chọn số buổi/tuần và đặt lịch học chi tiết!</p>
<?php endif;?>
<label class="hint">Lưu ý: Lịch học chi tiết trong tuần không được trùng cả ngày và khung giờ học</label>
<!-- End Partial Widget -->
