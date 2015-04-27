<?php
/* @var $this StudentController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);
?>

<h2 class="mT10">Thông tin chi tiết người dùng</h2>
<?php 
	$gender = array(0=>'Nữ', 1=>'Nam');//Gender options
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
		   'name'=>'Họ và Tên',
		   'value'=>$model->fullName(),
		   'type'=>'raw',
		),		
		'email',
		array(
		   'name'=>'birthday',
		   'value'=>($model->birthday)? date('d/m/Y', strtotime($model->birthday)): "",
		),
		array(
		   'name'=>'gender',
		   'value'=>$gender[$model->gender],
		),
		'address',
		'phone',
		'created_date',
		'last_login_time',
		array(
		   'name'=>'status',
		   'value'=>$model->statusOptions($model->status),
		),
		'deleted_flag',
	),
)); ?>
<table class="detail-view">
	<tr class="even">
		<th>Kết nối với Facebook</th>
		<td>
			<?php 
				$fbUser = (UserFacebook::model()->checkConnectedFacebook($model->id));
				if($fbUser!=NULL):
			?>
			<p><span>Đã kết nối với Facebook: </span><a target="_blank" href="https://www.facebook.com/profile.php?id=<?php echo $fbUser->facebook_id;?>"><b><?php echo $fbUser->facebook_id;?></b></a></p>
			<p><b>Tên Facebook: </b><?php echo $fbUser->facebook_name;?></p> 
			<p><b>Email: </b><?php echo $fbUser->facebook_email;?></p> 
			<p><b>Username: </b><?php echo ($fbUser->facebook_username)? $fbUser->facebook_username: "Chưa tạo";?></p>
			<?php else:?>
			<span class="null">Chưa kết nối</span>
			<?php endif;?>
		</td>
	</tr>
	<tr class="odd">
		<th>Kết nối với Gmail</th>
		<td>
			<?php 
				$googleUser = (UserGoogle::model()->checkConnectedGoogle($model->id));
				if($googleUser!=NULL):
			?>
			<p><span>Đã kết nối với Gmail: </span><b><?php echo $googleUser->google_email;?></b></p>
			<p><b>Tên Gmail: </b><?php echo $googleUser->google_name;?></p>
			<?php else:?>
			<span class="null">Chưa kết nối</span>
			<?php endif;?>
		</td>
	</tr>
	<tr class="even">
		<th>Kết nối với Hocmai.vn</th>
		<td>
			<?php 
				$hmUser = (UserHocmai::model()->checkConnectedHocmai($model->id));
				if($hmUser!=NULL):
			?>
			<p><span>Đã kết nối với Hocmai.vn: </span><b><?php echo $hmUser->hocmai_username;?></b> (email: <?php echo $hmUser->hocmai_email;?>)</p>
			<p><b>Tên Hocmai: </b><?php echo $hmUser->hocmai_fullname;?></p> 
			<p><b>Điện thoại: </b><?php echo $hmUser->hocmai_phone.'; '.$hmUser->hocmai_mobile;?></p>
			<p><b>Địa chỉ: </b><?php echo $hmUser->hocmai_address;?></p>
			<?php else:?>
			<span class="null">Chưa kết nối</span>
			<?php endif;?>
		</td>
	</tr>
    <tr class="odd">
        <th>Thông tin đăng nhập cuối</th>
        <td>
            <?php $lastLoginNote = json_decode($model->last_login_note); ?>
            <p><b>Trình duyệt</b>: <?php echo isset($lastLoginNote->browser)?$lastLoginNote->browser:"Chưa xác định"; ?><p>
            <p><b>Phiên bản</b>: <?php echo isset($lastLoginNote->browser)?$lastLoginNote->version:"Chưa xác định"; ?><p>
            <p><b>Địa chỉ ip</b>: <?php echo isset($lastLoginNote->browser)?$lastLoginNote->ip:"Chưa xác định"; ?><p>
            <p><b>Hệ điều hành</b>: <?php echo isset($lastLoginNote->browser)?$lastLoginNote->os:"Chưa xác định"; ?><p>
        </td>
    </tr>
</table>
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<a href="<?php echo Yii::app()->baseUrl.'/admin/user'; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách người dùng</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>
