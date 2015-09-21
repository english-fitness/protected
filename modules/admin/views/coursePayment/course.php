<?php
/* @var $this CoursePaymentController */
/* @var $model CoursePayment */

?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Học phí của khóa học</h2>
    </div>
    <?php if(isset($course) && $course!==NULL):?>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/coursePayment/create?course_id=<?php echo $model->course_id;?>">
			<i class="icon-plus"></i>Thêm học phí
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
    		<?php echo ($teacher)? $teacher: "Chưa có giáo viên";?>
    	</div>
    	<div class="col col-lg-8 pL0i"><b>Học sinh:</b>&nbsp;
	        <?php $courseStudentValues = array_values($course->getAssignedStudentsArrs("/admin/student/view/id"));?>
			<?php $students = implode(', ', $courseStudentValues);?>
			<?php echo ($students!="")? $students: "Chưa có học sinh";?>
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
    <div class="col col-lg-12">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Tổng học phí:</b>&nbsp;<?php echo number_format($course->final_price)?> đ</span>
    		</div>
            <div class="col col-lg-8 pL0i">
    			<span class="fL"><b>Tổng số buổi học:</b>&nbsp;<?php echo $course->total_sessions?> buổi</span>
    		</div>
    	</p>
    	</p>
    </div>
	<?php endif;?>
</div>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$startDateFilter = Yii::app()->controller->getQuery('Session[plan_start]', ''); 
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->with('packageOption','packageOption.package', 'modifiedUser')->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
        array(
            'name'=>'id',
            'value'=>'$data->id',
            'htmlOptions'=>array('style'=>'width:100px;text-align:center'),
        ),
        array(
            'header'=>'Học phí',
            'value'=>'number_format($data->packageOption->tuition)',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'header'=>'Số buổi',
            'value'=>'$data->packageOption->package->sessions',
            'htmlOptions'=>array('style'=>'text-align:center'),
        ),
        array(
            'name'=>'payment_date',
            'value'=>'$data->payment_date != null ? date("d/m/Y", strtotime($data->payment_date)) : ""',
            'htmlOptions'=>array('style'=>'width:150px;text-align:center'),
        ),
        array(
            'name'=>'note',
            'value'=>'$data->note',
            'htmlOptions'=>array('style'=>'width:500px'),
            'filter'=>false,
        ),
        array(
            'header'=>'Sửa lần cuối',
            'value'=>'date("d/m/Y H:i", strtotime($data->last_modified_date))',
            'htmlOptions'=>array('style'=>'width:130px;text-align:center'),
        ),
        array(
            'header'=>'Bởi',
            'value'=>'$data->modifiedUser->fullname()',
            'htmlOptions'=>array('style'=>'width:200px'),
        ),
        array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}',
			'buttons'=>array (
		        'update'=> array(
					'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
					'url'=>'Yii::app()->createUrl("admin/coursePayment/update/id/$data->id")',
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL5' ),
					'url'=>'Yii::app()->createUrl("admin/coursePayment/view/id/$data->id")',
		        ),
    		),
			'htmlOptions'=>array('style'=>'width:80px;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		),
	),
)); ?>
