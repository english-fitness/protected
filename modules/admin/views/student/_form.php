<?php
/* @var $this StudentController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php
$createFromRegistration = ($this->action->id == 'create' && isset($preregisterUser));
$usernamePrefixesOptions = Settings::getPresetOptions("student_prefix");
$nextUserIndices = array();
foreach ($usernamePrefixesOptions as $prefix){
    $nextUserIndices[$prefix] = ClsUser::getNextUserIndex($prefix);
}
?>
<script type="text/javascript">
	function cancel(){
        <?php if($createFromRegistration):?>
        window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/preregisterUser';
        <?php else:?>
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/student/';
        <?php endif;?>
	}
	//Remove student
	function removeStudent(studentId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn xóa học sinh này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/student/delete/id/'+studentId;
		}
	}
	//Allow change password
	function changePassword(){
		var checkConfirm = confirm("Bạn có chắc chắn muốn thay đổi mật khẩu của học sinh này?");
		if(checkConfirm){
			$("#changePassword").show();
			$("#changePasswordStatus").val('1');
		}
	}
	//Allow change role student
	function changeToTeacher(studentId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn chuyển học sinh này thành giáo viên?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/student/changeToTeacher/id/'+studentId;
		}
	}
	//Open status to update
	function openStudentStatus(){
		$("#User_status").removeAttr("disabled");
	}
	//Allow change html object field
	function allowChangeSaleUser(){
		$("#Student_sale_user_id").removeAttr("disabled");
	}
	//Allow edit html object field
	function allowEdit(htmlObject){
		$(htmlObject).removeAttr('readonly');
	}
	$(document).on("click",".datepicker",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
    
    $(function(){
        var usePrefix = $("#use_prefix_checkbox").is(":checked");
        var nextUserIndices = {
            <?php foreach($nextUserIndices as $prefix=>$index){
                if ($index < 10){
                    $index = "00" . $index;
                } else if ($index <100){
                    $index = "0" . $index;
                }
                echo "'" . $prefix . "': '" . $index . "',";
            }?>
        };
        
        $("#username_prefix").change(function(){
            $("#username_index").val(nextUserIndices[this.value]).change();
        });
        
        $("#username_index").change(function(){
            if (usePrefix){
                $("#User_username").val($("#username_prefix").val() + this.value);
            } else {
                $("#User_username").val(this.value);
            }
        });
        
        $("#use_prefix_checkbox").change(function(){
            if ($(this).is(":checked")){
                usePrefix = true;
                $("#username_prefix").change().parent().show();
                $("#username_index").parent().addClass("col-lg-9");
            } else {
                usePrefix = false;
                $("#username_prefix").parent().hide();
                $("#username_index").val("").change().parent().removeClass("col-lg-9");
            }
        });
        
        $("#username_prefix").change();
    });
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
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm học sinh' : 'Sửa thông tin học sinh';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
                <?php if (!$student->hasErrors("preregister_id")):?>
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
                <?php endif;?>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin() && $model->status==User::STATUS_PENDING):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeStudent(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa học sinh</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
    <div class="row"><div style="float:right"><?php echo $form->error($student,'preregister_id'); ?></div></div>
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
            <?php if ($model->isNewRecord):?>
                <div style="margin-bottom:5px;margin-top:-20px">
                    <input type="checkbox" id="use_prefix_checkbox" checked> Dùng tên tài khoản định sẵn
                </div>
                <div class="col col-lg-3" style="padding-left:0">
                    <select id="username_prefix">
                    <?php foreach($usernamePrefixesOptions as $prefix):?>
                        <option value="<?php echo $prefix?>"><?php echo $prefix?></option>
                    <?php endforeach;?>
                    </select>
                </div>
                <div class="col col-lg-9" style="padding-right:0; padding-left:0">
                    <input type="text" id="username_index">
                </div>
                <?php echo $form->hiddenField($model,'username'); ?>
                <?php echo $form->error($model,'username'); ?>
            <?php else:
                echo $form->textField($model,'username',array_merge(array('size'=>60,'maxlength'=>30), $readonlyAttrs));
                echo $form->error($model,'username');
            endif;?>
            
			<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
			<div class="fL">
				<a class="fs12 errorMessage" href="javascript: changeToTeacher(<?php echo $model->id;?>);">Chuyển học sinh này thành role giáo viên?</a>
			</div>
			<div class="fR">
				<a class="fs12 errorMessage" href="javascript: changePassword();">Cho phép admin thay đổi mật khẩu của học sinh này?</a>
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
            <?php echo $form->textField($model,'password',array('size'=>60,'maxlength'=>128,'value'=>$model->isNewRecord ? 'speakup.vn' : '')); ?>
			<?php echo $form->error($model,'password'); ?>
			<?php if(!$model->isNewRecord):?>
			<label class="hint">Nhập mật khẩu mới cho tài khoản của học sinh này!</label>
            <?php else:?>
            <label class="hint">Mật khẩu mặc định là "speakup.vn"</label>
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
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'lastname'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($model,'lastname', array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttrs)); ?>
				<?php echo $form->error($model,'lastname'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($model,'firstname', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php echo $form->textField($model,'firstname', array_merge(array('size'=>60,'maxlength'=>128), $readonlyAttrs)); ?>
					<?php echo $form->error($model,'firstname'); ?>
				</div>
			</div>
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
			<label class="hint fR mR20"><span class="clrBlack">Click đúp vào các trường dữ  liệu cần sửa, để cho phép thay đổi giá trị</span></label>
		<?php endif;?>
	</legend>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'birthday'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($model,'birthday',$readonlyAttrs); ?>
				<?php echo $form->error($model,'birthday'); ?>
				<label class="hint">Định dạng ngày sinh yyyy-mm-dd</label>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($model,'gender', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php $genderOptions = array(0=>'Chưa xác định', 1=>'Nữ', 2=>'Nam');?>
					<?php echo $form->dropDownList($model,'gender', $genderOptions, $readonlyAttrs); ?>
					<?php echo $form->error($model,'gender'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'phone'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($model,'phone',array_merge(array('size'=>20,'maxlength'=>20),$readonlyAttrs)); ?>
					<?php echo $form->error($model,'phone'); ?>
			</div>
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
			<?php echo $form->labelEx($model,'status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->dropDownList($model,'status', Student::statusOptions(), $disabledAttrs); ?>
			<?php echo $form->error($model,'status'); ?>
			<?php if(!$model->isNewRecord):?>
			<div class="fR">
				<a class="fs12 errorMessage" href="javascript: openStudentStatus();">Thay đổi trạng thái của học sinh (thủ công)!</a>
			</div>
			<?php endif;?>
		</div>
	</div>
    <?php if (!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($student,'official_start_date'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($student,'official_start_date',array_merge(array("class"=>"datepicker"), $readonlyAttrs)); ?>
			<?php echo $form->error($student,'official_start_date'); ?>
		</div>
	</div>
    <?php endif;?>
	<?php if(!$model->isNewRecord):?>
	<div class="form-element-container row">
		<div class="col col-lg-3"><label>Lịch sử trạng thái</label></div>
		<div class="col col-lg-9">
			<?php echo $model->displayHistoryStatus();?>
		</div>
	</div>
	<?php endif;?>
</fieldset>
<fieldset>
	<legend>Thông tin liên hệ</legend>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($student,'contact_name'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($student,'contact_name',array_merge(array('maxlength'=>128),$readonlyAttrs)); ?>
			<?php echo $form->error($student,'contact_name'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($student,'contact_phone'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($student,'contact_phone',array_merge(array('maxlength'=>20),$readonlyAttrs)); ?>
			<?php echo $form->error($student,'contact_phone'); ?>
		</div>
	</div>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($student,'contact_email'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($student,'contact_email',array_merge(array('maxlength'=>128),$readonlyAttrs)); ?>
			<?php echo $form->error($student,'contact_email'); ?>
		</div>
	</div>
</fieldset>
</div><!-- form -->
<?php $this->endWidget(); ?>
<?php if ($createFromRegistration):
    $fullname = $preregisterUser->fullname;
    $firstSpace = strpos($fullname, ' ');
    if ($firstSpace > -1){
        $lastname = substr($fullname, 0, $firstSpace);
        $firstname = substr($fullname, $firstSpace + 1);
    } else {
        $firstname = $fullname;
        $lastname = '';
    }
?>
    <script>
        document.getElementById('User_email').value = '<?php echo $preregisterUser->email?>';
        document.getElementById('User_lastname').value = '<?php echo $lastname?>';
        document.getElementById('User_firstname').value = '<?php echo $firstname?>';
        document.getElementById('User_phone').value = '<?php echo $preregisterUser->phone?>';
    </script>
<?php endif;?>