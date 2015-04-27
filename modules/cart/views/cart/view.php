<?php
/* @var $this CartController */
/* @var $model Cart */

$this->breadcrumbs=array(
	'Carts'=>array('index'),
	$model->cart_id,
);
$this->menu=array(
    array('label'=>'<i class="glyphicon glyphicon-th-list"></i> Danh sách thẻ', 'url'=>array('index')),
    array('label'=>'<i class="glyphicon glyphicon-plus-sign"></i> Tạo thẻ', 'url'=>array('create')),
    array('label'=>'<i class="glyphicon glyphicon-eye-open"></i> Xem', 'url'=>array('view', 'id'=>$model->cart_id)),
    array('label'=>'<i class="glyphicon glyphicon-remove-circle"></i> Delete Cart', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cart_id),'confirm'=>'Are you sure you want to delete this item?')),
    array('label'=>'<i class="glyphicon glyphicon-list-alt"></i> Quản lý thẻ', 'url'=>array('admin')),
);
?>

<h1 class="page-header">View Cart #<?php echo $model->cart_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'cart_code',
		'cart_price',
	),
)); ?>
