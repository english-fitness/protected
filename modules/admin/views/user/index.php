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
        <h2 class="page-title mT10">Quản lý người dùng hệ thống</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/user/create">
			<i class="icon-plus"></i>Thêm người dùng
			</a>
        </div>
    </div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-12">
      <a href="<?php echo Yii::app()->baseUrl; ?>/admin/user/deletedUser"><span class="trash"></span>Người dùng đã bị xóa(học sinh, giáo viên...)</a>
    </div>
</div>
<?php 
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
		'email',
		array(
		   'name'=>'birthday',
		   'value'=>'($data->birthday)? date("d/m/Y", strtotime($data->birthday)): ""',
		   'filter'=>'<input type="text" value="'.$birthdayFilter.'" name="User[birthday]">',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'name' => 'phone',
		   'value'=>'$data->displayContactIcons()', 
		),
		array(
		   'name' => 'role',
		   'value'=>'$data->role', 
		),
		array(
		   'header' => 'Bảng phân quyền',
		   'value'=>'CHtml::link("Bảng phân quyền", Yii::app()->createUrl("admin/permission/user/id/$data->id"))',
		   'type' => 'raw',
		),
		/*
		'gender',
		'address',
		'profile_picture',
		'about',		
		'created_date',
		'last_login_time',
		'status',
		'activation_code',
		'activation_expired',
		*/
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