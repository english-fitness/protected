<?php
/* @var $this UserSalesController */
/* @var $model UserSalesHistory */

$this->breadcrumbs=array(
	'User Sales Histories'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserSalesHistory', 'url'=>array('index')),
	array('label'=>'Manage UserSalesHistory', 'url'=>array('admin')),
);
?>

<h1>Create UserSalesHistory</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>