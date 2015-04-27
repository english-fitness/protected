<?php
/* @var $this CourseCommentController */
/* @var $model CourseComment */

$this->breadcrumbs=array(
	'Course Comments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CourseComment', 'url'=>array('index')),
	array('label'=>'Create CourseComment', 'url'=>array('create')),
	array('label'=>'Update CourseComment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CourseComment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CourseComment', 'url'=>array('admin')),
);
?>

<h1>View CourseComment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'course_id',
		'user_id',
		'comment',
		'created_date',
		'modified_date',
	),
)); ?>
