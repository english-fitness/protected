<?php
/* @var $this UserSalesController */
/* @var $model UserSalesHistory */

$this->breadcrumbs=array(
	'User Sales Histories'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List UserSalesHistory', 'url'=>array('index')),
	array('label'=>'Create UserSalesHistory', 'url'=>array('create')),
	array('label'=>'Update UserSalesHistory', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete UserSalesHistory', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage UserSalesHistory', 'url'=>array('admin')),
);
?>

<h1>View UserSalesHistory #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'user_id',
		'preregister_user_id',
		'sale_date',
		'next_sale_date',
		'sale_note',
		'sale_status',
		'sale_question',
		'user_answer',
		'created_user_id',
		'modified_user_id',
		'deleted_flag',
		'created_date',
		'modified_date',
	),
)); ?>
