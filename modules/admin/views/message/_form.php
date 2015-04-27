<script type="text/javascript">
	function cancel(){
		window.location = daykemBaseUrl + '/admin/message/inbox';
	}
	function removeMessage(id){
		var checkConfirm = confirm("Bạn có chắc chắn xóa tin nhắn này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/message/delete/id/'+id;
		}
	}
</script>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'message-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo (!$model->isNewRecord) ? 'Sửa nội dung tin nhắn' : 'Thêm/Trả lời tin nhắn';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu / Gửi tin nhắn</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeMessage(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
	<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 errorMessage">Tin nhắn này bị hủy bỏ, để xóa hoàn toàn tin nhắn, bạn hãy vui lòng nhấn tiếp "Xóa bản ghi"!</div>
	</div>
	<?php endif;?>
	<?php if(isset($receiver) && isset($message)):?>
	<div class="form-element-container row">
		<div class="col col-lg-3"><b><?php echo $message->getLinkUserByAdminPage();?></b></div>
		<div class="col col-lg-9">
			<div class="col col-lg-12 pL0i"><b>Tiêu đề: </b><?php echo $message->title;?></div>
			<div class="col col-lg-12 pL0i"><b>Nội dung: </b><?php echo $message->content;?></div>
		</div>
	</div>	
	<?php endif;?>
	<?php if(!$model->isNewRecord):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Người nhận tin nhắn&nbsp;<span class="required"></span></label>
		</div>
		<div class="col col-lg-9">
			<?php echo implode(", ", $model->getRecipientsInMessage("/admin/user/view/id"));?>
        </div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Gán người nhận &nbsp;<span class="required"></span></label>
		</div>
		<div class="col col-lg-9">
			<?php $ajaxSearchUser = isset($receiver)? $receiver->email: "";?>
	        <?php $this->renderPartial("/course/widget/ajaxAddUser",array("ajaxSearchUser"=>$ajaxSearchUser,"ajaxBaseUrl"=>"/notification/ajaxLoadUser")); ?>
        </div>
	</div>
    <div class="form-element-container row">
        <div class="col col-lg-3">
            <label>Tiêu đề tin nhắn &nbsp;<span class="required">*</span></label>
        </div>
        <div class="col col-lg-9">
            <?php echo $form->textField($model,'title',array('class'=>'class_title','size'=>60,'maxlength'=>256)); ?>
            <?php echo $form->error($model,'title'); ?>
        </div>
    </div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Nội dung tin nhắn &nbsp;<span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$model,
				'attribute'=>'content',
				'language'=>'en',
				'editorTemplate'=>'advanced',
				'toolbar' => array(
                    array('-','Source','-','Bold','Italic','Underline','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote','-','Link','Unlink','-','SpecialChar','-','Cut','Copy','Paste','-','Undo','Redo','-','Maximize','-','About'),
                ),
			)); ?>
			<?php echo $form->error($model,'content'); ?>
        </div>
	</div>
	<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>
</div>