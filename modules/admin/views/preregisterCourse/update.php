<?php
/* @var $this PreregisterCourseController */
/* @var $model PreregisterCourse */

$this->breadcrumbs=array(
	'Preregister Courses'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'mergeCourses'=>$mergeCourses)); ?>