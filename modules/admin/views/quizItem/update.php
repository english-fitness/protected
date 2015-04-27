<?php
/* @var $this QuizItemController */
/* @var $model QuizItem */

$this->breadcrumbs=array(
	'Quiz Items'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List QuizItem', 'url'=>array('index')),
	array('label'=>'Create QuizItem', 'url'=>array('create')),
	array('label'=>'View QuizItem', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage QuizItem', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'subjects'=>$subjects,
 'writingExams'=>$writingExams,'assignedQuizExams'=>$assignedQuizExams,)); ?>