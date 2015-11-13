<style type="text/css">
	/* notable */
	.notable{
		color: darkviolet;
		background-color: lightblue !important;
		font-weight: bold;
	}
	.notable a{
		color: darkviolet;
	}
	.notable:hover{
		color: blue;
		background-color: #BCE774 !important;
	}
	.notable:hover a{
		color: blue;
	}
	.notable.selected{
		color: white;
		background-color: #245ba7 !important;
	}
	.notable.selected a{
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
	a.duplicate{
		color: black;
		text-decoration: none;
	}
	a.duplicate:hover{
		color: darkviolet;
		text-decoration: underline;
	}
</style>
<?php 
	$createdDateFilter = Yii::app()->controller->getQuery('PreregisterUser[created_date]', '');

	function setHighlightClass($status){
		switch ($status) {
			case PreregisterUser::CARE_STATUS_LATER:
				return array("class"=>"notable");
				break;
			case PreregisterUser::CARE_STATUS_REGISTERED:
				return array("class"=>"registeredUser");
				break;
			case PreregisterUser::CARE_STATUS_SCHEDULED:
				return array("class"=>"scheduledUser");
				break;
			default:
				return array();
				break;
		}
	}

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
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'gridView',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'setHighlightClass($data->care_status)',
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
			'template'=>'{update}',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit' ),
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
