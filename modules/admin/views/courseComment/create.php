<?php
/* @var $this CourseCommentController */
/* @var $model CourseComment */

$this->breadcrumbs=array(
	'Course Comments'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CourseComment', 'url'=>array('index')),
	array('label'=>'Manage CourseComment', 'url'=>array('admin')),
);
?>

<?php $this->renderPartial('_form', array('model'=>$model, 'courses'=>$courses)); ?>