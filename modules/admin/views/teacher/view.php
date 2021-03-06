<?php
FileAsset::register($this);
/* @var $this TeacherController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	$model->id,
);
?>
<h2 class="mT10">Chi tiết giáo viên</h2>
<?php 
	$gender = array(0=>'Chưa xác định', 1=>'Nữ', 2=>'Nam');//Gender options
	$tokenCode = sha1($model->id.$model->role.$model->username);
	$loginByUrl = Yii::app()->getRequest()->getBaseUrl(true)."/login/byUrl?username=".$model->username."&token=".$tokenCode;
?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
		   'name'=>'Họ và Tên',
		   'value'=>$model->fullName(),
		   'type'=>'raw',
		),
		'username',
		'email',
		'phone',
		array(
		   'name'=>'Tiêu đề',
		   'value'=>$teacherProfile->title,
		   'type'=>'raw',
		),
		array(
		   'name'=>'Môn dạy gia sư',
		   'value'=>$teacherProfile->displayAbilitySubjects(),
		),
		array(
		   'name'=>'Khóa học được gán',
		   'value'=>Teacher::model()->displayCourseLink($model->id),
		   'type'=>'raw',
		),
		array(
		   'name'=>'birthday',
		   'value'=>($model->birthday)? date('d/m/Y', strtotime($model->birthday)):"",
		),		
		array(
		   'name'=>'gender',
		   'value'=>$gender[$model->gender],
		),
		'address',		
		array(
		   'name'=>'Mô tả ngắn',
		   'value'=>$teacherProfile->short_description,
		   'type'=>'raw',
		),
		array(
		   'name'=>'Mô tả đầy đủ',
		   'value'=>$teacherProfile->description,
		   'type'=>'raw',
		),
		array(
		   'name'=>'last_login_time',
		   'value'=>($model->last_login_time)? date('d/m/Y H:i', strtotime($model->last_login_time)):"",
		),
		array(
		   'name'=>'status',
		   'value'=>$model->statusOptions($model->status),
		),
		array(
		   'name'=>'Tài liệu',
		   'value'=>'<a href="'.Yii::app()->createUrl('/media/js/filemanager/dialog.php?user_id='.$model->id).'" class="view-document-from-teacher">Bấm để xem</a>',
		   'type'=>'raw',
		),
		array(
		   'name'=>'Đăng nhập từ URL',
		   'value'=>(Yii::app()->user->isAdmin())?$loginByUrl:"Bạn ko được phép xem!",
		),
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
			<p><span>Đã kết nối với Gmail: </span><?php echo $googleUser->google_email;?></p>
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
		<a href="<?php echo Yii::app()->baseUrl.'/admin/teacher'; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách giáo viên</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>



<script type="text/javascript">
	$(document).ready(function() {
		$(".view-document-from-teacher").fancybox({
            'width'		: 900,
            'height'	: 600,
            'type'		: 'iframe',
            'autoScale'    	: false
        });
	});
</script>






