<style type="text/css">
	/* working */
	.working{
		color: darkviolet;
		background-color: lightblue !important;
		font-weight: bold;
	}
	.working a{
		color: darkviolet;
	}
	.working:hover{
		color: blue;
		background-color: #BCE774 !important;
	}
	.working:hover a{
		color: blue;
	}
	.working.selected{
		color: white;
		background-color: #245ba7 !important;
	}
	.working.selected a{
		color: white;
	}
	/* registeredUser */
	.registeredUser{
		color: crimson;
		background-color: lightblue !important;
		font-weight: bold;
	}
	.registeredUser a{
		color: crimson;
	}
	.registeredUser:hover{
		color: blue;
		background-color: #BCE774 !important;
	}
	.registeredUser:hover a{
		color: blue;
	}
	.registeredUser.selected{
		color: white;
		background-color: #245ba7 !important;
	}
	.registeredUser.selected a{
		color: white;
	}
	tr:not(.notable):not(.registeredUser) a.duplicate{
		color: black;
		text-decoration: none;
	}
	a.duplicate:hover{
		color: darkviolet !important;
		text-decoration: underline !important;
	}
</style>
<?php 
	$createdDateFilter = Yii::app()->controller->getQuery('PreregisterUser[created_date]', '');

	function getPhoneDisplay($phone, $duplicate){
		$formatted = Common::formatPhoneNumber($phone);
		if ($duplicate){
			return '<a class="duplicate" href="/admin/preregisterUser/index?PreregisterUser[phone]='.$phone.'" title="Số điện thoại đã được đăng ký">'.
						$formatted.'*'.
					'</a>';
		}
		return $formatted;
	}

	function getEmailDisplay($email, $duplicate){
		if ($duplicate){
			return '<a class="duplicate" href="/admin/preregisterUser/index?PreregisterUser[email]='.$email.'" title="Email đã được đăng ký">'.
						$email.'*'.
					'</a>';
		}
		return $email;
	}

	function setHighlight($status){
		switch ($status) {
			case PreregisterUser::CARE_STATUS_L3:
				return "working";
				break;
			case PreregisterUser::CARE_STATUS_L4:
				return "registeredUser";
				break;
			default:
				break;
		}
	}
?>
<?php $this->renderPartial('careStatusGuide')?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'gridView',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'array("class"=>setHighlight($data->care_status))',
	'columns'=>array(
		'fullname',
        array(
            'name'=>'phone',
            'value'=>function($data){
            	return getPhoneDisplay($data->phone, $data->phoneDuplicate);
            },
            'htmlOptions'=>array('style'=>'width:100px;'),
            'type'=>'raw'
        ),
        array(
        	'name'=>'email',
        	'value'=>function($data){
            	return getEmailDisplay($data->email, $data->emailDuplicate);
            },
            'type'=>'raw',
    	),
		'promotion_code',
        array(
            'name'=>'source',
            'value'=>'$data->source',
            'filter'=>PreregisterUser::allowableSource(),
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
			'filter'=>PreregisterUser::careStatusFilter(true, Yii::app()->controller->getQuery('PreregisterUser[care_status]')),
			'htmlOptions'=>array("style"=>"width:135px;text-align:center;"),
		),
		array(
			'name'=>'sale_user_id',
			'value'=>'($data->sale_user_id)? User::model()->displayUserById($data->sale_user_id):""',
			'filter'=>Student::model()->getSalesUserOptions(false, "", false),
			'htmlOptions'=>array('style'=>'width:150px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit'),
		        ),
    		),
            'headerHtmlOptions'=>array('style'=>'width:25px'),
            'htmlOptions'=>array('style'=>'width:25px;'),
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