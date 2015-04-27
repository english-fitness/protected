<?php
/* @var $this NotificationController */
/* @var $model Notification */

$this->breadcrumbs=array(
	'Notifications'=>array('index'),
	'Create',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'errorMsg'=>$errorMsg)); ?>