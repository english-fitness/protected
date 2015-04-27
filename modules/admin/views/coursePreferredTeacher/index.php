<?php
/* @var $this CoursePreferredTeacherController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Course Preferred Teachers',
);

$this->menu=array(
	array('label'=>'Create CoursePreferredTeacher', 'url'=>array('create')),
	array('label'=>'Manage CoursePreferredTeacher', 'url'=>array('admin')),
);
?>

<h1>Course Preferred Teachers</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
