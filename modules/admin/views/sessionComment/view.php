<?php
/* @var $this SessionCommentController */
/* @var $model SessionComment */

$this->breadcrumbs=array(
	'Session Comments'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List SessionComment', 'url'=>array('index')),
	array('label'=>'Create SessionComment', 'url'=>array('create')),
	array('label'=>'Update SessionComment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SessionComment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SessionComment', 'url'=>array('admin')),
);
?>

<h1>View SessionComment #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'session_id',
		'user_id',
		'comment',
		'created_date',
		'modified_date',
	),
)); ?>
