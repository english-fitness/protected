<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Phân quyền người dùng</title>
</head>
<body>
	<h2>Bảng phân quyền dành cho bạn</h2><br/>
	<?php if($user->role==User::ROLE_ADMIN):?>
		<h3 class="text-center error">ADMIN có tất cả các quyền truy cập cơ bản!</h3>
	<?php else:?>
		<?php if(count($permissions)>0):?>
		<div class="form-element-container row pB10 pL10 borderGrey">
			<?php foreach($permissions as $permission):?>	
			<div class="col col-lg-3 pA5">
				<?php $checked = "";			
					if(isset($user) && in_array($permission->id, $assignedPermissionIds)){
						$checked = 'checked="checked"';
					}
				?>
				<input type="checkbox" name="Permission[]" value="<?php echo $permission->id;?>" <?php echo $checked;?> disabled="disabled"/>
				<span <?php echo ($checked!="")? 'class="error"': "";?>><?php echo $permission->title;?></span>
			</div>	
			<?php endforeach;?>
		</div>
		<?php endif;?>
	<?php endif;?>	
<div class="clearfix h30">&nbsp;</div>	
</body>
</html>