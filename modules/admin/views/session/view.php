<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	$model->id,
);
?>
<script type="text/javascript">
	function cancelSession(sessionId){
		window.location = '/admin/session/cancel/id/'+sessionId;
	}
</script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Chi tiết buổi học</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
    	<?php if($model->status==Session::STATUS_APPROVED):?>
    		<button class="btn btn-default remove" name="form_action" type="button" onclick="cancelSession(<?php echo $model->id;?>);"><i class="icon-undo"></i>Báo hoãn/hủy buổi học</button>
    	<?php endif;?>
    </div>
</div>
<?php 
	$statusOptions = Session::statusOptions();
	$typeOptions = $model->typeOptions();
	$paymentStatuses = ClsCourse::paymentStatuses();
	$paymentTypes = ClsCourse::paymentTypes();
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
		   'name'=>'course_id',
		   'value'=>$model->course->title,
		),
		'subject',
		array(
		   'name'=>'Kiểu lớp học',
		   'value'=>"1-".$model->total_of_student.' <span class="clrOrange">('.count($model->assignedStudents()).' hs)</span>',
		   'type'  => 'raw',
		),
		array(
		   'name'=>'Giờ bắt đầu dự kiến',
		   'value'=>date('d/m/Y, H:i', strtotime($model->plan_start))." (Thời lượng ".$model->plan_duration." phút)",
		),
		array(
		   'name'=>'Giáo viên',
		   'value'=>$model->getTeacher("/admin/teacher/view/id"),
		   'type'  => 'raw',
		),
		array(
		   'name'=>'teacher_entered_time',
		   'value'=>($model->teacher_entered_time)? date('d/m/Y, H:i', strtotime($model->teacher_entered_time)):"",
		),
		array(
		   'name'=>'Học sinh',
		   'value'=>implode("<br/>", $model->getAttendedTimeOfStudents("/admin/student/view/id", true)),
		   'type'  => 'raw',
		),
		array(
		   'name'=>'content',
		   'value'=>$model->content,
		   'type'=>'raw',
		),
		array(
		   'name'=>'whiteboard',
		   'value'=>($model->whiteboard)? Yii::app()->board->generateUrl($model->whiteboard): "",
		),
		array(
		   'name'=>'type',
		   'value'=>$typeOptions[$model->type],
		),
		array(
		   'name'=>'payment_type',
		   'value'=>$paymentTypes[$model->payment_type],
		),
		array(
		   'name'=>'payment_status',
		   'value'=>$paymentStatuses[$model->payment_status],
		),
		'actual_start',
		'actual_end',
		'actual_duration',
		array(
		   'name'=>'status',
		   'value'=>$model->getStatus(),
		),
		array(
		   'name'=>'status_note',
		   'value'=>$model->status_note,
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
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<a href="<?php echo Yii::app()->baseUrl.'/admin/session?course_id='.$model->course_id; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách buổi học</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>
