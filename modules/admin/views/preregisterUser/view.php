<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */

$this->breadcrumbs=array(
	'Preregister Users'=>array('index'),
	$model->id,
);
?>
<h2 class="mT10">Chi tiết đăng ký tư vấn</h2>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'fullname',
		'email',
		'birthday',
		'gender',
		'phone',
		array(
		   'name'=>'weekday',
		   'value'=>$model->getWeekdays(),
		),
		'timerange',
		'promotion_code',
		'sale_status',
		array(
		   'name'=>'sale_note',
		   'value'=>$model->sale_note,
		   'type'=>'raw',
		),
		array(
		   'name'=>'sale_user_id',
		   'value'=>($model->sale_user_id)? User::model()->displayUserById($model->sale_user_id):"",
		),
		array(
		   'name'=>'last_sale_date',
		   'value'=>($model->last_sale_date)? date('d/m/Y', strtotime($model->last_sale_date)):"",
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
	),
)); ?>
