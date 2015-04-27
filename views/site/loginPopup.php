<div id="popupAll">
<?php if(isset($connectedSuccess) && $connectedSuccess==1):?>
	<script type="text/javascript">
		setTimeout(function() {window.close();}, 300);
	</script>
	<?php if(isset($facebookConnected) && $facebookConnected):?><p class="text-center alert-success">Kết nối & đăng nhập bằng Facebook thành công!</p><?php endif;?>
	<?php if(isset($googleConnected) && $googleConnected):?><p class="text-center alert-success">Kết nối & đăng nhập bằng Gmail thành công!</p><?php endif;?>
<?php endif;?>
</div>