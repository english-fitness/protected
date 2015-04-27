<?php
/* @var $this CartController */
/* @var $model Cart */

$this->breadcrumbs=array(
	'Carts'=>array('index'),
	$model->cart_id=>array('view','id'=>$model->cart_id),
	'Update',
);

$this->menu=array(
	array('label'=>'<i class="glyphicon glyphicon-th-list"></i> Danh sách thẻ', 'url'=>array('index')),
	array('label'=>'<i class="glyphicon glyphicon-plus-sign"></i> Tạo thẻ', 'url'=>array('create')),
	array('label'=>'<i class="glyphicon glyphicon-eye-open"></i> Xem', 'url'=>array('view', 'id'=>$model->cart_id)),
	array('label'=>'<i class="glyphicon glyphicon-list-alt"></i> Quản lý thẻ', 'url'=>array('admin')),
);
?>

<h1 class="page-header">Update Cart <?php echo $model->cart_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>