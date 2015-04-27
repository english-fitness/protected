<?php
/* @var $this CoursePreferredTeacherController */
/* @var $model CoursePreferredTeacher */

$this->breadcrumbs=array(
	'Course Preferred Teachers'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CoursePreferredTeacher', 'url'=>array('index')),
	array('label'=>'Create CoursePreferredTeacher', 'url'=>array('create')),
	array('label'=>'View CoursePreferredTeacher', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CoursePreferredTeacher', 'url'=>array('admin')),
);
?>

<h1>Update CoursePreferredTeacher <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>