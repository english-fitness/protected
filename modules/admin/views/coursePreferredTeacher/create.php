<?php
/* @var $this CoursePreferredTeacherController */
/* @var $model CoursePreferredTeacher */

$this->breadcrumbs=array(
	'Course Preferred Teachers'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CoursePreferredTeacher', 'url'=>array('index')),
	array('label'=>'Manage CoursePreferredTeacher', 'url'=>array('admin')),
);
?>

<h1>Create CoursePreferredTeacher</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>