<?php
/* @var $this UserSalesHistoryController */
/* @var $model UserSalesHistory */

$this->breadcrumbs=array(
	'User Sales Histories'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>