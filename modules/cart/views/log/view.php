<?php
/* @var $this LogController */
/* @var $model CartLog */

$this->breadcrumbs=array(
	'Lịch sử'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CartLog', 'url'=>array('index')),
	array('label'=>'Create CartLog', 'url'=>array('create')),
	array('label'=>'Update CartLog', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CartLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CartLog', 'url'=>array('admin')),
);
?>

<h1 class="page-header">View CartLog #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'type',
		'user_id',
		'cart_id',
		'log_value',
		'create_time',
		'ip_address',
	),
)); ?>
