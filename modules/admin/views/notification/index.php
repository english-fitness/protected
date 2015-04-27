<?php
/* @var $this NotificationController */
/* @var $model Notification */

$this->breadcrumbs=array(
	'Notifications'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php 
    		$pageTitle = 'Quản lý thông báo hệ thống';
    		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
    			$pageTitle = 'Thông báo đã bị xóa/hủy';
    		}
    	?>
        <h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/notification/create">
			<i class="icon-plus"></i>Thêm thông báo mới
			</a>
        </div>
    </div>
</div>
<?php if(!(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1)):?>
<div class="form-element-container row">
	<div class="col col-lg-12">
      <a href="<?php echo Yii::app()->baseUrl; ?>/admin/notification?deleted_flag=1"><span class="trash"></span>&nbsp;Danh sách thông báo đã bị xóa/hủy</a>
    </div>
</div>
<?php endif;?>
<?php 
	$createdDateFilter = Yii::app()->controller->getQuery('Notification[created_date]', '');
	$receiverEmailFilter = Yii::app()->controller->getQuery('Notification[receiver_id]', '');
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
		   'name'=>'content',
		   'value'=>'$data->content',
		   'type'=>'raw',
		),		
		array(
		   'name'=>'created_date',
		   'value'=>'date("d/m/Y H:i", strtotime($data->created_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="Notification[created_date]">',
		   'htmlOptions'=>array('style'=>'width:130px;'),
		),
		array(
		   'header'=>'Người nhận',
		   'value'=>'$data->displayReceivedUser()',
		   'filter'=>'<input type="text" value="'.$receiverEmailFilter.'" name="Notification[receiver_id]" placeholder="email người nhận">',
		   'htmlOptions'=>array('style'=>'width:250px;'),	
		),
		array(
		   'header'=>'Đã đọc chưa?',
		   'value'=>'$data->displayConfirmedUsers($data->confirmed_ids)',
		   'htmlOptions'=>array('style'=>'width:125px;'),	
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL15' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
		),
	),
)); ?>
