<?php
/* @var $this CourseCommentController */
/* @var $model CourseComment */

$this->breadcrumbs=array(
	'Course Comments'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CourseComment', 'url'=>array('index')),
	array('label'=>'Create CourseComment', 'url'=>array('create')),
	array('label'=>'View CourseComment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CourseComment', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'courses'=>$courses)); ?>