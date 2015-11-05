<?php
/* @var $this PermissionController */
/* @var $model Permission */

$this->breadcrumbs=array(
	'Permissions'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Các quyền truy cập hệ thống</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
    	<span class="fR mT10">
        	<a href="<?php echo Yii::app()->baseUrl; ?>/admin/permission/manage"><span class="btn-edit"></span>&nbsp;<b>Quản lý quyền truy cập hệ thống</b></a>
        </span>
    </div>
</div>
<?php
	$checkUserForm = false;//Check to displaying permission form
	if(isset($user) && in_array($user->role, array(User::ROLE_MONITOR, User::ROLE_SUPPORT, User::ROLE_TELESALES))):
		$checkUserForm = true;
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
    	<span class="fL"><b>Người dùng hệ thống:</b>&nbsp;<?php echo $user->fullName().' ('.$user->email.', '.$user->role.')';?></span>
    </div>
</div>
<?php endif;?>
<div class="clearfix h20">&nbsp;</div>
<?php if($checkUserForm):?>
	<form id="PermissionForm" action="/admin/permission/user/id/<?php echo $user->id;?>" method="post">
<?php endif;?>
<?php if(count($permissions)>0):?>
	<div class="form-element-container row pB10 pL10 borderGrey">
		<?php foreach($permissions as $permission):?>	
		<div class="col col-lg-3 pA5">
			<?php $checked = "";			
				if(isset($user) && in_array($permission->id, $assignedPermissionIds)){
					$checked = 'checked="checked"';
				}
			?>
			<input type="checkbox" name="Permission[]" value="<?php echo $permission->id;?>" <?php echo $checked;?>/>
			<span <?php echo ($checked!="")? 'class="error"': "";?>><?php echo $permission->title;?></span>
		</div>	
		<?php endforeach;?>
	</div>
	<?php if($checkUserForm):?>
		<div class="form-element-container row">
			<input type="hidden" name="chkSave" value="1"/>
        	<button class="btn btn-primary pA5 mT20" name="form_action" type="submit"><i class="icon-save"></i>Lưu thay đổi phân quyền người dùng</button>
		</div>
	<?php endif;?>
<?php endif;?>
<?php echo ($checkUserForm)? "</form>": "";?>
<div class="clearfix h30">&nbsp;</div>	
