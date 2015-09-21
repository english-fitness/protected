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
        <h2 class="page-title mT10">Quản lý giá gói: <?php echo $model->package->title; ?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/<?php echo Yii::app()->controller->id; ?>/add/id/<?php echo $model->package->id;  ?>">
                <i class="icon-plus"></i>Thêm cấu hình giá
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
            'name'=>'package_id',
            'value'=>'$data->package->title',
            'htmlOptions'=>array('style'=>'width:120px; text-align:center;'),
        ),
        array(
            'name'=>'tuition',
            'value'=>'Yii::app()->format->formatNumber($data->tuition)." VND"',
            'htmlOptions'=>array('style'=>'width:350px; text-align:center;'),
        ),
		array(
            'class'=>'CButtonColumn',
            'buttons'=>array (
                'edit'=> array('label'=>'', 'imageUrl'=>'',
                        'options'=>array( 'class'=>'btn-edit mL15' ),
                ),
                'delete'=>array(
                    'visible'=>'true',
                ),
                'view'=>array(
                    'visible'=>'false',
                ),
            ),
		),
	),
)); ?>
