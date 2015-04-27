<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
        <h2 class="page-title mT10">Buổi học đã bị hoãn/hủy</h2>
    </div>
</div>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$startDateFilter = Yii::app()->controller->getQuery('Session[plan_start]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(null, "plan_start desc"),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(
		array(
		   'name'=>'course_id',
		   'value'=>'CHtml::link($data->course->title, Yii::app()->createUrl("admin/session?course_id=$data->course_id"))',
		   'htmlOptions'=>array('style'=>'width:150px;'),
		   'type'=>'raw', 'filter'=>false,
		),
		array(
		   'header'=>'Môn học',
		   'value'=>'Subject::model()->displayClassSubject($data->course->subject_id)',
		   'htmlOptions'=>array('style'=>'min-width:100px;'),
		),
		'subject',
		array(
		   'name'=>'total_of_student',
		   'value'=>'"1-".$data->total_of_student."<br/><span class=\"clrOrange\">(".count($data->assignedStudents())." hs)</span>"',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		   'type'  => 'raw',
		),
		array(
		   'name'=>'teacher_id',
		   'value'=>'$data->getTeacher("/admin/teacher/view/id")',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		   'type'  => 'raw', 'filter'=>false,
		),
		array(
		   'header' => 'Học sinh',
		   'value'=>'implode(", ", $data->getAssignedStudentsArrs("/admin/student/view/id"))',
		   'type'  => 'raw', 'filter'=>false,
		   'htmlOptions'=>array('style'=>'min-width:150px; max-width:400px;'),
		),
		array(
		   'header'=>'Ngày học',
		   'value'=>'date("d/m/Y", strtotime($data->plan_start))',
		   'filter'=>'<input type="text" value="'.$startDateFilter.'" name="Session[plan_start]">',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'header' => 'Giờ học',
		   'value'=>'$data->displayActualTime()',
		   'htmlOptions'=>array('style'=>'width:80px;'),
		),
		array(
		   'name' => 'status',
		   'value'=>'$data->getStatus()',
		   'filter'=>Session::statusOptions(),
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'name'=>'status_note',
		   'value'=>'$data->status_note',
		   'htmlOptions'=>array('style'=>'min-width:150px;'),
		   'type'=>'raw',
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL15' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn'),
		        ),
    		),
		),
	),
)); ?>
