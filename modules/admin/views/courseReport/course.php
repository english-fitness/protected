<?php
/* @var $this CoursePaymentController */
/* @var $model CoursePayment */

?>
<style>
    .modal-dialog{
        left:0 !important;
        width:960px;
        margin-top:0;
    }
    .iframe-container{
        background:url(/media/images/icon/ripple-loader.gif) center center no-repeat;
    }
</style>
<script src="/media/js/bootstrap/bootstrap-dialog.min.js"></script>
<link rel="stylesheet" href="/media/css/bootstrap/bootstrap-dialog.min.css">
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Báo cáo trong khóa học</h2>
    </div>
    <?php if(isset($course) && $course!==NULL):?>
        <div class="col col-lg-6 for-toolbar-buttons">
            <div class="btn-group">
                <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/courseReport/create?course_id=<?php echo $model->course_id;?>">
    			<i class="icon-plus"></i>Thêm báo cáo
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
                    <span class="fL"><b>Trình độ:</b>&nbsp;
                    <?php $typeOptions = $course->typeOptions(); echo $course->level;?></span>
                </div>
                <div class="col col-lg-8 pL0i">
                    <span class="fL"><b>Giáo trình:</b>&nbsp;<?php echo $course->curriculum;?></span>
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
        </div>
        <?php if ($course->status != Course::STATUS_ENDED && $course->deleted_flag == 0):?>
            <div class="col col-lg-12">
                <p>
                    <div class="col col-lg-3 pL0i">
                        <span class="fL"><b>Ngày báo cáo tiếp theo:</b>&nbsp;<?php echo $course->getNextReportDate()?></span>
                    </div>
                </p>
            </div>
        <?php endif;?>
	<?php endif;?>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->with('lastModifiedUser', 'reportingTeacher', 'course')->search(),
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
            'name'=>'reporting_teacher',
            'value'=>'$data->reportingTeacher->fullname()',
            'htmlOptions'=>array('style'=>'width:150px;text-align:center'),
        ),
        array(
            'name'=>'report_type',
            'value'=>'$data->reportTypeOptions($data->report_type)',
            'htmlOptions'=>array('style'=>'width:150px;text-align:center'),
        ),
        array(
            'name'=>'report_date',
            'value'=>'date("d/m/Y", strtotime($data->report_date))',
            'htmlOptions'=>array('style'=>'width:130px;text-align:center'),
        ),
        array(
            'header'=>'Báo cáo',
            'value'=>'"<a class=\"reportViewLink\" href=\"".$data->getGoogleDocsViewerUrl(array("embedded"=>"true"))."\">Xem báo cáo</a>".
                       "<br>".
                       "-<a href=\"".$data->getReportUrl()."\">Tải xuống</a>-"',
            'type'=>'raw',
            'htmlOptions'=>array("style"=>"width:130px;text-align:center"),
        ),
        array(
            'header'=>'Sửa lần cuối bởi',
            'value'=>'$data->lastModifiedUser->fullname()',
            'htmlOptions'=>array('style'=>'width:150px;text-align:center'),
        ),
        array(
            'header'=>'Lúc',
            'value'=>'date("d/m/Y H:i", strtotime($data->last_modified_date))',
            'htmlOptions'=>array('style'=>'width:150px;text-align:center'),
        ),
        array(
            'header'=>'',
            'value'=>'"<a class=\"btn-edit \" href=\"/admin/courseReport/update/id/".$data->id."\" title=\"Xem\"></a>"',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'width:30px;text-align:center'),
        )
	),
)); ?>
<script>
$(".reportViewLink").click(function(e){
    e.preventDefault();
    
    BootstrapDialog.show({
        title:'Progress report',
        message:'<div class="iframe-container"><iframe id="googleDocsViewer" height="520" width="910" src="' + this.getAttribute('href') + '"></div>',
    });
    
    $('body').unbind('mousewheel DOMMouseScroll');
    $('body').bind('mousewheel DOMMouseScroll', onWheel);
    
    return false;
});

function onWheel (e){
    var iframe = document.getElementById('googleDocsViewer');
    if (e.target === iframe)
        e.preventDefault();
}
</script>