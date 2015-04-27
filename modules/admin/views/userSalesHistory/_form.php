<?php
/* @var $this UserSalesHistoryController */
/* @var $model UserSalesHistory */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/student/';
	}
	//Remove SaleHistory
	function removeSaleHistory(saleHistoryId){
		var checkConfirm = confirm("Bạn có chắc chắn muốn xóa lịch sử tư vấn này?");
		if(checkConfirm){
			window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/userSalesHistory/delete/id/'+saleHistoryId;
		}
	}
	$(document).on("click",".datepicker",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
</script>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-sales-history-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm lịch sử tư vấn' : 'Sửa lịch sử tư vấn';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        </div>
	    </div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'sale_date'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'sale_date'); ?>
			<?php echo $form->error($model,'sale_date'); ?>
			<label class="hint">Định dạng ngày tư vấn yyyy-mm-dd H:i. Ví dụ <?php echo date('Y-m-d H:i');?></label>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Ngày tư vấn tiếp theo (nếu có)</label>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'next_sale_date', array('class'=>'datepicker')); ?>
			<?php echo $form->error($model,'next_sale_date'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'sale_note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'sale_note',array('size'=>60,'maxlength'=>256)); ?>
			<?php echo $form->error($model,'sale_note'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'sale_status'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'sale_status',array('size'=>60,'maxlength'=>80)); ?>
			<?php echo $form->error($model,'sale_status'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3"><?php echo $form->labelEx($model,'sale_question')?></div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'sale_question', array('rows'=>6, 'cols'=>5, 'style'=>'height:6em;')); ?>
			<?php echo $form->error($model,'sale_question'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3"><?php echo $form->labelEx($model,'user_answer')?></div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'user_answer', array('rows'=>6, 'cols'=>5, 'style'=>'height:6em;')); ?>
			<?php echo $form->error($model,'user_answer'); ?>
		</div>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->