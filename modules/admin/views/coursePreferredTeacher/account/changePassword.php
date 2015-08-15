<?php
/* @var $this StudentController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
<fieldset>
	<legend>Thay đổi mật khẩu</legend>
	<div class="form-element-container row">
	 	<?php if(isset($successMsg)):?>
	 	<div class="alert alert-success"><?php echo $successMsg;?></div>
	 	<?php endif;?>
	</div>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <label class="required" for="User_password">Mật khẩu cũ<span class="required">*</span></label>
        </div>
		 <div class="col col-lg-9">
		 	<?php $password = Yii::app()->controller->getPost('User[password]', '');?>
            <input id="User_password" type="password" value="<?php echo $password;?>" name="User[password]" maxlength="128" size="60">
            <div class="errorMessage"><?php echo isset($errorPassword)? $errorPassword: "";?></div>
        </div>		
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <label class="required" for="User_password">Nhập mật khẩu mới<span class="required">*</span></label>
        </div>
		 <div class="col col-lg-9">
		 	<?php $passwordSave = Yii::app()->controller->getPost('User[passwordSave]', ''); ?>
            <input id="User_passwordSave" type="password" value="<?php echo $passwordSave;?>" name="User[passwordSave]" maxlength="128" size="60">
            <div class="errorMessage"><?php echo isset($errorPasswordSave)? $errorPasswordSave: "";?></div>
        </div>		
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <label class="required" for="User_password">Nhập lại mật khẩu mới<span class="required">*</span></label>
        </div>
		 <div class="col col-lg-9">
		 	<?php $repeatPassword = Yii::app()->controller->getPost('User[repeatPassword]', '');?>
            <input id="User_repeatPassword" type="password" value="<?php echo $repeatPassword;?>" name="User[repeatPassword]" maxlength="128" size="60">
            <div class="errorMessage"><?php echo isset($errorRepeatPassword)? $errorRepeatPassword: "";?></div>
        </div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		 <div class="col col-lg-9">
		 	<button type="submit" id="btnChangePassword" name="changePassword" class="btn btn-primary next-step">Lưu mật khẩu mới</button>
        </div>
	</div>
	<div class="clearfix h50">&nbsp;</div>
</fieldset>
<?php $this->endWidget(); ?>

</div><!-- form -->