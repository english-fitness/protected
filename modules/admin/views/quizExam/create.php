<?php
/* @var $this QuizExamController */
/* @var $model QuizExam */

$this->breadcrumbs=array(
	'Quiz Exams'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List QuizExam', 'url'=>array('index')),
	array('label'=>'Manage QuizExam', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model,'subjects'=>$subjects,)); ?>