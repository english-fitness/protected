<h4 class="page-title mT10">Danh sách người nhận tin nhắn: <a href="<?php echo Yii::app()->baseurl.'/admin/message/view/id/'.$message->id; ?>"><?php echo $message->title; ?></a> </h4>

<div class="content"><h4>Nội dung tin nhắn: </h4><?php echo $message->content; ?></div>

<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
    'columns'=>array(
        array(
            'header'=>'Họ tên',
            'value'=>'$data->displayReceivedUser()',
            'type'=>'raw',
        ),
        array(
            'name'=>'read_flag',
            'value'=>'$data->getStatusLabel()',
            'type'=>'raw',
            'htmlOptions'=>array("style"=>"150px;")
        ),
        array(
            'name'=>'read_date',
            'value'=>'($data->read_date)?Common::formatDatetime($data->read_date):""',
            'type'=>'raw',
            'htmlOptions'=>array("style"=>"150px;")
        ),
        array(
            'class'=>'CButtonColumn',
            'buttons'=>array (
                'update'=> array('label'=>'', 'imageUrl'=>'',
                    'options'=>array( 'class'=>'dpn' ),
                ),
                'view'=>array(
                    'label'=>'', 'imageUrl'=>'',
                    'options'=>array( 'class'=>'dpn' ),
                ),
                'delete'=>array(
                    'label'=>'', 'imageUrl'=>'',
                    'options'=>array( 'class'=>'btn-remove' ),
                ),
            ),
        ),
    ),
)); ?>
