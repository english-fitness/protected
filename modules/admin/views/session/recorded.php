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

<div id="disk_usage" style="display:inline-table; background-color:#eeeeee; border-radius:5px; padding: 5px 10px 0px 10px;">
	<?php 
		$record_dir = Yii::app()->params['recordDir'];
		if (!$record_dir)
			$record_dir = "/home/administrator/records/";
		
		$io = popen ( 'du -s ' . $record_dir, 'r' );
		$size = fgets ( $io, 4096);
		$size = substr ( $size, 0, strpos ( $size, "\t" ) );
		if ($size <= 1024)
			$size .= " KB";
		else if ($size <= 1048576)
			$size = round($size/1024, 2) . " MB";
		else
			$size = round($size/1048576, 2) . " GB";
		pclose ( $io );
		echo 'Dung lượng thư mục ghi âm: ' . $size;
		
		$lowSpaceThreshold = 0.1;
		$total = round(disk_total_space('/home/')/pow(2, 30), 2);
		$remaining = round(disk_free_space('/home/')/pow(2, 30), 2);
		if ($remaining/$total < $lowSpaceThreshold)
		{
			echo "<p style='color:red; line-height:25px; margin:0'>Dung lượng còn lại: " . $remaining . " GB (" . round($remaining/$total, 2)*100 . "%)</p>";
			echo "<p style='color:red'>Dung lượng lưu trữ trên server còn dưới " . $lowSpaceThreshold*100 . 
					"%, tải xuống các file ghi âm để lưu trữ và xóa bớt file ghi âm trước khi ghi âm lớp học mới</p>";
		}
		else
		{
			echo "<p style='color:blue; line-height:25px; margin:0'>Dung lượng còn lại: " . $remaining . " GB (" . round($remaining/$total, 2)*100 . "%)</p>";
		}
	?>
</div>

<?php 
	$startDateFilter = Yii::app()->controller->getQuery('Session[plan_start]', '');
	$teacherFullname = Yii::app()->controller->getQuery('Session[teacher_fullname]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->searchRecordedSession(),
	'filter'=>$model,
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
		'subject',
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
		   'filter'=>'<input type="text" value="'.$teacherFullname.'" name="Session[teacher_fullname]">',
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
		   'filter'=>'<input type="text" value="'.$startDateFilter.'" name="Session[plan_start]">',
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
