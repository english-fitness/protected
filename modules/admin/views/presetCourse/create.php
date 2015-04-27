<?php
/* @var $this PresetCourseController */
/* @var $model PresetCourse */

$this->breadcrumbs=array(
	'Preset Courses'=>array('index'),
	'Create',
);
?>
<?php $this->renderPartial('_form', array('model'=>$model, 'subjects'=>$subjects, 'availableTeachers'=>$availableTeachers)); ?>