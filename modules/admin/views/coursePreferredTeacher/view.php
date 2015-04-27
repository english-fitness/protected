<?php
/* @var $this CoursePreferredTeacherController */
/* @var $model CoursePreferredTeacher */

$this->breadcrumbs=array(
	'Course Preferred Teachers'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CoursePreferredTeacher', 'url'=>array('index')),
	array('label'=>'Create CoursePreferredTeacher', 'url'=>array('create')),
	array('label'=>'Update CoursePreferredTeacher', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CoursePreferredTeacher', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CoursePreferredTeacher', 'url'=>array('admin')),
);
?>

<h1>View CoursePreferredTeacher #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'course_id',
		'teacher_id',
		'priority',
	),
)); ?>
