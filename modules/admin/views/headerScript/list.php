<?php
/* @var $this TeacherController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Quản lý cầu hình đầu trang</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/headerScript/add">
			<i class="icon-plus"></i>Thêm cấu hình đầu trang
			</a>
        </div>
    </div>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(

		array(
		   'name'=>'id',
		   'value'=>'$data->id',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		),
		array(
				'name'=>'condition',
				'value'=>'Yii::app()->request->hostInfo.$data->condition',
				'htmlOptions'=>array('style'=>'width:350px; text-align:center;'),
		),
		array(
				'name'=>'value',
				'value'=>'$data->value',
				'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
				'class'=>'CButtonColumn'

		),
	),
)); ?>
