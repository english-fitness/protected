
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Chi tiết tin nhắn</h2>
    </div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
		   'name'=>'sender_id',
		   'value'=>$model->getUser()->fullName(),
		),
		'title',
		array(
            'name'=>'Nội dung',
            'value'=>$model->content,
			'type'=>'raw',
        ),
        array(
            'name'=>'created_date',
            'value'=>Common::formatDatetime($model->created_date),
        ),
        array(
		   'name'=>'modified_date',
		   'value'=>($model->modified_date)? date('d/m/Y H:i', strtotime($model->modified_date)):"",
		),
		array(
		   'name'=>'modified_user_id',
		   'value'=>($model->modified_user_id)? User::model()->displayUserById($model->modified_user_id):"",
		),
        array(
            'name'=>'Số người nhận',
            'value'=>$model->countRecipient()." người ",
        ),
        array(
            'name'=>'Số người đã đọc',
            'value'=>$model->countRecipient(1)." người ",
        ),
		'deleted_flag',
	),
)); ?>
