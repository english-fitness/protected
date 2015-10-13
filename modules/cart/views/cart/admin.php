<?php
/* @var $this CartController */
/* @var $model Cart */

$this->breadcrumbs=array(
	'Carts'=>array('index'),
	'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#cart-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<div class="page-header"><h1>Quản lý thẻ</h1><a href="/cart/cart/export" style="float:right;margin-top:-15px">Lấy danh sách thẻ</a></div>


<?php $this->widget('zii.widgets.grid.CGridView', array(
    'itemsCssClass'=>'table table-striped table-bordered table-hover',
    'id'=>'list-view',
    'ajaxUpdate' => true,
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        array(
            'header'=>'ID',
            'name'=>'cart_id',
            'value'=>'$data->cart_id',
        ),
        array(
            'name'=>'cart_type',
            'value'=>'$data->typeLabel',
            'type' => 'raw',
        ),
        array(
            'name'=>'cart_code',
            'value'=>'Common::formatCartCode($data->cart_code)',
            'type' => 'raw',
        ),
        array(
            'name'=>'cart_price',
            'value'=>'Yii::app()->format->formatNumber($data->cart_price)." Buổi"',
            'type' => 'raw',
        ),
        array(
            'name'=>'cart_status',
            'value'=>'$data->statusLabel',
            'type' => 'raw',
        )
    ),
)); ?>