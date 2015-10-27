<?php
/* @var $this TeacherController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Quản lý học sinh</h2>
    </div>
    <?php if(Yii::app()->user->isAdmin()):?>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/student/create">
			<i class="icon-plus"></i>Thêm học sinh
			</a>
        </div>
    </div>
    <?php endif;?>
</div>
<?php 
	$createdDateFilter = Yii::app()->controller->getQuery('User[created_date]', '');
	$birthdayFilter = Yii::app()->controller->getQuery('User[birthday]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->with(array(
		'student'=>array(
			'select'=>array('preregister_id', 'official_start_date'),
			'together'=>true,
		),
		'student.preregisterUser'=>array(
			'select'=>array('source'),
			'together'=>true,
		)
	))->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'name'=>'id',
		   'value'=>'$data->id',
		   'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
		),
		array(
		   'name'=>'firstname',
		   'value'=>'$data->fullName()',
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		array(
		   'name'=>'username',
		   'htmlOptions'=>array('style'=>'width:150px;'),
		),
		'email',
		array(
		   'name' => 'phone',
		   'value'=>'Common::formatPhoneNumber($data->phone)',
		   'htmlOptions'=>array('style'=>'width:110px;padding-left:5px'),
		),
		array(
			'header'=>'Nguồn',
			'name'=>'source',
			'filter'=>PreregisterUser::allowableSource(Yii::app()->controller->getQuery('User[source]')),
			'value'=>'@$data->student->preregisterUser->source',
			'htmlOptions'=>array('style'=>'text-align:center'),
		),
		array(
		   'name'=>'status',
		   'value'=>'($data->statusOptions($data->status))',
		   'filter'=>Student::filterOptions(),
		   'htmlOptions'=>array('style'=>'width:130px;text-align:center'),
		),
        array(
            'header'=>'Học viên chính thức từ ngày',
            'value'=>'!empty($data->student->official_start_date) ? date("d-m-Y", strtotime($data->student->official_start_date)) : ""',
            'htmlOptions'=>array('style'=>'text-align:center;width:90px'),
        ),
		array(
		   	'value'=>'CHtml::link("Quản lý", "/admin/student/manage/sid/".$data->id)',
		   	'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
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
		            'options'=>array( 'class'=>'btn-view' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
            'htmlOptions'=>array('style'=>'width:60px;'),
            'headerHtmlOptions'=>array('style'=>'width:60px;'),
		),
		array(
		   'header'=>'Tư vấn',
		   'value'=>'CHtml::link("Tư vấn", "/admin/student/saleUpdate/id/".$data->id, array("class"=>"icon-plus pL20", "style"=>"width:60px;"))',
		   'filter'=>false, 'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:60px;'),
		),
       
	),
)); ?>