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
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Quản lý buổi học trong khóa</h2>
    </div>
    <?php if(isset($course) && $course!==NULL):?>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/session/create?cid=<?php echo $model->course_id;?>">
			<i class="icon-plus"></i>Thêm buổi học mới
			</a>
        </div>
    </div>
    <div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Môn học:</b>&nbsp;<?php echo Subject::model()->displayClassSubject($course->subject_id);?></span>
    		</div>
    		<div class="col col-lg-8 pL0i">
    			<span class="fL"><b>Chủ đề khóa học:</b>&nbsp;<?php echo $course->title;?></span>
    			<span class="fL"><a class="btn-edit mL15" href="/admin/course/update/id/<?php echo $course->id?>" title=""></a></span>
    			<span class="fL"><a class="btn-view mL15" href="/admin/course/view/id/<?php echo $course->id?>" title=""></a></span>
    		</div>
    	</p>
    </div>    
    <div class="col col-lg-12">
    	<div class="col col-lg-3 pL0i">
    		<b>Giáo viên:</b>&nbsp;<?php $teacher = $course->getTeacher("/admin/teacher/view/id");?>
    		<?php echo ($teacher)? $teacher: "Chưa gán giáo viên";?>
    	</div>
    	<div class="col col-lg-8 pL0i"><b>Học sinh:</b>&nbsp;
	        <?php $courseStudentValues = array_values($course->getAssignedStudentsArrs("/admin/student/view/id"));?>
			<?php $students = implode(', ', $courseStudentValues);?>
			<?php echo ($students!="")? $students: "Chưa gán học sinh";?>
		</div>
	</div>
	<div class="col col-lg-12">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Kiểu khóa học:</b>&nbsp;
    			<?php $typeOptions = $course->typeOptions(); echo $typeOptions[$course->type];?></span>
    		</div>
    		<div class="col col-lg-8 pL0i">
    			<span class="fL"><b>Trạng thái:</b>&nbsp;<?php echo $course->getStatus();?></span>
    		</div>
    	</p>
    </div>
    <div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-12 pL0i">
    			<?php $preCourseStr = $course->displayConnectedPreCourses();?>
    			<span class="fL"><b>Đơn xin học:</b>&nbsp;
    				<?php echo ($preCourseStr!="")? $preCourseStr: "Chưa xác định";?>
    			</span>
    		</div>
    	</p>
    </div>
	<?php endif;?>
</div>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$startDateFilter = Yii::app()->controller->getQuery('Session[plan_start]', ''); 
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(null, "plan_start asc"),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(
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
		   'header'=>'Giáo viên',
		   'value'=>'$data->getTeacher("/admin/teacher/view/id", true)',
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
		   'header'=>'Giờ học',
		   'value'=>'$data->displayActualTime()',
		   'htmlOptions'=>array('style'=>'width:80px;'),
		),
		array(
		   'name'=>'status',
		   'value'=>'ClsAdminHtml::displaySessionStatus($data->id, $data->status)',
		   'filter'=>Session::statusOptions(),
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		array(
		   'header'=>'Lớp học ảo',
		   'value'=>'ClsAdminHtml::displayBoard($data)',
		),
		/*
		'actual_start',
		'actual_end',
		'status',
		'created_date',
		'modified_date',
		*/
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
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
	),
)); ?>
