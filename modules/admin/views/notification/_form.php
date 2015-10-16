<?php
/* @var $this NotificationController */
/* @var $model Notification */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/notification/';
	}
	function removeNotice(noticeId){
		var checkConfirm = confirm("Bạn có chắc chắn xóa thông báo này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/notification/delete/id/'+noticeId;
		}
	}
</script>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'notification-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm thông báo mới' : 'Sửa thông báo';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord && Yii::app()->user->isAdmin()):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeNotice(<?php echo $model->id;?>);"><i class="btn-remove"></i>Xóa bản ghi</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
	<?php if(!$model->isNewRecord && $model->deleted_flag==1):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 errorMessage">Thông báo này bị hủy bỏ, để xóa hoàn toàn thông báo, bạn hãy vui lòng nhấn tiếp "Xóa bản ghi"!</div>
	</div>
	<?php endif;?>
	<?php if(isset($errorMsg) && $errorMsg!=""):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">&nbsp;</div>
		<div class="col col-lg-9 error"><?php echo $errorMsg;?></div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Tài khoản người nhận&nbsp;<span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php if($model->isNewRecord):?>
                <?php $this->renderPartial("/widgets/addUser",array()); ?>
                <label class="hint"><b class="clrBlack">all_students@hocmai.vn</b> = Tất cả học sinh, <b class="clrBlack">all_teachers@hocmai.vn</b> = Tất cả giáo viên!</label>
			<?php else:?>
				<input type="text" value="<?php echo $model->getReceivedUser()->email;?>" disabled="disabled"/>
			<?php endif;?>
		</div>
		
	</div>

	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Nội dung thông báo&nbsp;<span class="required">*</span></label>
		</div>
		<div class="col col-lg-9">
			<?php $model->content = Yii::app()->controller->getPost('Notification[content]', $model->content);?>
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

</div><!-- form -->


