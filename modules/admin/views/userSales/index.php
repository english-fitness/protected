<?php
/* @var $this UserSalesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Sales Histories',
);

$this->menu=array(
	array('label'=>'Create UserSalesHistory', 'url'=>array('create')),
	array('label'=>'Manage UserSalesHistory', 'url'=>array('admin')),
);
?>

<h1>User Sales Histories</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
