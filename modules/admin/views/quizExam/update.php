<?php
/* @var $this QuizExamController */
/* @var $model QuizExam */

$this->breadcrumbs=array(
	'Quiz Exams'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List QuizExam', 'url'=>array('index')),
	array('label'=>'Create QuizExam', 'url'=>array('create')),
	array('label'=>'View QuizExam', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage QuizExam', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model,'subjects'=>$subjects,)); ?>