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
		'address',
		'phone',
		'class_name',
		'parent_name',
		'parent_phone',
		array(
		   'name'=>'subject_note',
		   'value'=>$model->subject_note,
		   'type'=>'raw',
		),
		array(
		   'name'=>'objective',
		   'value'=>$model->objective,
		   'type'=>'raw',
		),
		array(
		   'name'=>'content_request',
		   'value'=>$model->content_request,
		   'type'=>'raw',
		),
		array(
		   'name'=>'teacher_request',
		   'value'=>$model->teacher_request,
		   'type'=>'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>$model->statusOptions($model->status),
		),
		array(
		   'name'=>'user_type',
		   'value'=>$model->userTypeOptions($model->user_type),
		),
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
		   'name'=>'refer_user_id',
		   'value'=>($model->refer_user_id)? User::model()->displayUserById($model->refer_user_id):"",
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
