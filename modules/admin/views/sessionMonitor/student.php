<?php
/* @var $this SessionMonitorController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Theo dõi buổi học</h2>
    </div>
</div>
<?php 
	$statusOptions = $model->statusOptions(); unset($statusOptions['-1']);
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'name'=>'id',
		   'value'=>'$data->id',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		),
		array(
		   'name'=>'firstname',
		   'value'=>'$data->fullName()',
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		array(
		   'name'=>'username',
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		'email',
		array(
		   'name' => 'phone',
		   'value'=>'$data->displayContactIcons()',
		   'htmlOptions'=>array('style'=>'width:135px;'),
		),
		array(
		   'name'=>'status',
		   'value'=>'($data->statusOptions($data->status))',
		   'filter'=>$statusOptions,
		   'htmlOptions'=>array('style'=>'width:135px;'),
		),
		array(
		   'header'=>'Khóa học chính thức',
		   'value'=>'Student::model()->displayCourseMonitorLink($data->id, " khóa học chính thức", Course::TYPE_COURSE_NORMAL)',
		   'htmlOptions'=>array('style'=>'width:140px; text-align:center;'),
		   'type' => 'raw',
		),
		array(
		   'header'=>'Khóa học thử',
		   'value'=>'Student::model()->displayCourseMonitorLink($data->id, " khóa học thử", Course::TYPE_COURSE_TRAINING)',
		   'htmlOptions'=>array('style'=>'width:120px; text-align:center;'),
		   'type' => 'raw',
		),
	),
)); ?>