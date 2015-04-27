<?php
/* @var $this SessionController */
/* @var $model Session */

$this->breadcrumbs=array(
	'Sessions'=>array('index'),
	'Manage',
);
?>
<script type="text/javascript">
   	setTimeout(function(){window.location.href="/admin/session/nearest"},60000);
</script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/session.js"></script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
        <h2 class="page-title mT10">Buổi học gần nhất</h2>
    </div>
</div>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$startDateFilter = Yii::app()->controller->getQuery('Session[plan_start]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->searchNearestSession(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->course->type==Course::TYPE_COURSE_TESTING)?array("class"=>"testingSession"):array()',
	'columns'=>array(
		array(
		   'name'=>'course_id',
		   'value'=>'CHtml::link($data->course->title, Yii::app()->createUrl("admin/session?course_id=$data->course_id"))',
		   'type'=>'raw', 'filter'=>false,
		),
		array(
		   'header'=>'Môn học',
		   'value'=>'Subject::model()->displayClassSubject($data->course->subject_id)',
		   'htmlOptions'=>array('style'=>'min-width:100px;'),
		),
		array(
		   'name'=>'subject',
		   'value'=>'ClsAdminHtml::displayInlineEdit($data->id, $data->subject)',
		   'htmlOptions'=>array('style'=>'min-width:100px;'),	
		),
		array(
		   'name'=>'total_of_student',
		   'value'=>'"1-".$data->total_of_student."<br/><span class=\"clrOrange\">(".count($data->assignedStudents())." hs)</span>"',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		   'type'  => 'raw',
		),
		array(
		   'name'=>'teacher_id',
		   'value'=>'$data->getTeacher("/admin/teacher/view/id", true)',
		   'type'  => 'raw', 'filter'=>false,
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
		   'filter'=>'<input type="text" value="'.$startDateFilter.'" name="Session[plan_start]">',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'header'=>'Giờ học',
		   'value'=>'$data->displayActualTime()',
		   'htmlOptions'=>array('style'=>'width:80px;'),
		),
		array(
		   'header' => 'Thời gian còn lại',
		   'value'=>'$data->displayRemainTime()',
		),
		array(
		   'name'=>'status',
		   'value'=>'ClsAdminHtml::displaySessionStatus($data->id, $data->status)',
		   'filter'=>Session::statusOptions(),
		),
		array(
		   'name'=>'whiteboard',
		   'value'=>'ClsAdminHtml::displayBoard($data)',
		   'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL5' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
		),
		array(
		   'header'=>'Hoãn/hủy',
		   'value'=>'($data->status==Session::STATUS_APPROVED)? CHtml::link("Báo hủy", "/admin/session/cancel/id/".$data->id, array("class"=>"icon-undo pL20", "style"=>"width:80px;")): ""',
		   'filter'=>false, 'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:80px;'),
		),
	),
)); ?>
