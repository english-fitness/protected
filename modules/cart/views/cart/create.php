<?php
/* @var $this CartController */
/* @var $model Cart */

$this->breadcrumbs=array(
	'Carts'=>array('index'),
	'Create',
);

$this->menu=array(
    array('label'=>'<i class="glyphicon glyphicon-th-list"></i> Danh sách thẻ', 'url'=>array('index')),
    array('label'=>'<i class="glyphicon glyphicon-list-alt"></i> Quản lý thẻ', 'url'=>array('admin')),
);
?>

<h1 class="page-header">Tạo mã thẻ</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>