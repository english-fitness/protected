<?php $this->renderPartial('/messages/tab'); ?>
<?php $this->renderPartial('accountTab'); ?>
<div class="form">
    <div class="account">
        <?php $form=$this->beginWidget('CActiveForm',array(
            "htmlOptions"=>array(
                "class"=>"myForm"
            ),
            "action"=>"/student/account/socialNetwork"
        )); ?>
        <div class="row-form">
        	<div class="label">Bạn có thể đăng nhập bằng Facebook, Gmail, Hocmai.vn...Nếu bạn kết nối tài khoản của bạn với Facebook, Gmail, Hocmai.vn...</div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Kết nối với Facebook: </div>
            <div class="value col-sm-7">
                <?php 
                	$fbUser = UserFacebook::model()->checkConnectedFacebook($user->id);
                	if($fbUser!=NULL):
                ?>
                <p>Trạng thái: <b class="alert-success">Đã kết nối!</b></p>
                <p>Facebook: <b><?php echo $fbUser->facebook_name;?></b> (ID: <?php echo $fbUser->facebook_id;?>)</p>
                <p><a target="_blank" href="https://www.facebook.com/profile.php?id=<?php echo $fbUser->facebook_id;?>"><b style="color:#325DA7">[Xem Facebook đã kết nối]</b></a></p>
				<?php else:?>
				<p>Trạng thái: <b class="error">Chưa kết nối!</b></p>
				<div class="btn-facebook-connect" onclick="javascript:oauthConnectPopup('<?php echo Yii::app()->baseUrl."/student/account/connectFacebook";?>', 760, 480)">Kết nối với Facebook</div>
				<?php endif;?>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Kết nối với Gmail: </div>
            <div class="value col-sm-7">
                <?php 
					$googleUser = (UserGoogle::model()->checkConnectedGoogle($user->id));
					if($googleUser!=NULL):
				?>
				<p>Trạng thái: <b class="alert-success">Đã kết nối!</b></p>
				<p>Tài khoản Gmail: <b><?php echo $googleUser->google_name;?></b> (email: <?php echo $googleUser->google_email;?>)</p>
				<?php else:?>
				<p>Trạng thái: <b class="error">Chưa kết nối!</b></p>
				<div class="btn-google-connect" onclick="javascript:oauthConnectPopup('<?php echo Yii::app()->baseUrl."/student/account/connectGoogle";?>', 760, 480)">Kết nối với Gmail</div>
				<?php endif;?>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Kết nối với Hocmai.vn: </div>
            <div class="value col-sm-7">
                <?php 
					$hmUser = (UserHocmai::model()->checkConnectedHocmai($user->id));
					if($hmUser!=NULL):
				?>
				<p>Trạng thái: <b class="alert-success">Đã kết nối!</b></p>
				<p>Tài khoản Hocmai.vn: <b><?php echo $hmUser->hocmai_username;?></b> (email: <?php echo $hmUser->hocmai_email;?>)</p>
				<?php else:?>
				<p>Trạng thái: <b class="error">Chưa kết nối!</b></p>
				<div class="btn-hocmai-connect" onclick="javascript:oauthConnectPopup('<?php echo Yii::app()->baseUrl."/student/account/connectHocmai";?>', 580, 320)">Kết nối với Hocmai.vn</div>
				<?php endif;?>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>

</div>