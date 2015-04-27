<?php
/* @var $this CartController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lịch sử',
);

$this->menu=array(
    array('label'=>'<i class="glyphicon glyphicon-plus-sign"></i> Tạo thẻ', 'url'=>array('create')),
    array('label'=>'<i class="glyphicon glyphicon-list-alt"></i> Quản lý thẻ', 'url'=>array('admin')),
);
?>

<h1 class="page-header">Danh sách thẻ</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
