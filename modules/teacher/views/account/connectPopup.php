<div id="popupAll">
<?php if(isset($connectedSuccess) && $connectedSuccess==1):?>
	<script type="text/javascript">
		setTimeout(function() {window.close();}, 300);
	</script>
	<?php if(isset($facebookConnected) && $facebookConnected):?><p class="text-center alert-success">Kết nối Facebook thành công!</p><?php endif;?>
	<?php if(isset($googleConnected) && $googleConnected):?><p class="text-center alert-success">Kết nối Gmail thành công!</p><?php endif;?>
<?php else:?>
	<?php if(isset($facebookConnected)):?>
		<p class="text-center mT10" style="color:red;">Kết nối Facebook không thành công & hoặc tài khoản Facebook này đã được kết nối với người dùng khác trong hệ thống!</p>
	<?php endif;?>
	<?php if(isset($googleConnected)):?>
		<p class="text-center mT10" style="color:red;">Kết nối Gmail không thành công & hoặc tài khoản Gmail này đã được kết nối với người dùng khác trong hệ thống!</p>
	<?php endif;?>
<?php endif;?>
</div>
