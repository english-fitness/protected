<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Tổng hợp hàng tháng</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/DailyRecord/create">
			<i class="icon-plus"></i>Thêm bản ghi hàng ngày
			</a>
        </div>
    </div>
</div>
<?php 
	$monthFilter = Yii::app()->controller->getQuery('TeacherPayment[month]', '');
	$teacherFullname = Yii::app()->controller->getQuery('TeacherPayment[teacher_fullname]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
			'name'=>'id',
			'value'=>'$data->id',
			'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:60px;'),
		),
		array(
			'name'=>'teacher_id',
			'value'=>'$data->getTeacherLink()',
			'filter'=>'<input type="text" value="'.$teacherFullname.'" name="TeacherPayment[teacher_fullname]">',
			'htmlOptions'=>array('style'=>'width:200px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:200px;'),
			'type'=>'raw',
		),
		array(
			'name'=>'month',
			'value'=>'date("m-Y", strtotime($data->month))',
			'filter'=>'<input type="text" value="'.$monthFilter.'" name="TeacherPayment[month]">',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'name'=>'total_platform_session',
			'value'=>'$data->total_platform_session . " buổi"',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:90px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:90px;'),
		),
		array(
			'name'=>'total_non_platform_session',
			'value'=>'$data->total_non_platform_session . " buổi"',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:90px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:90px;'),
		),
		array(
			'header'=>'Tổng',
			'value'=>'($data->total_platform_session + $data->total_non_platform_session) . " buổi"',
			'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		),
		array(
			'header'=>'Số ngày',
			'value'=>'CHtml::link($data->countDays()." ngày", Yii::app()->createUrl("admin/TeacherPayment/update/id/$data->id"))',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		),
		array(
			'name'=>'payment_status',
			'value'=>'$data->getPaymentStatus()',
			'filter'=>TeacherPayment::paymentStatusOptions(),
			'htmlOptions'=>array('style'=>'width:155px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:155px;'),
		),
		array(
			'name'=>'payment_date',
			'value'=>'(empty($data->payment_date)) ? "" : date("d-m-Y", strtotime($data->payment_date))',
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'name'=>'report_status',
			'value'=>'$data->getStatus()',
			'filter'=>TeacherPayment::statusOptions(),
			'htmlOptions'=>array('style'=>'width:150px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:150px;'),
		),
		array(
			'name'=>'report_date',
			'value'=>'(empty($data->report_date)) ? "" : date("d-m-Y", strtotime($data->report_date))',
			'htmlOptions'=>array('style'=>'text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
    		),
			'htmlOptions'=>array('style'=>'width:50px;'),
			'headerHtmlOptions'=>array('style'=>'width:60px;'),
		),
	),
)); ?>
