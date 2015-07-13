<?php
FileAsset::register();
/* @var $this TeacherController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Quản lý giáo viên</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/teacher/create">
			<i class="icon-plus"></i>Thêm giáo viên
			</a>
        </div>
    </div>
</div>
<?php 
	$statusOptions = $model->statusOptions(); unset($statusOptions['-1']);
	$createdDateFilter = Yii::app()->controller->getQuery('User[created_date]', '');
	$birthdayFilter = Yii::app()->controller->getQuery('User[birthday]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'name'=>'firstname',
		   'value'=>'$data->fullName()',
		),
		array(
		   'name'=>'username',
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		'email',
		/*
		array(
		   'name'=>'birthday',
		   'value'=>'($data->birthday)? date("d/m/Y", strtotime($data->birthday)): ""',
		   'filter'=>'<input type="text" value="'.$birthdayFilter.'" name="User[birthday]">',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),*/
		array(
		   'name' => 'phone',
		   'value'=>'$data->displayContactIcons()', 
		),
		/*
		array(
		   'header'=>'Môn dạy gia sư',
		   'value'=>'Teacher::model()->displayAbilitySubjects($data->id)',
		   'htmlOptions'=>array('style'=>'width:250px;'),
		),
		*/
		array(
		   'name'=>'status',
		   'value'=>'($data->statusOptions($data->status))',
		   'filter'=>$statusOptions,
		   'htmlOptions'=>array('style'=>'width:135px;'),
		),
		array(
		   'header' => 'Khóa học',
		   'value'=>'Teacher::model()->displayCourseLink($data->id)',
		   'type'=>'raw',
		),
		/*
		'gender',
		'address',
		'profile_picture',
		'about',
		'role',
		'created_date',
		'last_login_time',
		'status',
		'activation_code',
		'activation_expired',
		*/
        array(
            'header' => 'Tài liệu',
            'value'=>'CHtml::link(
                "xem tài liệu",
                array("/media/applications/filemanager/main/dialog.php?user_id=".$data->id),
                array("class"=>"view-document-from-teacher"))',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'width:50px;'),
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
	),
)); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $(".view-document-from-teacher").fancybox({
            'width'		: 900,
            'height'	: 600,
            'type'		: 'iframe',
            'autoScale'    	: false
        });
    });
</script>