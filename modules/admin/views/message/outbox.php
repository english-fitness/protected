<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1) $title.= " (đã xóa/hủy)";?>
        <h2 class="page-title mT10"><?php  echo $title;?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/message/create">
                <i class="icon-plus"></i>Thêm tin nhắn mới
            </a>
        </div>
    </div>
</div>
<?php if(!(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1)):?>
<div class="form-element-container row">
	<div class="col col-lg-12">
		<?php $deletedTitle = "Tin nhắn gửi đi (đã xóa/hủy)";?>
      <a href="<?php echo Yii::app()->baseUrl."/admin/message/outbox?deleted_flag=1"; ?>"><span class="trash"></span>&nbsp;<?php echo $deletedTitle;?></a>
    </div>
</div>
<?php endif;?>
<?php $createdDateFilter = Yii::app()->controller->getQuery('Message[created_date]', '');?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->search(),
    'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
    'columns'=>array(
        array(
            'name'=>'title',
            'value'=>'"<b>Người gửi: ".$data->getUser()->fullName()."</b><br/><b>Tiêu đề tin: </b>".CHtml::link($data->title, Yii::app()->createUrl("admin/message/view/id/$data->id"))',
        	'type'=>'raw',
        	'htmlOptions'=>array('style'=>'width:300px;'),
        ),        
        array(
            'name'=>'content',
            'value'=>'$data->content',
            'type'=>'raw'
        ),
        array(
		   'header' => 'Danh sách người nhận',
		   'value'=>'implode(", ", $data->getRecipientsInMessage("/admin/user/view/id"))',
		   'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'min-width:250px; max-width:400px;'),
		),
		array(
		   'header' => 'Đã đọc',
		   'value'=>'CHtml::link($data->countRecipient(1)."/".$data->countRecipient(), Yii::app()->createUrl("admin/messageStatus/index/id/$data->id"))',
		   'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		),
        array(
            'name'=>'created_date',
            'value'=>'Common::formatDatetime($data->created_date)',
         	'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="Message[created_date]">',
         	'htmlOptions'=>array('style'=>'width:120px;'),
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