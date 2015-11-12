<?php
/* @var $this CourseController */
/* @var $model Course */

$this->breadcrumbs=array(
	'Courses'=>array('index'),
	'Manage',
);

function getTuitionText($tuition){
	if ($tuition == 0){
		return "Chưa có";
	} else {
		return number_format($tuition)  . " đ";
	}
}
?>
<script type="text/javascript">
	function approve(id){
		var data = {'course_id': id};
		var checkConfirm = confirm("Bạn có chắc chắn muốn xác nhận khóa học này?");
		if(checkConfirm){
			$.ajax({
				url: daykemBaseUrl + "/admin/course/ajaxApprove",
				type: "POST", dataType: 'json', data:data,
				success: function(data) {
					if(data.success){
						$('#courseStatus'+id).html('Đã xác nhận');
					}
				}
			});
		}
	}
</script>
<?php
$studentName = Yii::app()->controller->getQuery('Course[student_name]', '');
$teacherFullname = Yii::app()->controller->getQuery('Course[teacher_fullname]', '');
$registration = new ClsRegistration();

$columns = array(
	array(
	    'name'=>'id',
	    'value'=>'$data->id',
	    'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
	),
	array(
	    'name'=>'subject_id',
	    'value'=>'$data->subject->name',
	    'filter'=>Subject::model()->generateSubjectFilters(),
	    'htmlOptions'=>array('style'=>'width:150px; text-align:center;'),
	),
	array(
	    'header' => 'Số buổi',
	    'value'=>'CHtml::link($data->countSessions(null, true)." buổi", Yii::app()->createUrl("admin/session?course_id=$data->id"))',
	    'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
	    'type'=>'raw',
	),
	array(
	    'header'=>'Giáo viên',
	    'value'=>'CHtml::link($data->teacher->fullname(), "/admin/teacher/view/id/".$data->teacher->id)',
	    'filter'=>'<input type="text" value="'.$teacherFullname.'" name="Course[teacher_fullname]">',
	    'type' => 'raw',
	    'htmlOptions'=>array('style'=>'width:120px; text-align:center;'), 
	),
	array(
		'header' => 'Học sinh',
		'value'=>'implode(", ", $data->getAssignedStudentsArrs("/admin/student/view/id"))',
		'filter'=>'<input type="text" value="'.$studentName.'" name="Course[student_name]">',
		'type' => 'raw',
		'htmlOptions'=>array('style'=>'max-width:400px;'),
	),
	array(
		'header' => 'Ngày bắt đầu',
		'value'=>'$data->getFirstDateInList("ASC")',
		'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
	),
	array(
		'header' => 'Ngày kết thúc',
		'value'=>'$data->getFirstDateInList("DESC")',
		'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
	),
	array(
		'name'=>'status',
		'value'=>'ClsAdminHtml::displayCourseStatus($data->id, $data->status)',
		'filter'=>Course::statusOptions(),
		'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
	),
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
		'headerHtmlOptions'=>array('style'=>'width:60px;'),
		'htmlOptions'=>array('style'=>'width:60px;'),
	),
);

//tuition
if ($model->type == Course::TYPE_COURSE_NORMAL){
	$tuitionColumns = array(
		array(
			'header'=>'Học phí',
			'value'=>'CHtml::link(
				getTuitionText($data->final_price),
				Yii::app()->createUrl("admin/coursePayment/course/id/$data->id")
			)',
			'htmlOptions'=>array('style'=>'width:120px;text-align:center'),
			'type'=>'raw',
		),
		array(
			'name'=>'total_sessions',
			'value'=>function($data){
				$paidSession = Session::model()->countByAttributes(array(
					'course_id'=>$data->id,
					'teacher_paid'=>true,
					'deleted_flag'=>0,
				));
				return $paidSession."/".$data->total_sessions." buổi";
			},
			'htmlOptions'=>array('style'=>'width:80px;text-align:center'),
		),
	);
	array_splice($columns, 4, 0, $tuitionColumns);
}

//report
$reportColumn = array(
	array(
		'header'=>'Báo cáo',
		'value'=>'CHtml::link("Báo cáo", "/admin/courseReport/course/id/$data->id")',
		'htmlOptions'=>array('text-align:center'),
		'type'=>'raw',
		'htmlOptions'=>array('style'=>'width:80px;text-align:center'),
	),
);
if ($model->type == Course::TYPE_COURSE_NORMAL){
	array_splice($columns, 9, 0, $reportColumn);
} else if ($model->type == Course::TYPE_COURSE_TRAINING){
	array_splice($columns, 7, 0, $reportColumn);
}

//next report date and type
if ($model->deleted_flag == 1){
	$typeColumn = array(
		array(
		    'name'=>'type',
		    'value'=>'Course::typeOptions()[$data->type]',
		    'filter'=>false,
		    'htmlOptions'=>array('style'=>'width:125px; text-align:center;'),
	    )
	);
	array_splice($columns, 1, 0, $typeColumn);
} else if ($model->type == Course::TYPE_COURSE_NORMAL){
	$reportDateColumn = array(
		array(
		    'header'=>'Ngày báo cáo tiếp theo',
		    'value'=>'$data->getNextReportDate()',
		    'filter'=>false,
		    'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
	    )
	);
	array_splice($columns, 10, 0, $reportDateColumn);
}
?>
<div class="page-header-toolbar-container row">
	<div class="col col-lg-6">
		<?php 
			$pageTitle = 'Danh sách khóa học'; $typeOptions = $model->typeOptions();
			if(isset($_GET['type']) && isset($typeOptions[$_GET['type']])){
				$pageTitle = $typeOptions[$_GET['type']];
			}
			if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
				$pageTitle = 'Khóa học đã bị xóa/hủy';
			}
		?>
		<h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
	</div>
	<div class="col col-lg-6 for-toolbar-buttons">
		<div class="btn-group">
			<?php
				if (isset($_GET['type'])){
					$typeParam = "?type=".$_GET['type'];
				} else {
					$typeParam = "";
				}
			?>
			<a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/course/create<?php echo $typeParam?>">
			<i class="icon-plus"></i>Thêm khóa học
			</a>
		</div>
	</div>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->with(array(
		'teacher'=>array(
			'select'=>array('id', 'firstname', 'lastname')
		),
		'students'
	))->search('t.created_date DESC'),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>$columns,
)); ?>