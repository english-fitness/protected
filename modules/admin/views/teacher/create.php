<?php
/* @var $this TeacherController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'teacher'=>$teacher,
 'classSubjects' => $classSubjects, 'abilitySubjects' => $abilitySubjects));
?>