<?php
/* @var $this DailyRecordController */
/* @var $model TeachingDay */
?>
<?php
	$params = array('model'=>$model);
	if (isset($payment)){
		$params['payment'] = $payment;
	}
	if (isset($error)){
		$params['error'] = $error;
	}
	$this->renderPartial('admin.views.teacherPayment.dailyRecord._form', $params);
?>
