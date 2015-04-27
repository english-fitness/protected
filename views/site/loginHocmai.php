<style>
	#popupAll .row-form .label{width:20%;}
	#popupAll .row-form .value{width:75%;}
</style>
<div id="popupAll">
<?php if(isset($connectedSuccess) && $connectedSuccess==1):?>
	<script type="text/javascript">
		setTimeout(function() {window.close();}, 300);
	</script>
	<?php if(isset($hocmaiConnected) && $hocmaiConnected):?>
		<p class="text-center alert-success">Kết nối và đăng nhập bằng tài khoản Hocmai.vn thành công!</p>
		<?php if(isset($_GET['username']) && isset($_GET['token'])):?>
			<script type="text/javascript">
				setTimeout(function() {window.location = '<?php echo Yii::app()->baseurl.'/site/loggedRedirect'?>';}, 500);
			</script>
		<?php endif;?>
	<?php endif;?>
<?php elseif(isset($_GET['username']) && isset($_GET['token'])):?>
	<p class="text-center alert">
		<span style="color:red;">Thông tin đăng nhập bằng tài khoản Hocmai.vn không đúng, vui lòng kiểm tra lại!</span><br/><br/>
		<span>Hệ thống sẽ tự chuyển hướng về trang chủ DạyKèm123 sau 5 giây</span>
		<script type="text/javascript">
				setTimeout(function() {window.location = '<?php echo Yii::app()->baseurl.'/news';?>';}, 5000);
		</script>
	</p>
<?php else:?>	
	<h3 class="text-center mT8">Đăng nhập bằng tài khoản trên Hocmai.vn</h3>
	<div id="accountRegister">
	    <form enctype="multipart/form-data" method="post" action="<?php echo Yii::app()->baseurl;?>/login/hocmai" class="myForm" role="form" id="connectHocmai">
	    	<?php if(isset($hocmaiConnected) && !$hocmaiConnected):?>
	    	<div class="row-form">
	    		<div class="col-sm-3 label">&nbsp;</div>
	            <div class="col-sm-8 value errorMessage">Tên đăng nhập hoặc mật khẩu trên Hocmai.vn không hợp lệ!</div>
	        </div>
	        <?php endif;?>
	        <div class="row-form">
	            <div class="col-sm-3 label">Tên đăng nhập:  <span class="required">*</span></div>
	            <div class="col-sm-8 value">
	            	<?php $username = Yii::app()->request->getPost('username', '');?>
	                <input type="text" name="username" placeholder="Tên đăng nhập" value="<?php echo $username;?>"/>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">Mật khẩu:  <span class="required">*</span></div>
	            <div class="col-sm-8 value">
	            	<?php $password = Yii::app()->request->getPost('password', '');?>
	                <input type="password" name="password" placeholder="Mật khẩu" value="<?php echo $password;?>"/>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">&nbsp; </div>
	            <div class="col-sm-8 value">
	                <button type="submit"  name="save">Đăng nhập</button>
	            </div>
	        </div>
	    </form>
	</div>
	<?php endif;?>
</div>