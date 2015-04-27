<?php
/* @var $this PreregisterCourseController */
/* @var $model PreregisterCourse */

$this->breadcrumbs=array(
	'Preregister Courses'=>array('index'),
	'Manage',
);
?>
<script>
	//Refuse precourse ajax
	function refuse(id){
		var data = {'preCourseId': id};
		var checkConfirm = confirm("Bạn có chắc chắn muốn từ chối đơn xin học này?");
			if(checkConfirm){
			$.ajax({
				url: daykemBaseUrl + "/admin/preregisterCourse/ajaxRefuse",
				type: "POST", dataType: 'json', data:data,
				success: function(data) {
					if(data.success){
						$('#preCourseStatus'+id).html('<span class="error">Đã từ chối</span>');
					}
				}
			});
		}
	}
</script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php 
    		$pageTitle = 'Danh sách đơn xin học';
    		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
    			$pageTitle = 'Đơn xin học đã bị xóa/hủy';
    		}
    	?>
        <h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/preregisterCourse/create">
			<i class="icon-plus"></i>Thêm đơn xin học cho học sinh
			</a>
        </div>
    </div>
</div>
<?php if(isset($presetCourse) && isset($presetCourse->id)):?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
    	<span class="fL"><b>Thuộc chủ đề khóa học tạo trước:</b>&nbsp;<?php echo $presetCourse->title;?></span>
    	<span class="fL"><a class="btn-edit mL15" href="/admin/presetCourse/update/id/<?php echo $presetCourse->id;?>" title=""></a></span>
    	<span class="fL"><a class="btn-view mL15" href="/admin/presetCourse/view/id/<?php echo $presetCourse->id;?>" title=""></a></span>
    </div>
</div>
<?php endif;?>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$createdDateFilter = Yii::app()->controller->getQuery('PreregisterCourse[created_date]', '');
	$startDate = Yii::app()->controller->getQuery('PreregisterCourse[start_date]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(
		'title',
		array(
		   'header'=>'Học sinh',
		   'value'=>'$data->getStudent("/admin/student/view/id")',
		   'type' => 'raw',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name'=>'subject_id',
		   'value'=>'Subject::model()->displayClassSubject($data->subject_id)',
		   'filter'=>Subject::model()->generateSubjectFilters(),		   
		),
		array(
		   'name'=>'total_of_student',
		   'value'=>'"1-".$data->total_of_student',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		   'filter'=>$registration->totalStudentOptions(6, true),
		),
		array(
		   'name'=>'start_date',
		   'value'=>'date("d/m/Y", strtotime($data->start_date))',
		   'filter'=>'<input type="text" value="'.$startDate.'" name="PreregisterCourse[start_date]">',
		),
		array(
		   'name'=>'total_of_session',
		   'value'=>'isset($data->package->title)?$data->package->title:"Lớp cũ"',
		   'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
		),
		array(
		   'name'=>'final_price',
		   'value'=>'number_format($data->final_price)',
		   'htmlOptions'=>array('style'=>'width:100px; text-align:right;'),
		),
		array(
		   'header'=>'Tiền thực tế đã nộp',
		   'value'=>'number_format($data->getTotalPaidAmount())',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:right;'),
		   'type' => 'raw',
		),
		array(
		   'name'=>'payment_status',
		   'value'=>'$data->displayHistoryPaymentLink()',
		   'filter'=>ClsCourse::paymentStatuses(),
		   'htmlOptions'=>array('style'=>'width:120px;'),
		   'type' => 'raw',
		),		
		array(
		   'name'=>'session_per_week',
		   'value'=>'ClsAdminHtml::displaySessionPerWeek($data->session_per_week)',
		   'type'  => 'raw',
		),
		array(
		   'name'=>'created_date',
		   'value'=>'date("d/m/Y, H:i", strtotime($data->created_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="PreregisterCourse[created_date]">',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name'=>'status',
		   'value'=>'ClsAdminHtml::displayPreregisterCourseStatus($data->id, $data->status, $data->payment_status)',
		   'filter'=>$model->statusOptions(),
		   'htmlOptions'=>array('style'=>'width:120px;'),	
		),
		array(
		   'name'=>'course_id',
		   'value'=>'$data->displayActualCourse("Khóa học")',
		   'filter'=>false,
		   'type' => 'raw',
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
