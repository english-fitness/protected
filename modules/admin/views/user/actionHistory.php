<?php
/* @var $this UserActionHistoryController */
/* @var $model UserActionHistory */

$this->breadcrumbs=array(
	'User Action Histories'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Lịch sử các hoạt động</h2>
    </div>
</div>
<?php $createdDateFilter = Yii::app()->controller->getQuery('UserActionHistory[created_date]', '');?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->with(array(
		'user'=>array(
			'select'=>array('id', 'firstname', 'lastname')
		)
	))->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'name'=>'table_name',
		   'value'=>'$data->table_name',
		   'htmlOptions'=>array('style'=>'width:200px;'),
		),
		array(
		   'name'=>'controller',
		   'value'=>'$data->controller',
		   'htmlOptions'=>array('style'=>'width:200px;'),
		),
		array(
		   'name'=>'action',
		   'value'=>'$data->displayAction()',
		   'htmlOptions'=>array('style'=>'width:220px;'),
		   'type' => 'raw',
		),
		array(
		   'name'=>'primary_key',
		   'value'=>'$data->primary_key',
		   'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
		),
		array(
		   'name'=>'description',
		   'value'=>'$data->description',
		   'type' => 'raw',
		),
		array(
		   'name'=>'created_date',
		   'value'=>'date("d/m/Y, H:i", strtotime($data->created_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="UserActionHistory[created_date]">',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name'=>'user_id',
		   'value'=>'($data->user_id)? $data->user->getViewLink() :""',
		   'type'=>'raw', 'htmlOptions'=>array('style'=>'width:150px;'),
		),
	),
)); ?>
