<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	'Manage',
);
?>

<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/session.js"></script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
        <h2 class="page-title mT10">Buổi học được ghi âm</h2>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->searchRecordedSession(),
	//'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->course->type==Course::TYPE_COURSE_TESTING)?array("class"=>"testingSession"):array()',
	'columns'=>array(
		array(
		   'name'=>'course_id',
		   'value'=>'CHtml::link($data->course->id, Yii::app()->createUrl("admin/session?course_id=$data->course_id"))',
		   'type'=>'raw',
		   'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		/*
		array(
		   'header'=>'Môn học',
		   'value'=>'Subject::model()->displayClassSubject($data->course->subject_id)', 
		),
		array(
		   'name'=>'subject',
		   'value'=>'ClsAdminHtml::displayInlineEdit($data->id, $data->subject)',
		),
		array(
		   'header'=>'Kiểu lớp',
		   'value'=>'"1-".$data->total_of_student."<br/><span class=\"clrOrange\">(".count($data->assignedStudents())." hs)</span>"',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		   'type'  => 'raw',
		),
		*/
		array(
		   'name'=>'teacher_id',
		   'value'=>'$data->getTeacher("/admin/teacher/view/id", true)',
		   'type'  => 'raw',
		),
		array(
		   'header' => 'Học sinh',
		   'value'=>'implode(", ", $data->getAssignedStudentsArrs("/admin/student/view/id"))',
		   'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'min-width:150px; max-width:400px;'),
		),
		array(
		   'header'=>'Ngày học',
		   'value'=>'date("d/m/Y", strtotime($data->plan_start))',
		),
		array(
		   'header'=>'Giờ học',
		   'value'=>'$data->displayActualTime(true, true)',
		),
		/*
		array(
		   'name'=>'whiteboard',
		   'value'=>'ClsAdminHtml::displayBoard($data)',
		),*/
		'record_file',
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
		),
	),
)); ?>
