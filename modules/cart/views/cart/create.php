<?php
/* @var $this CartController */
/* @var $model Cart */

$this->breadcrumbs=array(
	'Carts'=>array('index'),
	'Create',
);
?>

<h1 class="page-header">Tạo mã thẻ</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>