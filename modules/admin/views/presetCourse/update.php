<?php
/* @var $this PresetCourseController */
/* @var $model PresetCourse */

$this->breadcrumbs=array(
	'Preset Courses'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>
<?php $this->renderPartial('_form', array('model'=>$model, 'subjects'=>$subjects, 'availableTeachers'=>$availableTeachers)); ?>