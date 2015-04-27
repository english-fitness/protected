<script>
	//Refuse precourse ajax
	function markRead(id){
		var data = {'messageId': id};
		var checkConfirm = confirm("Bạn có chắc chắn muốn xác nhận đã đọc & xử lý tin nhắn này?");
			if(checkConfirm){
			$.ajax({
				url: daykemBaseUrl + "/admin/message/ajaxMarkRead",
				type: "POST", dataType: 'json', data:data,
				success: function(data) {
					if(data.success){
						$('#messageReadFlag'+id).html('Đã xử lý');
					}
				}
			});
		}
	}
</script>
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
		<?php $deletedTitle = "Danh sách tin nhắn đến đã xóa/hủy";?>
      <a href="<?php echo Yii::app()->baseUrl."/admin/message/inbox?deleted_flag=1"; ?>"><span class="trash"></span>&nbsp;<?php echo $deletedTitle;?></a>
    </div>
</div>
<?php endif;?>
<?php 
	$createdDateFilter = Yii::app()->controller->getQuery('Message[created_date]', '');
?>
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
            'value'=>'"<b>".$data->getLinkUserByAdminPage(true)."</b><br/><b>Tiêu đề tin: </b>".CHtml::link($data->title, Yii::app()->createUrl("admin/message/view/id/$data->id"))',
        	'type'=>'raw',
        	'htmlOptions'=>array('style'=>'width:350px;'),
        ),        
        array(
            'name'=>'content',
            'value'=>'$data->content',
            'type'=>'raw'
        ),
        array(
            'name'=>'created_date',
            'value'=>'Common::formatDatetime($data->created_date)',
         	'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="Message[created_date]">',
         	'htmlOptions'=>array('style'=>'width:120px;'),
        ),
        array(
            'header'=>'Đọc & xử lý tin',
            'value'=>'ClsAdminHtml::displayInboxMessageStatus($data->id, $data->countRecipient(1))',
        	'htmlOptions'=>array('style'=>'width:150px;'),
            'type'=>'raw'
        ),
        array(
            'header'=>'Người xử lý cuối',
            'value'=>'($data->modified_user_id)? User::model()->displayUserById($data->modified_user_id):""',
        	'htmlOptions'=>array('style'=>'width:100px;'),
            'type'=>'raw'
        ),        
        array(
		   'header'=>'Trả lời',
		   'value'=>'CHtml::link("<b>Trả lời</b>", "/admin/message/create?uid=".$data->sender_id."&msgId=".$data->id, array())',
		   'filter'=>false, 'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
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