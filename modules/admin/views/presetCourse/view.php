<?php
/* @var $this PresetCourseController */
/* @var $model PresetCourse */

$this->breadcrumbs=array(
	'Preset Courses'=>array('index'),
	$model->title,
);
?>

<h1>Chi tiết đơn/khóa tạo trước</h1>
<?php $statusOptions = $model->statusOptions();?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
		   'name'=>'Môn học',
		   'value'=>Subject::model()->displayClassSubject($model->subject_id),
		),
		array(
		   'name'=>'Giáo viên',
		   'value'=>$model->getTeacher("/admin/teacher/view/id"),
		   'type'  => 'raw',
		),
		'title',
		'price_per_student',
		array(
		   'name'=>'Ưu đãi học phí',
		   'value'=>$model->getDiscountPriceDescription(),
		   'type'=>'raw',
		),
		'min_student',
		'max_student',
		'total_of_session',
		'start_date',
		array(
		   'name'=>'session_per_week',
		   'value'=>ClsAdminHtml::displaySessionPerWeek($model->session_per_week),
		   'type'=>'raw',
		),
		array(
		   'name'=>'Số đơn đăng ký học',
		   'value'=>CHtml::link($model->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID)."/".$model->countRegisteredStudents()." đơn đăng ký học", Yii::app()->createUrl("admin/preregisterCourse?preset_id=$model->id")),
		   'type'=>'raw',
		),
		array(
		   'name'=>'Khóa học thực tế',
		   'value'=>$model->displayActualCourse(NULL),
		   'type' => 'raw',
		),
		array(
		   'name'=>'short_description',
		   'value'=>$model->short_description,
		   'type'=>'raw',
		),
		array(
		   'name'=>'description',
		   'value'=>$model->description,
		   'type'=>'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>$statusOptions[$model->status],
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
