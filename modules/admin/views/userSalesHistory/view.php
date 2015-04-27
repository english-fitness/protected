<?php
/* @var $this UserSalesController */
/* @var $model UserSalesHistory */

$this->breadcrumbs=array(
	'User Sales Histories'=>array('index'),
	$model->id,
);
?>
<h2 class="mT10">Chi tiết lịch sử chăm sóc, tư vấn</h2>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
		   'name'=>'created_user_id',
		   'value'=>($model->user_id)? User::model()->displayUserById($model->user_id):"",
		),
		array(
		   'name'=>'sale_date',
		   'value'=>($model->sale_date)? date('d/m/Y H:i', strtotime($model->sale_date)):"",
		),
		array(
		   'name'=>'next_sale_date',
		   'value'=>($model->next_sale_date)? date('d/m/Y', strtotime($model->next_sale_date)):"",
		),
		'sale_note',
		'sale_status',
		array(
		   'name'=>'sale_question',
		   'value'=>$model->sale_question,
		   'type'=>'raw',
		),
		array(
		   'name'=>'user_answer',
		   'value'=>$model->user_answer,
		   'type'=>'raw',
		),
		array(
		   'name'=>'created_user_id',
		   'value'=>($model->created_user_id)? User::model()->displayUserById($model->created_user_id):"",
		),
		array(
		   'name'=>'created_date',
		   'value'=>($model->created_date)? date('d/m/Y H:i', strtotime($model->created_date)):"",
		),
		array(
		   'name'=>'modified_user_id',
		   'value'=>($model->modified_user_id)? User::model()->displayUserById($model->modified_user_id):"",
		),
		array(
		   'name'=>'modified_date',
		   'value'=>($model->modified_date)? date('d/m/Y H:i', strtotime($model->modified_date)):"",
		),
		'deleted_flag',
	),
)); ?>
