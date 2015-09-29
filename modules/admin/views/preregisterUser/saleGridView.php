<?php 
	$createdDateFilter = Yii::app()->controller->getQuery('PreregisterUser[created_date]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'gridView',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(
		'fullname',
        array(
            'name'=>'phone',
            'value'=>'$data->phone',
            'htmlOptions'=>array('style'=>'width:100px;'),
        ),
		'email',
		'promotion_code',
        array(
            'name'=>'source',
            'value'=>'$data->source',
            'filter'=>PreregisterUser::getSelectFilter('source'),
            'htmlOptions'=>array('style'=>'min-width:100px;'),
        ),
		array(
		   'name'=>'created_date',
		   'value'=>'date("d/m/Y, H:i", strtotime($data->created_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="PreregisterUser[created_date]">',
		   'htmlOptions'=>array('style'=>'width:110px;'),
		),
		array(
		   'name'=>'care_status',
		   'value'=>'$data->careStatusOptions($data->care_status)',
		   'filter'=>$model->careStatusOptions(),
		   'htmlOptions'=>array('style'=>'width:135px;'),
		),
		array(
		   'name'=>'sale_user_id',
		   'value'=>'($data->sale_user_id)? User::model()->displayUserById($data->sale_user_id):""',
           'filter'=>Student::model()->getSalesUserOptions(false, "", false),
		   'htmlOptions'=>array('style'=>'width:150px;'),
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
            'headerHtmlOptions'=>array('style'=>'width:60px'),
            'htmlOptions'=>array('style'=>'width:60px;'),
		),
		array(
		   'header'=>'Tư vấn',
		   'value'=>'CHtml::link("Tư vấn", "/admin/preregisterUser/saleUpdate/id/".$data->id, array("class"=>"icon-plus pL20", "style"=>"width:60px;"))',
		   'filter'=>false, 'type'  => 'raw',
           'headerHtmlOptions'=>array('style'=>'width:60px'),
		   'htmlOptions'=>array('style'=>'width:60px;'),
		),
	),
)); ?>
