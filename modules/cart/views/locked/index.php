<?php
/* @var $this LogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tài khoản bị khóa',
);
?>

<h1 class="page-header">Tài khoản bị khóa</h1>

<div class="table-responsive">
    <?php $this->widget('zii.widgets.grid.CGridView', array(
        'itemsCssClass'=>'table table-striped table-bordered table-hover',
        'id'=>'dataTables-example',
        'dataProvider'=>$model->search(),
        'filter'=>$model,
        'columns'=>array(
            'user_id',

            array(
                'header'=>'Email',
                'value'=>'$data->user->email',
                'type' => 'raw',
            ),

            array(
                'header'=>'Họ tên',
                'value'=>'$data->user->fullName()',
                'type' => 'raw',
            ),
            array(
                'header'=>'Địa chỉ',
                'value'=>'$data->user->address',
                'type' => 'raw',
            ),
            array(
                'class'=>'CButtonColumn',
                'htmlOptions'=>array('style'=>'width:8%'),
                'buttons'=>array(
                    'update'=>array(
                        'visible'=>'false',
                    ),
                    'view'=>array(
                        'visible'=>'false',
                    )
                ),
            ),
        ),
    )); ?>
</div>