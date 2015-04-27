<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'modelCourse'=>$modelCourse,
	'availableTeachers'=>$availableTeachers, 'availableStudents'=>$availableStudents,)); ?>