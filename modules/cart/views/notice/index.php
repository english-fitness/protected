<?php
/* @var $this NoticeController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Thông báo',
);
?>

<h1 class="page-header">Thông báo</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
    'summaryCssClass'=>'hidden',
)); ?>
