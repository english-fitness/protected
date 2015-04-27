<?php
/* @var $this CartController */
/* @var $model Cart */

$this->breadcrumbs=array(
	'Carts'=>array('index'),
	'Manage',
);

$this->menu=array(
    array('label'=>'<i class="glyphicon glyphicon-th-list"></i> Danh sách thẻ', 'url'=>array('index')),
    array('label'=>'<i class="glyphicon glyphicon-plus-sign"></i> Tạo thẻ', 'url'=>array('create')),
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

<h1 class="page-header">Quản lý thẻ</h1>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'itemsCssClass'=>'table table-striped table-bordered table-hover',
    'id'=>'list-view',
    'ajaxUpdate' => true,
    'dataProvider'=>$model->search(),
    'filter'=>$model,
    'columns'=>array(
        'cart_id',

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
            'value'=>'Yii::app()->format->formatNumber($data->cart_price)." VND"',
            'type' => 'raw',
        ),
        array(
            'name'=>'cart_status',
            'value'=>'$data->statusLabel',
            'type' => 'raw',
        )
    ),
)); ?>