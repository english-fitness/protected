<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Teacher Penalty Charge Records</h2>
    </div>
</div>
<?php 
	$teacherFullname = Yii::app()->controller->getQuery('TeacherFineCharge[teacher_fullname]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->with("teacher")->search(null, 't.id desc'),
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
			'value'=>'$data->teacher->getViewLink()',
			'htmlOptions'=>array('style'=>'width:200px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:200px;'),
			'type'=>'raw',
		),
		array(
			'name'=>'points',
			'value'=>'$data->points',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'header'=>'Số buổi học bị trừ',
			'value'=>'TeacherFineCharge::getNumberOfSessionDeducted($data->points)',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'name'=>'created_date',
			'value'=>'date("d-m-Y", strtotime($data->created_date))',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
	),
)); ?>
