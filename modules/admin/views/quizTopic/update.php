<?php
/* @var $this QuizTopicController */
/* @var $model QuizTopic */

$this->breadcrumbs=array(
	'Quiz Topics'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List QuizTopic', 'url'=>array('index')),
	array('label'=>'Create QuizTopic', 'url'=>array('create')),
	array('label'=>'View QuizTopic', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage QuizTopic', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'subjects'=>$subjects)); ?>