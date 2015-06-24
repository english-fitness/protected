<?php
/* @var $this TeacherController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/teacher/';
	}
	function removeTeacher(teacherId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn xóa giáo viên này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/teacher/delete/id/'+teacherId;
		}
	}
	function changePassword(){
		var checkConfirm = confirm("Bạn có chắc chắn muốn thay đổi mật khẩu của học sinh này?");
		if(checkConfirm){
			$("#changePassword").show();
			$("#changePasswordStatus").val('1');
		}
	}
	function changeToStudent(teacherId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn chuyển giáo viên này thành học sinh?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/teacher/changeToStudent/id/'+teacherId;
		}
	}
	//Allow edit html object field
	function allowEdit(htmlObject){
		$(htmlObject).removeAttr('readonly');
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	"htmlOptions"=>array(
		"enctype"=>"multipart/form-data"
	),
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm giáo viên' : 'Sửa thông tin giáo viên';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin() && $model->status==User::STATUS_PENDING):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeTeacher(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa giáo viên</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
<fieldset>
	<?php $disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();?>
	<?php $readonlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'allowEdit(this)'): array();?>
	<legend>Thông tin tài khoản</legend>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'username'); ?>
        </div>
        <?php $changeStatus = Yii::app()->request->getPost('changeStatus', "0");?>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'username',array_merge(array('size'=>60,'maxlength'=>30), $disabledAttrs)); ?>
			<?php echo $form->error($model,'username'); ?>
			<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
			<div class="fL">
				<a class="fs12 errorMessage" href="javascript: changeToStudent(<?php echo $model->id;?>);">Chuyển giáo viên này thành role học sinh?</a>
			</div>
			<div class="fR">
				<a class="fs12 errorMessage" href="javascript: changePassword();">Cho phép admin thay đổi mật khẩu của giáo viên này?</a>
				<input type="hidden" id="changePasswordStatus" name="changeStatus" value="<?php echo $changeStatus;?>"/>
			</div>
			<?php endif;?>
        </div>		
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'email'); ?>
		</div>
		<div class="col col-lg-9">        	
			<?php echo $form->textField($model,'email',array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttrs)); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
	</div>
	<div id="changePassword" class="form-element-container row" style="<?php echo (!$model->isNewRecord && $changeStatus==0)? 'display:none;': "";?>">
		<div class="col col-lg-3">
            <?php echo $form->labelEx($model,'password'); ?>
        </div>
		 <div class="col col-lg-9">
            <?php echo $form->passwordField($model,'password',array('size'=>60,'maxlength'=>128,'value'=>'')); ?>
			<?php echo $form->error($model,'password'); ?>
			<?php if(!$model->isNewRecord):?>
			<label class="hint">Nhập mật khẩu mới cho tài khoản của giáo viên này!</label>
			<?php endif;?>
        </div>		
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'lastname'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'lastname', array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttrs)); ?>
			<?php echo $form->error($model,'lastname'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'firstname'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'firstname', array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttrs)); ?>
			<?php echo $form->error($model,'firstname'); ?>
		</div>
	</div>
	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Đăng nhập từ URL</label></div>
		<div class="col col-lg-9">
			<?php $tokenCode = sha1($model->id.$model->role.$model->email);?>
			<span class="fs12"><?php echo Yii::app()->getRequest()->getBaseUrl(true)."/login/byUrl?email=".$model->email."&token=".$tokenCode;?></span>
		</div>
	</div>
	<?php endif;?>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Thông tin cá nhân
		<?php if(!$model->isNewRecord):?>
		<label class="hint fR mR20"><span class="error">Click đúp vào các trường dữ  liệu cần sửa, để cho phép thay đổi giá trị</span></label>
		<?php endif;?>
	</legend>
	<div class="form-element-container row">
        <div class="col col-lg-3">
			<label>Ảnh đại diện</label>
		</div>
        <div class="value col-lg-7 loadImageJavascript">
            <img src="<?php echo Yii::app()->user->getProfilePicture($teacher->user_id); ?>" class="w50" alt="avartar"/>
            <input type="file" name="profilePicture" style="width: auto;"/>
        </div>
    </div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($teacher,'title'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($teacher,'title',array_merge(array('size'=>60,'maxlength'=>256), $readonlyAttrs)); ?>
			<?php echo $form->error($teacher,'title'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="User_profile_picture">Môn dạy gia sư</label>
		</div>
		<div class="col col-lg-9">
			<div class="class-subjects">
				<?php foreach($classSubjects as $clsId=>$classSubject):?>
					<div class="clearfix pA5" style="border-bottom:1px dashed #EDEDED;">
						<label class="fL"><?php echo $classSubject['name']?></label>
						<?php foreach($classSubject['subject'] as $subject):?>
							<span class="fL w20">&nbsp;</span><input type="checkbox" <?php if(in_array($subject['id'], $abilitySubjects)):?> checked="checked" <?php endif;?>
							 class="fL mL10" name="abilitySubjects[]" value="<?php echo $subject['id'];?>"/><span class="fL">&nbsp;<?php echo $subject['name'];?></span>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($teacher,'short_description'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($teacher,'short_description',array_merge(array('rows'=>6, 'cols'=>5, 'style'=>'height:6em;'), $readonlyAttrs)); ?>
			<?php echo $form->error($teacher,'short_description'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'birthday'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'birthday', $readonlyAttrs); ?>
			<?php echo $form->error($model,'birthday'); ?>
			<label class="hint">Định dạng ngày sinh yyyy-mm-dd</label>
        </div>
	</div>

	<div class="form-element-container row">
		<?php $gender_options = array(0=>'Chưa xác định', 1=>'Nữ', 2=>'Nam',);?>
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
			<?php echo $form->textField($model,'address',array_merge(array('size'=>60,'maxlength'=>256),$readonlyAttrs)); ?>
			<?php echo $form->error($model,'address'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'phone'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'phone',array_merge(array('size'=>20,'maxlength'=>20),$readonlyAttrs)); ?>
			<?php echo $form->error($model,'phone'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'status', $model->statusOptions(), array()); ?>
			<?php echo $form->error($model,'status'); ?>
		</div>
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($teacher,'description'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$teacher,
				'attribute'=>'description',
				'language'=>'en',
				'editorTemplate'=>'advanced',
				'toolbar' => array(
                    array('-','Source','-','Bold','Italic','Underline','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote','-','Link','Unlink','-','SpecialChar','-','Cut','Copy','Paste','-','Undo','Redo','-','Maximize','-','About'),
                ),
			)); ?>
			<?php echo $form->error($teacher,'description'); ?>
		</div>
	</div>
</fieldset>
<?php $this->endWidget(); ?>

</div><!-- form -->