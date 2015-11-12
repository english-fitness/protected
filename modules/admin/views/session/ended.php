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
        <h2 class="page-title mT10">Buổi học đã kết thúc</h2>
    </div>
</div>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$startDateFilter = Yii::app()->controller->getQuery('Session[plan_start]', '');
	$teacherFullname = Yii::app()->controller->getQuery('Session[teacher_fullname]', '');
    $studentName = Yii::app()->controller->getQuery('Session[student_name]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(Session::STATUS_ENDED, "plan_start desc"),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->course->type==Course::TYPE_COURSE_TESTING)?array("class"=>"testingSession"):array()',
	'columns'=>array(
		array(
			'name'=>'id',
			'value'=>'$data->id',
			'htmlOptions'=>array('style'=>'text-align:center; width:60px'),
		),
		array(
			'name'=>'course_id',
			'value'=>'CHtml::link($data->course->id, Yii::app()->createUrl("admin/session?course_id=$data->course_id"))',
			'type'=>'raw', 'filter'=>false,
			'htmlOptions'=>array('style'=>'text-align:center;width:60px;'),
		),
		array(
			'header'=>'Môn học',
			'value'=>'$data->course->subject->name',
			'htmlOptions'=>array('style'=>'min-width:100px;text-align:center'),
		),
		array(
			'name'=>'subject',
			'value'=>'$data->subject',
			'htmlOptions'=>array('style'=>'width:100px;'),
        ),
		/*
		array(
		   'name'=>'total_of_student',
		   'value'=>'"1-".$data->total_of_student."<br/><span class=\"clrOrange\">(".count($data->assignedStudents())." hs)</span>"',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		   'type'  => 'raw',
		),
		*/
		array(
			'name'=>'teacher_id',
			'value'=>'$data->getTeacher("/admin/teacher/view/id", true)',
			'filter'=>'<input type="text" value="'.$teacherFullname.'" name="Session[teacher_fullname]">',
			'htmlOptions'=>array('style'=>'width:120px;text-align:center'),
			'type'  => 'raw',
		),
		array(
			'header' => 'Học sinh',
			'value'=>'implode(", ", $data->getAssignedStudentsArrs("/admin/student/view/id"))',
			'filter'=>'<input type="text" value="'.$studentName.'" name="Session[student_name]">',
			'type'  => 'raw',
			'htmlOptions'=>array('style'=>'min-width:150px; max-width:400px;'),
		),
		array(
			'header'=>'Ngày học',
			'value'=>'date("d/m/Y", strtotime($data->plan_start))',
			'filter'=>'<input type="text" value="'.$startDateFilter.'" name="Session[plan_start]">',
			'htmlOptions'=>array('style'=>'width:80px;text-align:center'),
		),
		array(
			'header' => 'Giờ học',
			'value'=>'$data->displayActualTime()',
			'htmlOptions'=>array('style'=>'width:100px;text-align:center'),
		),
		array(
			'header' => 'Skype / Platform',
			'value'=>'$data->note != null ? ($data->note->using_platform ? "Platform" : "Skype") : ""',
			'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
        ),
		array(
			'name'=>'teacher_paid',
			'header' => 'Tính tiền cho giáo viên',
			'value'=>'$data["teacher_paid"] ? "Paid" : ($data["teacher_paid"] === "0" ? "Unpaid" : "")',
			'filter'=>array('1'=>'Có', '0'=>'Không'),
			'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
        ),
		array(
			'name'=>'whiteboard',
			'value'=>'ClsAdminHtml::displayBoard($data)',
			'filter'=>false,
			'htmlOptions'=>array('style'=>'max-width:150px;'),
		),
		array(
			'header'=>'Nhận xét',
			'value'=>'CHtml::link("Xem nhận xét", Yii::app()->createUrl("/admin/sessionComment/view?sessionId=$data->id"))',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'text-align:center')
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
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
		),
	),
)); ?>
