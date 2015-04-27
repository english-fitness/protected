<?php
/* @var $this UserSalesController */
/* @var $model UserSalesHistory */

$this->breadcrumbs=array(
	'User Sales Histories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List UserSalesHistory', 'url'=>array('index')),
	array('label'=>'Create UserSalesHistory', 'url'=>array('create')),
	array('label'=>'View UserSalesHistory', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage UserSalesHistory', 'url'=>array('admin')),
);
?>

<h1>Update UserSalesHistory <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>