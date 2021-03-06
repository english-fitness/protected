<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	//Cancel button
	function cancel(){
		<?php if(Yii::app()->request->urlReferrer != null){
            echo "window.location = '" . Yii::app()->request->urlReferrer . "';";
        } else {
            echo "window.location = '" . Yii::app()->baseUrl."/admin/preregisterUser';";
        }
        ?>
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
        }).datepicker("show");
    });
    <?php if(Yii::app()->request->urlReferrer != null):?>
    $(function(){
        $("#preregister-user-form").append($('<input>')
            .attr("type", "hidden")
            .attr("name", "urlReferrer")
            .val('<?php echo Yii::app()->request->urlReferrer?>')
        );
        
        var mustSaveBeforeCreate = false;
        
        $("#preregister-user-form").change(function(){
            mustSaveBeforeCreate = true;
        });
        
        $("#createUserButton").click(function(e){
            e.preventDefault();
            
            //need to check if ckeditor instance changed
            for ( instance in CKEDITOR.instances ){
                if ( CKEDITOR.instances[instance].checkDirty() ){
                    mustSaveBeforeCreate = true;
                }
            }
            
            if (mustSaveBeforeCreate){
                for ( instance in CKEDITOR.instances ){
                    CKEDITOR.instances[instance].updateElement();
                }
                $("#savingIndicator").html('<span style="color:blue;float:right">Đang lưu thông tin tư vấn...</span>').show()
                $.ajax({
                    url:"/admin/preregisterUser/ajaxSaleUpdate/id/<?php echo $model->id?>",
                    type:"post",
                    data:$("#preregister-user-form").serialize(),
                    success:function(response){
                        if (!response.success){
                            $("#savingIndicator").html('<span style="color:red;float:right">Không thể lưu thông tin</span>').show()
                        }
                        window.location.href = "/admin/student/create?preregisterId=<?php echo $model->id?>";
                    },
                    error:function(){
                        $("#savingIndicator").html('<span style="color:red;float:right">Không thể lưu thông tin</span>').show()
                        window.location.href = "/admin/student/create?preregisterId=<?php echo $model->id?>";
                    }
                });
            } else {
                window.location.href = "/admin/student/create?preregisterId=<?php echo $model->id?>";
            }
        });
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
        <h2 class="page-title mT10">Ghi chú chăm sóc, tư vấn</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
        </div>
        <?php if(!$model->hasExistingUser()):?>
            <button id="createUserButton" class="btn btn-default" name="form_action" type="button"><i class="icon-plus"></i>Tạo tài khoản</button>
        <?php endif;?>
    </div>
</div>
<div class="row">
    <div class="col col-lg-12" id="savingIndicator" style="display:none"></div>
</div>
<?php 
	$readOnlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'allowEdit(this)'): array();
	$disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();
?>
<?php $this->renderPartial('/preregisterUser/careStatusGuide')?>
<fieldset>
<legend>
	Thông tin người đăng ký
	<?php if (!$model->isNewRecord):?>
		<span><a class="btn-edit" style="float:none;text-decoration:none" href="/admin/preregisterUser/update/id/<?php echo $model->id?>">&nbsp;&nbsp;&nbsp;&nbsp;</a></span>
	<?php endif;?>
</legend>
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
			<label>Ngày học:&nbsp;</label>
			<?php echo $model->getWeekdays();?>
		</div>
		<div class="col col-lg-4">
			<label>Giờ học:&nbsp;</label>
			<?php echo $model->timerange;?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Nguồn:&nbsp;</label>
			<?php echo $model->source;?>
		</div>
		<div class="col col-lg-4">
			<label>Mã khuyến mại:&nbsp;</label>
			<?php echo $model->promotion_code;?>
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
				<?php echo PreregisterUser::careStatusFilter(false, $model->care_status, array("id"=>"PreregisterUser_care_status")); ?>
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
					<?php if(!$model->isNewRecord):?>
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
            <?php echo $form->labelEx($model,'last_sale_date', array('class'=>'mT10')); ?>
        </div>
        <div class="col col-lg-9">
            <div class="col col-lg-5 pL0i pR0i">
                <?php echo $form->textField($model,'last_sale_date', array('class'=>'datepicker','placeholder'=>'Định dạng ngày tư vấn cuối yyyy-mm-dd')); ?>
					<?php echo $form->error($model,'last_sale_date'); ?>
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

<script type="text/javascript">
	var statusGuide = <?php echo json_encode(PreregisterUser::careStatusGuide())?>;
    $("#PreregisterUser_care_status").find('option').each(function(){
    	$(this).html($(this).html() + ' - ' + statusGuide[$(this).val()]);
    })
</script>