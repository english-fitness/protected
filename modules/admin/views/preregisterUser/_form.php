<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	//Cancel button
	function cancel(){
        <?php if(Yii::app()->request->urlReferrer != null):?>
		window.location = '<?php echo Yii::app()->request->urlReferrer;?>';
        <?php else:?>
        window.location = '<?php echo Yii::app()->baseUrl.'/admin/preregisterUser';?>';
        <?php endif?>
	}
	//Remove preset course
	function removePreUser(preUserId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa đăng ký tư vấn này?");
		if(checkConfirm){
            var removeUrl = '/admin/preregisterUser/delete/id/'+preUserId;
            <?php if(Yii::app()->request->urlReferrer != null):?>
                removeUrl += '?urlReferrer=<?php echo Yii::app()->request->urlReferrer?>';
            <?php endif?>
			window.location = removeUrl;
		}
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
    <?php if(Yii::app()->request->urlReferrer != null):?>
    $(function(){
        $("#preregister-user-form").append($('<input>')
            .attr("type", "hidden")
            .attr("name", "urlReferrer")
            .val('<?php echo Yii::app()->request->urlReferrer?>')
        );
    });
    <?php endif;?>
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
        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm đăng ký tư vấn' : 'Sửa đăng ký tư vấn';?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
        	<?php if(!$model->isNewRecord):?>
        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removePreUser(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa đăng ký</button>
        	<?php endif;?>
        </div>
    </div>
</div>
<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
<div class="form-element-container row">
	<div class="col col-lg-3">&nbsp;</div>
	<div class="col col-lg-9 errorMessage">Đăng ký này đã bị hủy bỏ, để xóa hoàn toàn đăng ký tư vấn này, bạn hãy vui lòng nhấn tiếp "Xóa đăng ký"!</div>
</div>
<?php endif;?>
<?php 
	$readOnlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'allowEdit(this)'): array();
	$disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();
?>
<fieldset>
<legend>Thông tin người đăng ký</legend>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'fullname'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($model,'fullname', array_merge($readOnlyAttrs, array('size'=>60,'maxlength'=>256, 'placeholder'=>'Họ và tên của học sinh'))); ?>
				<?php echo $form->error($model,'fullname'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($model,'birthday', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php echo $form->textField($model,'birthday', array_merge($readOnlyAttrs, array('placeholder'=>'Định dạng ngày sinh yyyy-mm-dd'))); ?>
					<?php echo $form->error($model,'birthday'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'email'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($model,'email', array_merge($readOnlyAttrs, array('size'=>60,'maxlength'=>128))); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($model,'gender', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php $genderOptions = array(''=>'---Giới tính---', '0'=>'Nữ', '1'=>'Nam');?>
					<?php echo $form->dropDownList($model,'gender', $genderOptions, array()); ?>
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
				<?php echo $form->textField($model,'phone', array_merge($readOnlyAttrs, array('size'=>20,'maxlength'=>20))); ?>
					<?php echo $form->error($model,'phone'); ?>
			</div>
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'source'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($model,'source', array_merge($readOnlyAttrs, array('size'=>30,'maxlength'=>30))); ?>
					<?php echo $form->error($model,'source'); ?>
			</div>
		</div>
	</div>	
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Thông tin khác</legend>	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'weekday'); ?>
		</div>
		<div class="col col-lg-9">
			<p><?php echo $model->getWeekdays()?></p>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'timerange'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'timerange',array_merge($readOnlyAttrs,array('size'=>60,'maxlength'=>256))); ?>
			<?php echo $form->error($model,'timerange'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'promotion_code'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'promotion_code',array_merge($readOnlyAttrs,array('size'=>60,'maxlength'=>256))); ?>
			<?php echo $form->error($model,'promotion_code'); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<?php $this->endWidget(); ?>

</div><!-- form -->