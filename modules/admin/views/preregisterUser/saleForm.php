<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	//Cancel button
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl.'/admin/preregisterUser';?>';
	}
	//Allow edit html object field
	function allowEdit(htmlObject){
		$(htmlObject).removeAttr('readonly');
	}
	//Allow change html object field
	function allowChangeSaleUser(){
		$("#PreregisterUser_sale_user_id").removeAttr("disabled");
	}
	$(document).on("click",".datepicker",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'preregister-user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Ghi chú chăm sóc, tư vấn</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
        </div>
    </div>
</div>
<?php 
	$readOnlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'allowEdit(this)'): array();
	$disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();
?>
<fieldset>
<legend>Thông tin người đăng ký</legend>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Họ và tên:&nbsp;</label><?php echo $model->fullname;?>
		</div>
		<div class="col col-lg-4">
			<label>Email:&nbsp;</label><?php echo $model->email;?>
		</div>
		<div class="col col-lg-4">
			<label>Điện thoại:&nbsp;</label><?php echo $model->phone;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Khối lớp:&nbsp;</label><?php $model->class_name;?>
		</div>
		<div class="col col-lg-4">
			<label>Ngày sinh:&nbsp;</label><?php echo ($model->birthday)? date('d/m/Y', strtotime($model->birthday)):"";?>
		</div>
		<div class="col col-lg-4">
			<label>Giới tính:&nbsp;</label>
			<?php $genderOptions = array(0=>'Nữ', 1=>'Nam');
				echo ($model->gender)? $genderOptions[$model->gender]:"";
			?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Người đăng ký:&nbsp;</label>
			<?php echo $model->userTypeOptions($model->user_type);?>
		</div>
		<div class="col col-lg-4">
			<label>Tên phụ huynh:&nbsp;</label><?php echo $model->parent_name;?>
		</div>
		<div class="col col-lg-4">
			<label>ĐT phụ huynh:&nbsp;</label><?php echo $model->parent_phone;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Địa chỉ:&nbsp;</label><?php echo $model->address;?>
		</div>
		<div class="col col-lg-4">
			<label>Trạng thái đăng ký:&nbsp;</label>
			<?php echo $model->statusOptions($model->status);?>
		</div>
		<div class="col col-lg-4">
			<label>Thành viên trong hệ thống?:&nbsp;</label>
			<?php echo ($model->refer_user_id)? User::model()->displayUserById($model->refer_user_id):"";?>
		</div>		
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Môn muốn gia sư:&nbsp;</label><?php echo $model->subject_note;?>
		</div>
		<div class="col col-lg-8">
			<label>Mục tiêu học tập:&nbsp;</label><?php echo $model->objective;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-12">
			<label>Yêu cầu giáo viên:&nbsp;</label><?php echo $model->teacher_request;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-12">
			<label>Yêu cầu học tập:&nbsp;</label><?php echo $model->content_request;?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Ghi chú chăm sóc, tư vấn</legend>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'care_status'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->dropDownList($model,'care_status', $model->careStatusOptions(), array()); ?>
				<?php echo $form->error($model,'care_status'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($model,'sale_user_id', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php $salesUserOptions = Student::model()->getSalesUserOptions(true, "---Người tư vấn---");?>
					<?php echo $form->dropDownList($model,'sale_user_id', $salesUserOptions, $disabledAttrs); ?>
					<?php echo $form->error($model,'sale_user_id'); ?>
					<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
					<div class="fR">
						<a class="fs12 errorMessage" href="javascript: allowChangeSaleUser();">Thay đổi người chăm sóc, tư vấn!</a>
					</div>
					<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'sale_status'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($model,'sale_status', array_merge($readOnlyAttrs, array('size'=>60,'maxlength'=>80))); ?>
				<?php echo $form->error($model,'sale_status'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($model,'last_sale_date', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php echo $form->textField($model,'last_sale_date', array('class'=>'datepicker','placeholder'=>'Định dạng ngày tư vấn cuối yyyy-mm-dd')); ?>
					<?php echo $form->error($model,'last_sale_date'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'sale_note'); ?>
		</div>
		<div class="col col-lg-9">
		<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$model,
				'attribute'=>'sale_note',
				'language'=>'en',
				'editorTemplate'=>'advanced',
				'toolbar' => array(
                    array('-','Source','-','Bold','Italic','Underline','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote','-','Link','Unlink','-','SpecialChar','-','Cut','Copy','Paste','-','Undo','Redo','-','Maximize','-','About'),
                ),
			)); ?>
		<?php echo $form->error($model,'sale_note'); ?>
		</div>	
	</div>
	
</fieldset>
<?php $this->endWidget(); ?>

</div><!-- form -->