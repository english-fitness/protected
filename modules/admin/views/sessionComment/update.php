<?php
/* @var $this SessionCommentController */
/* @var $model SessionComment */

$this->breadcrumbs=array(
	'Session Comments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List SessionComment', 'url'=>array('index')),
	array('label'=>'Create SessionComment', 'url'=>array('create')),
	array('label'=>'View SessionComment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage SessionComment', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'sessions'=>$sessions)); ?>