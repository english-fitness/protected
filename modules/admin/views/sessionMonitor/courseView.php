<?php
/* @var $this SessionMonitorController */
/* @var $model Course */
?>

<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php 
    		$pageTitle = 'Danh sách khóa học'; $typeOptions = $model->typeOptions();
    		if(isset($_GET['type']) && isset($typeOptions[$_GET['type']])){
    			$pageTitle = $typeOptions[$_GET['type']];
    		}
    	?>
        <h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
    </div>
	<div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Học sinh:</b>&nbsp;<?php echo $student->fullname()?></span>
    		</div>
    	</p>
    </div>
</div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->searchByStudent($student->id, "created_date DESC"),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(
		array(
			'name'=>'id',
			'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
		),
		array(
		   'name'=>'subject_id',
		   'value'=>'Subject::model()->displayClassSubject($data->subject_id)',
		   'filter'=>Subject::model()->generateSubjectFilters(),
		   'htmlOptions'=>array('style'=>'width:240px; text-align:left;'),
		),
		array(
		   'name'=>'final_price',
		   'value'=>'number_format($data->final_price)',
		   'htmlOptions'=>array('style'=>'width:100px; text-align:right;'),
		),
		array(
		   'name'=>'total_sessions',
		   'value'=>'$data->total_sessions',
		   'filter'=>false,
		   'htmlOptions'=>array('style'=>'width:120px; text-align:center;'),
		),
		array(
		   'header'=>'Giáo viên',
		   'value'=>'$data->getTeacher("/admin/teacher/view/id")',
		   'htmlOptions'=>array('style'=>'width:140px;'),
		   'type' => 'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>'ClsAdminHtml::displayCourseStatus($data->id, $data->status)',
		   'filter'=>Course::statusOptions(),
		   'htmlOptions'=>array('style'=>'width:132px; text-align:center;'),
		),
		array(
			'header'=>'Buổi học trên platform',
			'value'=>'CHtml::link(
				SessionNote::countCompletedSessionByCourse($data->id, 1) . " Buổi",
				"/admin/sessionMonitor/sessionView?cid=$data->id&using_platform=1"
			)',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'), 
			'type'=>'raw',
		),
		array(
			'header'=>'Buổi học ngoài platform',
			'value'=>'CHtml::link(
				SessionNote::countCompletedSessionByCourse($data->id, 0) . " Buổi",
				"/admin/sessionMonitor/sessionView?cid=$data->id&using_platform=0"
			)',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'), 
			'type'=>'raw',
		),
		array(
			'header'=>'Buổi học đã thực hiện',
			'value'=>'CHtml::link(
				SessionNote::countCompletedSessionByCourse($data->id) . " Buổi",
				"/admin/sessionMonitor/sessionView?cid=$data->id&ended=1"
			)',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'), 
			'type'=>'raw',
		),
		array(
			'header'=>'Buổi học đã hủy',
			'value'=>'CHtml::link(
				SessionNote::countCancelledSessionByCourse($data->id) . " Buổi",
				"/admin/session/canceled?Session[status]=4&Session[course_id]= " . $data->id
			)',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'), 
			'type'=>'raw',
		),
		array(
			'header'=>'Tổng số buổi học',
			'value'=>'CHtml::link(
				SessionNote::countSessionByCourse($data->id) . " Buổi",
				"/admin/sessionMonitor/sessionView?cid=$data->id"
			)',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'), 
			'type'=>'raw',
		),
	),
)); ?>
