<?php
/* @var $this CourseController */
/* @var $model Course */

$this->breadcrumbs=array(
	'Courses'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);
?>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/course.js"></script>
<?php $this->renderPartial('_form', array('model'=>$model, 'subjects'=>$subjects,
 'availableTeachers'=>$availableTeachers, 'availableStudents'=>$availableStudents,
 'priorityTeachers'=>$priorityTeachers, 'action'=>'update')); ?>