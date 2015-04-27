<?php
/* @var $this SubjectController */
/* @var $model Subject */

$this->breadcrumbs=array(
	'Subjects'=>array('index'),
	'Create',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'preCourse'=>$preCourse)); ?>