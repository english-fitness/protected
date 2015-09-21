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
       'name'=>'subject_id',
       'value'=>'Subject::model()->displayClassSubject($data->subject_id)',
       'filter'=>Subject::model()->generateSubjectFilters(),
    ),
    array(
       'name'=>'type',
       'value'=>'Course::typeOptions()[$data->type]',
       'filter'=>false,
       'htmlOptions'=>array('style'=>'min-width:125px; text-align:center;'),
    ),
    array(
       'header' => 'Số buổi',
       'value'=>'CHtml::link($data->countSessions(null, true)." buổi", Yii::app()->createUrl("admin/session?course_id=$data->id"))',
       'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
       'type'=>'raw',
    ),
    array(
       'name'=>'total_of_student',
       'value'=>'"1-".$data->total_of_student',
       'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
       'filter'=>$registration->totalStudentOptions(6, true),
       'type' => 'raw',		   
    ),
    array(
       'header'=>'Giáo viên',
       'value'=>'$data->getTeacher("/admin/teacher/view/id")',
       'filter'=>'<input type="text" value="'.$teacherFullname.'" name="Course[teacher_fullname]">',
       'type' => 'raw',
    ),
    array(
       'header' => 'Học sinh',
       'value'=>'implode(", ", $data->getAssignedStudentsArrs("/admin/student/view/id"))',
       'filter'=>'<input type="text" value="'.$studentName.'" name="Course[student_name]">',
       'type' => 'raw',
       'htmlOptions'=>array('style'=>'max-width:400px;'),
    ),
    array(
       'header' => 'Ngày bắt đầu',
       'value'=>'$data->getFirstDateInList("ASC")',
       'htmlOptions'=>array('style'=>'width:100px; text-align:center;'), 
    ),
    array(
       'header' => 'Ngày kết thúc',
       'value'=>'$data->getFirstDateInList("DESC")',
       'htmlOptions'=>array('style'=>'width:100px; text-align:center;'), 
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
    ),
);
if ($model->type == Course::TYPE_COURSE_NORMAL){
    $tuitionColumns = array(
        array(
            'header'=>'Học phí',
            'value'=>'CHtml::link(
                getTuitionText($data->final_price),
                Yii::app()->createUrl("admin/coursePayment/course/id/$data->id")
            )',
            'htmlOptions'=>array('style'=>'width:130px;text-align:center'),
            'type'=>'raw',
        ),
        array(
            'name'=>'total_sessions',
            'value'=>'$data->total_sessions . " buổi"',
            'htmlOptions'=>array('style'=>'width:80px;text-align:center'),
        ),
    );
    array_splice($columns, 4, 0, $tuitionColumns);  
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
	'dataProvider'=>$model->search("created_date DESC"),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>$columns,
)); ?>