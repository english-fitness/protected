<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	$model->id,
);
?>

<h1>Chi tiết khóa học</h1>
<?php 
	$statusOptions = Course::statusOptions();
	$typeOptions = $model->typeOptions();
	$paymentStatuses = ClsCourse::paymentStatuses();
	$paymentTypes = ClsCourse::paymentTypes();
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
		   'name'=>'Môn học',
		   'value'=>Subject::model()->displayClassSubject($model->subject_id),
		),
		array(
		   'name'=>'Kiểu khóa học',
		   'value'=>$typeOptions[$model->type],
		),
		array(
		   'name'=>'Kiểu lớp học',
		   'value'=>"1-".$model->total_of_student,
		),
		array(
		   'name'=>'Chủ đề khóa học',
		   'value'=>CHtml::link($model->title, Yii::app()->createUrl("admin/session?course_id=$model->id")),
		   'type'  => 'raw',
		),
		array(
		   'name'=>'Giáo viên',
		   'value'=>$model->getTeacher("/admin/teacher/view/id"),
		   'type'  => 'raw',
		),		
		array(
		   'name'=>'Học sinh',
		   'value'=>implode(", ", $model->getAssignedStudentsArrs("/admin/student/view/id")),
		   'type'  => 'raw',
		),
		'teacher_form_url',
		'student_form_url',
		array(
		   'name'=>'Đơn xin học đã ghép?',
		   'value'=>$model->displayConnectedPreCourses(),
		   'type'  => 'raw',
		),
		array(
		   'name'=>'content',
		   'value'=>$model->content,
		   'type'  => 'raw',
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
		   'name'=>'payment_status',
		   'value'=>$paymentStatuses[$model->payment_status],
		),
		array(
		   'name'=>'created_user_id',
		   'value'=>($model->created_user_id)? User::model()->displayUserById($model->created_user_id):"",
		),
		array(
		   'name'=>'created_date',
		   'value'=>date('d/m/Y H:i', strtotime($model->created_date)),
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
		<a href="<?php echo Yii::app()->baseUrl.'/admin/course'; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách khóa học</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>
