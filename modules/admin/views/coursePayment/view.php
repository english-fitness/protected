</script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Chi tiết học phí</h2>
    </div>
</div>
<?php
    function formatDate($format, $date){
        return $date != null ? date($format, strtotime($date)) : "Chưa có";
    }
    function displayCourseName($title, $id){
        return $title != "" ? $title : $id;
    }
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
			'name'=>'Khóa học',
			'value'=>'<a href="/admin/session?course_id='.$model->course_id.'">'.displayCourseName($model->course->title, $model->course_id).'</a>',
			'type'=>'raw',
		),
		array(
			'name'=>'Số buổi',
			'value'=>$model->sessions,
			'type'=>'raw',
		),
        array(
			'name'=>'Học phí',
			'value'=>number_format($model->tuition),
		),
        array(
            'name'=>'payment_date',
            'value'=>formatDate("d/m/Y", $model->payment_date),
        ),
		'note',
        array(
			'name'=>'created_user_id',
			'value'=>$model->createdUser->fullname(),
		),
		array(
			'name'=>'created_date',
			'value'=>date("d-m-Y H:i:s", strtotime($model->created_date)),
		),
        array(
			'name'=>'last_modified_user_id',
			'value'=>$model->modifiedUser->fullname(),
		),
		array(
			'name'=>'last_modified_date',
			'value'=>date("d-m-Y H:i:s", strtotime($model->last_modified_date)),
		),
	),
)); ?>
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
    <?php if(Yii::app()->request->urlReferrer != null):?>
        <a href="<?php echo Yii::app()->request->urlReferrer?>"><div class="btn-back mT2"></div>&nbsp;Quay lại trang trước</a>
    <?php else:?>
		<a href="<?php echo Yii::app()->baseUrl.'/admin/teacherFine/fineRecords/';?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách</a>
    <?php endif;?>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>