</script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Chi tiết</h2>
    </div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'name'=>'teacher_id',
			'value'=>User::getLink($model->teacher_id),
			'type'=>'raw',
		),
		'points',
		'points_to_be_fined',
		'notes',
		array(
			'name'=>'created_date',
			'value'=>date("d-m-Y", strtotime($model->created_date)),
		),
	),
)); ?>
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<a href="<?php echo Yii::app()->baseUrl.'/admin/teacherFine/fineRecords/';?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>