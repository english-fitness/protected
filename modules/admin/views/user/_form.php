<?php
/* @var $this StudentController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/user/';
	}
	function removeUser(userId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn xóa người dùng này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/user/delete/id/'+userId;
		}
	}
	function changePassword(){
		var checkConfirm = confirm("Bạn có chắc chắn muốn thay đổi mật khẩu của người dùng này?");
		if(checkConfirm){
			$("#changePassword").show();
			$("#changePasswordStatus").val('1');
		}
	}
	function changeRole(){
		var checkConfirm = confirm("Bạn có chắc chắn muốn thay đổi vai trò của người dùng này?");
		if(checkConfirm){
			$("#User_role").removeAttr('disabled');
		}
	}
	function allowEdit(htmlObject){
		$(htmlObject).removeAttr('readonly');
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm người dùng' : 'Sửa thông tin người dùng';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeUser(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa người dùng</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
<fieldset>
	<?php $readonlyAttr = (!$model->isNewRecord)? array('readonly'=>'readonly', 'ondblclick'=>'allowEdit(this)'): array();?>
	<legend>Thông tin tài khoản</legend>
	<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 errorMessage">Người dùng này đã bị hủy bỏ, để xóa hoàn toàn người dùng này & các thông tin, lịch sử liên quan, bạn hãy vui lòng nhấn tiếp "Xóa người dùng"!</div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'username'); ?>
        </div>
        <div class="col col-lg-9">
        	<?php $changeStatus = Yii::app()->request->getPost('changeStatus', "0");?>
            <?php echo $form->textField($model,'username',array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttr)); ?>
			<?php echo $form->error($model,'username'); ?>
			<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
			<?php $chanPassAttrs = ($model->role==User::ROLE_ADMIN)? 'dpn': '';?>
			<div class="fR <?php echo $chanPassAttrs;?>">
				<a class="fs12 errorMessage" href="javascript: changePassword();">Cho phép admin thay đổi mật khẩu của người dùng này?</a>
				<input type="hidden" id="changePasswordStatus" name="changeStatus" value="<?php echo $changeStatus;?>"/>
			</div>
			<?php endif;?>
        </div>		
	</div>
	<div id="changePassword" class="form-element-container row" style="<?php echo (!$model->isNewRecord && $changeStatus==0)? 'display:none;': "";?>">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'password'); ?>
        </div>
		 <div class="col col-lg-9">
            <?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128, 'value'=>'')); ?>
			<?php echo $form->error($model,'password'); ?>
			<?php if(!$model->isNewRecord):?>
			<label class="hint">Nhập mật khẩu mới cho tài khoản của người dùng này!</label>
			<?php endif;?>
        </div>		
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'email'); ?>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'email',array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttr)); ?>
			<?php echo $form->error($model,'email'); ?>
        </div>		
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'lastname'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'lastname',array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttr)); ?>
			<?php echo $form->error($model,'lastname'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'firstname'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'firstname',array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttr)); ?>
			<?php echo $form->error($model,'firstname'); ?>
		</div>
	</div>
	<?php if($model->isNewRecord || $model->role==User::ROLE_MONITOR || $model->role==User::ROLE_SUPPORT):?>
	<?php $disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled','ondblclick'=>'enableEdit(this)'):array();?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'role'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $userRoleOptions = array(User::ROLE_MONITOR=>User::ROLE_MONITOR, User::ROLE_SUPPORT=>User::ROLE_SUPPORT);?>
			<?php echo $form->dropDownList($model,'role', $userRoleOptions, $disabledAttrs); ?>
			<?php echo $form->error($model,'role'); ?>
			<div class="fR <?php echo $chanPassAttrs;?>">
				<a class="fs12 errorMessage" href="javascript: changeRole();">Cho phép thay đổi vai trò</a>
			</div>
		</div>
	</div>
	<?php endif;?>
	<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'status', $model->statusOptions(), array()); ?>
			<?php echo $form->error($model,'status'); ?>
        </div>
	</div>
	<?php endif; ?>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Thông tin cá nhân</legend>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'birthday'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'birthday'); ?>
			<?php echo $form->error($model,'birthday'); ?>
			<label class="hint">Định dạng ngày sinh yyyy-mm-dd</label>
        </div>
	</div>

	<div class="form-element-container row">
		<?php $gender_options = array(0=>'Chưa xác định', 1=>'Nữ', 2=>'Nam');?>
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'gender'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'gender', $gender_options, array()); ?>
			<?php echo $form->error($model,'gender'); ?>
        </div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'address'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'address'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'phone'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20)); ?>
			<?php echo $form->error($model,'phone'); ?>
		</div>
	</div>	

</fieldset>
<?php $this->endWidget(); ?>

</div><!-- form -->