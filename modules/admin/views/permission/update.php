<?php
/* @var $this PermissionController */
/* @var $model Permission */

$this->breadcrumbs=array(
	'Permissions'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>