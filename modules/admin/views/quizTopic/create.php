<?php
/* @var $this QuizTopicController */
/* @var $model QuizTopic */

$this->breadcrumbs=array(
	'Quiz Topics'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List QuizTopic', 'url'=>array('index')),
	array('label'=>'Manage QuizTopic', 'url'=>array('admin')),
);
?>
<?php $this->renderPartial('_form', array('model'=>$model, 'subjects'=>$subjects)); ?>