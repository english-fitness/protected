<?php
/* @var $this PermissionController */
/* @var $model Permission */

$this->breadcrumbs=array(
	'Permissions'=>array('index'),
	$model->title,
);
?>

<h1>Chi tiết quyền truy cập</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'controller',
		'action',
		'description',
	),
)); ?>
