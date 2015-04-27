<?php
/* @var $this PermissionController */
/* @var $model Permission */

$this->breadcrumbs=array(
	'Permissions'=>array('index'),
	'Create',
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>