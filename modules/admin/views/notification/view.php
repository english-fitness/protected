<?php
/* @var $this NotificationController */
/* @var $model Notification */

$this->breadcrumbs=array(
	'Notifications'=>array('index'),
	$model->id,
);
?>
<h1>Chi tiết thông báo</h1>
<?php
	$confirmedLinks = "";
	if(trim($model->confirmed_ids)!=""){
		$readIds = explode(',', $model->confirmed_ids);
		foreach($readIds as $key=>$uid){
			if($key<100){
				if(trim($uid)!=""){
					$confirmedLinks .= '<a href="/admin/user/view/id/'.$uid.'">'.$uid.'</a>,';
				}
			}else{
				$confirmedLinks .= '...';
			}
		}
	}
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
		   'name'=>'receiver_id',
		   'value'=>$model->getReceivedUser()->email,
		),
		array(
		   'name'=>'Mã xác nhận',
		   'value'=>$confirmedLinks,
		   'type'=>'raw',
		),
		array(
		   'name'=>'content',
		   'value'=>$model->content,
		   'type'=>'raw',
		),
		'link',
		'notification_type',		
		array(
		   'name'=>'created_user_id',
		   'value'=>($model->created_user_id)? User::model()->displayUserById($model->created_user_id):"",
		),
		array(
		   'name'=>'created_date',
		   'value'=>($model->created_date)? date('d/m/Y H:i', strtotime($model->created_date)):"",
		),
		array(
		   'name'=>'modified_user_id',
		   'value'=>($model->modified_user_id)? User::model()->displayUserById($model->modified_user_id):"",
		),		
		array(
		   'name'=>'modified_date',
		   'value'=>($model->modified_date)? date('d/m/Y H:i', strtotime($model->modified_date)):"",
		),
		'deleted_flag',
	),
)); ?>
