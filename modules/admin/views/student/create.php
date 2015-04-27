<?php
/* @var $this StudentController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'student'=>$student)); ?>