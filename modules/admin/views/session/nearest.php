<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	'Manage',
);
?>
<script type="text/javascript" src="<?php echo $this->baseAssetsUrl ?>/js/admin/session.js"></script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
        <h2 class="page-title mT10">Buổi học gần nhất</h2>
    </div>
</div>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$startDateFilter = Yii::app()->controller->getQuery('Session[plan_start]', '');
	$teacherFullname = Yii::app()->controller->getQuery('Session[teacher_fullname]', '');
    $studentName = Yii::app()->controller->getQuery('Session[student_name]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gridView',
	'dataProvider'=>$model->searchNearestSession(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->course->type==Course::TYPE_COURSE_TESTING)?array("class"=>"testingSession"):array()',
	'columns'=>array(
		array(
		   'name'=>'course_id',
		   'value'=>'CHtml::link($data->course->id, Yii::app()->createUrl("admin/session?course_id=$data->course_id"))',
		   'type'=>'raw', 'filter'=>false,
		   'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
		   'header'=>'Môn học',
		   'value'=>'$data->course->subject->name',
		   'htmlOptions'=>array('style'=>'min-width:100px;text-align:center'),
		),
		array(
		   'name'=>'subject',
		   'value'=>'ClsAdminHtml::displayInlineEdit($data->id, $data->subject)',
		   'htmlOptions'=>array('style'=>'min-width:100px;text-align:center'),	
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
		   'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'text-align:center'),
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
		   'htmlOptions'=>array('style'=>'width:90px;text-align:center'),
		),
		array(
		   'header'=>'Giờ học',
		   'value'=>'$data->displayActualTime()',
		   'htmlOptions'=>array('style'=>'width:90px;text-align:center'),
		),
		array(
		   'header' => 'Thời gian còn lại',
		   'value'=>'$data->displayRemainTime()',
		   'htmlOptions'=>array('style'=>'text-align:center'),
		),
		array(
		   'name'=>'status',
		   'value'=>'ClsAdminHtml::displaySessionStatus($data->id, $data->status)',
		   'filter'=>Session::statusOptions(),
		   'htmlOptions'=>array('style'=>'text-align:center'),
		),
		array(
		   'name'=>'whiteboard',
		   'value'=>'ClsAdminHtml::displayBoard($data)',
		   'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL5' ),
		        ),
    		),
    		'htmlOptions'=>array('style'=>'width:80px;'),
		),
		array(
		   'header'=>'Hoãn/hủy',
		   'value'=>'($data->status==Session::STATUS_APPROVED)? CHtml::link("Báo hủy", "/admin/session/cancel/id/".$data->id, array("class"=>"icon-undo pL20", "style"=>"width:80px;")): ""',
		   'filter'=>false, 'type'=>'raw',
		   'htmlOptions'=>array('style'=>'text-align:center'),
		),
	),
)); ?>
<script>
	setInterval(function(){$.fn.yiiGridView.update("gridView");}, 60000);
</script>