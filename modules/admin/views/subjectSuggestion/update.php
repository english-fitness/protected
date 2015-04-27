<?php
/* @var $this SubjectSuggestionController */
/* @var $model SubjectSuggestion */

$this->breadcrumbs=array(
	'Subject Suggestions'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>