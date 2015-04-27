<?php
/* @var $this PreregisterCourseController */
/* @var $model PreregisterCourse */

$this->breadcrumbs=array(
	'Preregister Courses'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Lịch sử thanh toán học phí</h2>
    </div>
     <?php if(isset($preCourse) && $preCourse!==NULL):?>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/preregisterPayment/create?precourse_id=<?php echo $preCourse->id;?>">
			<i class="icon-plus"></i>Thêm phiếu thu học phí mới
			</a>
        </div>
    </div>
     <div class="col col-lg-12 pB10">
     	<span class="fL"><b>Chủ đề đơn xin học:</b> <a href="/admin/preregisterCourse/view/id/<?php echo $preCourse->id;?>"><?php echo $preCourse->title;?></a></span>
     	<span class="fL"><a class="btn-edit mL15" href="/admin/preregisterCourse/update/id/<?php echo $preCourse->id?>" title=""></a></span>
     </div>
     <div class="col col-lg-12 pB10">
     	<?php
			$totalPaidAmount = $preCourse->getTotalPaidAmount();
			$mobiCardRemainAmount = $preCourse->getMobicardRemainPaymentAmount();
		?>
     		<div class="col col-lg-4 pL0i">
     			<span>Tiền học phí khóa học: <b><?php echo number_format($preCourse->final_price);?></b></span>
     			<?php if($preCourse->course_type==Course::TYPE_COURSE_PRESET || $preCourse->course_type==Course::TYPE_COURSE_TRAINING):?>
     				<span class="error"> (Nếu cào thẻ: <b><?php echo number_format($preCourse->getTotalFinalPrice());?>)</b></span>
     			<?php endif;?>
     		</div>
			<div class="col col-lg-4 pL0i">Tiền học thực tế đóng: <b><?php echo number_format($totalPaidAmount);?></b></div>
			<div class="col col-lg-4 pL0i">Tiền học phí còn thiếu: <b><?php echo number_format($mobiCardRemainAmount);?></b></div>
     </div>
    <?php endif;?>
</div>
<?php 
	$registration = new ClsRegistration();//New Registration class
	$createdDateFilter = Yii::app()->controller->getQuery('PreregisterPayment[payment_date]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'name'=>'transaction_id',
		   'value'=>'$data->transaction_id',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name'=>'payment_date',
		   'value'=>'date("d/m/Y, H:i", strtotime($data->payment_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="PreregisterPayment[payment_date]">',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'header'=>'Học sinh',
		   'value'=>'$data->getStudent("/admin/student/view/id")',
		   'type' => 'raw',
		   'htmlOptions'=>array('style'=>'width:150px;'),
		),
		array(
		   'name'=>'paid_amount',
		   'value'=>'number_format($data->paid_amount)',
		   'htmlOptions'=>array('style'=>'width:120px; text-align:right;'),
		),
		array(
		   'name'=>'payment_method',
		   'value'=>'$data->payment_method',
		   'htmlOptions'=>array('style'=>'width:150px;'),
		),
		array(
		   'header'=>'Đơn xin học',
		   'value'=>'$data->displayPreregisterCourse()',
		   'type' => 'raw', 'filter'=>false,
		   'htmlOptions'=>array('style'=>'width:300px;'),
		),
		array(
		   'name'=>'note',
		   'value'=>'$data->note',
		   'type'=>'raw', 'filter'=>false,
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
		),
	),
)); ?>
