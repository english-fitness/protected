<?php
/* @var $this QuizItemController */
/* @var $model QuizItem */

$this->breadcrumbs=array(
	'Quiz Items'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List QuizItem', 'url'=>array('index')),
	array('label'=>'Manage QuizItem', 'url'=>array('admin')),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model, 'subjects'=>$subjects, 'writingExams'=>$writingExams)); ?>