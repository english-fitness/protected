<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Người dùng đã bị xóa</h2>
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
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(		
		'lastname',
		'firstname',
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
		'role',
		array(
		   'header' => 'Trạng thái',
		   'value'=>'$data->statusOptions($data->status)',
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