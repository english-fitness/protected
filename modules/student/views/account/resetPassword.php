<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->baseUrl; ?>/media/css/base/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/bootstrap/css/bootstrap.min.css" />
<?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>

<style type="text/css">
	.form-label{
		float:left;
		width:150px;
		margin-bottom: 5px;
	    line-height: 38px;
		font-size: 16px;
	}
	form label{
		color: #245ba7;
	}
	.input{
		float:left;
		margin-bottom: 5px;
	}
	.notice{
		text-align: center;
		height: 40px;
		color:red;
	}
	.logout{
		position:absolute;
		top:5px;
		right:10px;
	}
</style>
<div style="position:relative;height:100vh">
	<div class="logout">
		<a href="/site/logout">Thoát</a>
	</div>
	<div style="margin:0 auto; position:relative; top:18%; height:100px; width:500px">
		<div style="text-align:center">
			<img src="/media/images/logo/logo.png" style="height:80px">
			<h3>Chào mừng bạn đến với Speakup.vn</h3>
			<h4>Bạn cần thay đổi mật khẩu của mình trước khi tiếp tục</h4>
		</div>
		<?php if (isset($notice)):?>
		<div class="notice"><span><?php echo $notice?></span></div>
		<?php else:?>
		<div class="notice"></div>
		<?php endif;?>
		<div style="margin:0 auto; width:400px;">
			<form id="pw-form" method="post" action="/student/account/resetPassword">
				<div class="form-label">
					<label>Mật khẩu mới</label>
				</div>
				<div class="input">
					<input type="password" id="password" name="password">
				</div>
				<div style="clear:both"></div>
				<div class="form-label">
					<label>Nhập lại mật khẩu</label>
				</div>
				<div class="input">
					<input type="password" id="repeadPassword" name="repeatPassword">
				</div>
				<div style="clear:both"></div>
				<div style="margin-top:10px">
					<div class="form-label"></div>
					<div class="input">
						<button class="btn btn-primary">Tiếp tục</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#pw-form").submit(function(){
		if ($("#password").val() == "" || $("#repeatPassword").val() == ""){
			$(".notice").html("<span>Hãy điền đầy đủ thông tin cần thiết<span>");
			return false;
		}
	});
</script>