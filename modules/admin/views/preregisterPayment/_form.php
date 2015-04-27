<?php
/* @var $this SubjectController */
/* @var $model Subject */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl.'/admin/preregisterPayment'; ?>';
	}
	$(document).on("click",".datepicker",function(){
        $(this).datepicker({
            "dateFormat":"yy-mm-dd"
        }).datepicker("show");;
    });
</script>	
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'preregisterPayment',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm phiếu thu học phí' : 'Chỉnh sửa phiếu thu học phí';?></h2>
	    </div>
	    <?php if($model->isNewRecord || (!$model->isNewRecord && $model->created_user_id==Yii::app()->user->id)):?>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        </div>
	    </div>
	    <?php endif;?>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label>Thanh toán cho đơn xin học</label>
		</div>
		<div class="col col-lg-9">
			<?php if(isset($preCourse)):
				$totalPaidAmount = $preCourse->getTotalPaidAmount();
				$remainAmount = $preCourse->final_price-$totalPaidAmount;
				if($remainAmount<0) $remainAmount = 0;//Remain amount
			?>
			<?php echo CHtml::link($preCourse->title, Yii::app()->createUrl("admin/preregisterCourse/view/id/$preCourse->id"));?>
			<?php echo $form->hiddenField($model,'precourse_id', array());?>
			<br/><label class="hint">Tiền học phí thực tế: <b><?php echo number_format($preCourse->final_price);?></b>
				, Tiền học phí đã đóng: <b><?php echo number_format($totalPaidAmount);?></b>
				, Tiền học phí còn thiếu: <b><?php echo number_format($remainAmount);?></b>
			</label>
			<?php endif;?>
		</div>
	</div>
	<?php $disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'transaction_id'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'transaction_id', $disabledAttrs); ?>
			<?php echo $form->error($model,'transaction_id'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'paid_amount'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'paid_amount', $disabledAttrs); ?>
			<?php echo $form->error($model,'paid_amount'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_method'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'payment_method', $disabledAttrs); ?>
			<?php echo $form->error($model,'payment_method'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_date'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'payment_date', array_merge($disabledAttrs, array('class'=>'datepicker', 'readonly'=>'readonly'))); ?>
			<?php echo $form->error($model,'payment_date'); ?>
			<label class="hint">Ngày nộp học phí, định dạng yyyy-mm-dd. Ví dụ <b><?php echo date('Y-m-d');?></b></label>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php $noteDisabledAttrs = (!$model->isNewRecord && $model->created_user_id!=Yii::app()->user->id)? array('disabled'=>'disabled'): array();?>
			<?php echo $form->textArea($model,'note', array_merge(array('rows'=>6, 'cols'=>50, 'style'=>'height:6em'), $noteDisabledAttrs)); ?>
			<?php echo $form->error($model,'note'); ?>
		</div>
	</div>
	<div class="clearfix h25">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->