<?php
/* @var $this PreregisterCourseController */
/* @var $model PreregisterCourse */

$this->breadcrumbs=array(
	'Preregister Courses'=>array('index'),
	$model->title,
);
?>

<h1>Chi tiết đơn xin học</h1>
<?php 
	$statusOptions = $model->statusOptions();
	$actualCourse = $model->displayActualCourse();
	if($actualCourse==NULL && !$model->preset_course_id){
		$actualCourse = '<a href="/admin/course/create?preCourseId='.$model->id.'">Tạo khóa học từ đơn xin học này</a>';
	}elseif($model->preset_course_id){
		$actualCourse = '<span>Đơn xin học thuộc đơn/khóa tạo trước: </span><a href="/admin/presetCourse/view/id/'.$model->preset_course_id.'">[xem đơn/khóa tạo trước]</a>';
	}
	$paymentStatuses = ClsCourse::paymentStatuses();
	$paymentTypes = ClsCourse::paymentTypes();
	$typeOptions = Course::model()->typeOptions();
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'title',
		array(
		   'name'=>'Học sinh đăng ký',
		   'value'=>$model->getStudent("/admin/student/view/id"),
		   'type'=>'raw',
		),
		array(
		   'name'=>'subject_id',
		   'value'=>Subject::model()->displayClassSubject($model->subject_id),
		),
		array(
		   'name'=>'Kiểu lớp học',
		   'value'=>$model->displayTotalOfStudentStr(),
		),
		array(
		   'name'=>'Kiểu khóa học',
		   'value'=>$typeOptions[$model->course_type],
		),
		array(
		   'name'=>'Ngày dự kiến bắt đầu',
		   'value'=>date('d/m/Y', strtotime($model->start_date)),
		),
        array(
            'name'=>'total_of_session',
            'value'=>$model->totalOfSession,
        ),
		array(
		   'name'=>'session_per_week',
		   'value'=>ClsAdminHtml::displaySessionPerWeek($model->session_per_week),
		   'type'=>'raw',
		),
		array(
		   'name'=>'Đã tạo khóa học?',
		   'value'=>$actualCourse,
		   'type'=>'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>$statusOptions[$model->status],
		),
		array(
		   'name'=>'payment_type',
		   'value'=>$paymentTypes[$model->payment_type],
		),
		array(
		   'name'=>'Học phí toàn khóa học',
		   'value'=>number_format($model->final_price),
		),
		array(
		   'name'=>'<span class="error">Học phí cào thẻ (nếu áp dụng)</span>',
		   'value'=>number_format($model->getTotalFinalPrice()),
		),
		array(
		   'name'=>'Số tiền thực tế đã đóng',
		   'value'=>number_format($model->getTotalPaidAmount()),
		),
		array(
		   'name'=>'payment_status',
		   'value'=>$model->displayHistoryPaymentLink(),
		   'type'=>'raw',
		),
		array(
		   'name'=>'Thông tin mức học phí',
		   'value'=>$model->payment_note,
		   'type'=>'raw',
		),		
		'order_code',
		array(
		   'name'=>'payment_date',
		   'value'=>($model->payment_date)? date('d/m/Y H:i', strtotime($model->payment_date)):"",
		),
		array(
		   'name'=>'note',
		   'value'=>$model->note,
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
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<a href="<?php echo Yii::app()->baseUrl.'/admin/preregisterCourse?status='.$model->status; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách đơn xin học</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>
