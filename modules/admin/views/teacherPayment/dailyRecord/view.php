</script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Thống kê buổi học ngày <?php echo date('d-m-Y', strtotime($model->day));?></h2>
    </div>
</div>
<?php
	$payment = TeacherPayment::model()->findByPk($model->payment_id);
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'name'=>'day',
			'value'=>date("d-m-Y", strtotime($model->day)),
		),
		array(
			'name'=>'teacher_id',
			'value'=>$payment->getTeacherLink(),
			'type'=>'raw',
		),
		'platform_session',
		'non_platform_session',
		array(
			'name'=>'Tổng số buổi học',
			'value'=>$model->platform_session + $model->non_platform_session,
		),
		'note',
		array(
			'name'=>'created_date',
			'value'=>($model->created_date != null) ? date("d-m-Y H:i:s", strtotime($model->created_date)) : "",
		),
		array(
			'name'=>'created_user_id',
			'value'=>User::model()->displayUserById($model->created_user_id),
		),
		array(
			'name'=>'last_modified_date',
			'value'=>($model->last_modified_date != null) ? date("d-m-Y H:i:s", strtotime($model->last_modified_date)) : "",
		),
		array(
			'name'=>'last_modified_user_id',
			'value'=>User::model()->displayUserById($model->last_modified_user_id),
		),
	),
)); ?>
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<a href="<?php echo Yii::app()->baseUrl.'/admin/TeacherPayment/update/id/'.$model->payment_id; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách trong tháng</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>