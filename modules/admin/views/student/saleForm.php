<?php
/* @var $this StudentController */
/* @var $model User */
/* @var $form CActiveForm */
?>
<script type="text/javascript">
	function cancel(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/admin/student/';
	}
	//Allow edit html object field
	function allowEdit(htmlObject){
		$(htmlObject).removeAttr('readonly');
	}
	//Allow change html object field
	function allowChangeSaleUser(){
		$("#Student_sale_user_id").removeAttr("disabled");
	}
	function checkNewHistory(){
		if($('#chkAddNewHistory').prop('checked')){
			$('.addNewSaleHistory').css('display', '');
		}else{
			$('.addNewSaleHistory').css('display', 'none');
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
	'id'=>'user-form',
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
<fieldset>
	<?php 
		$readonlyAttrs = (!$model->isNewRecord)? array('readonly'=>'readonly','ondblclick'=>'allowEdit(this)'): array();
		$disabledAttrs = (!$model->isNewRecord)? array('disabled'=>'disabled'):array();
	?>
	<legend>Thông tin cá nhân</legend>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Họ và tên:&nbsp;</label><?php echo $model->fullName();?>
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
			<label>Khối lớp:&nbsp;</label><?php $student->displayClass($model->id);?>
		</div>
		<div class="col col-lg-4">
			<label>Ngày sinh:&nbsp;</label><?php echo ($model->birthday)? date('d/m/Y', strtotime($model->birthday)):"";?>
		</div>
		<div class="col col-lg-4">
			<label>Giới tính:&nbsp;</label>
			<?php $genderOptions = array(0=>'Nữ', 1=>'Nam');
				echo $genderOptions[$model->gender];
			?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-4">
			<label>Trạng thái:&nbsp;</label><?php echo $model->statusOptions($model->status);?>
		</div>
		<div class="col col-lg-4">
			<label>Họ tên bố:&nbsp;</label><?php echo $student->father_name;?>
			(<b>ĐT của bố:&nbsp;</b><?php echo $student->father_phone;?>)
		</div>
		<div class="col col-lg-4">
			<label>Họ tên mẹ:&nbsp;</label><?php echo $student->mother_name;?>
			(<b>ĐT của mẹ:&nbsp;</b><?php echo $student->mother_phone;?>)
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend>Ghi chú chăm sóc, tư vấn
		<?php if(!$model->isNewRecord):?>
			<label class="hint fR mR20"><span class="clrRed">Click đúp vào các trường dữ  liệu cần sửa, để cho phép thay đổi giá trị</span></label>
		<?php endif;?>
	</legend>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($student,'care_status'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->dropDownList($student,'care_status', $student->careStatusOptions(), array()); ?>
				<?php echo $form->error($student,'care_status'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($student,'sale_user_id', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php $salesUserOptions = Student::model()->getSalesUserOptions(true, "---Người tư vấn---");?>
					<?php echo $form->dropDownList($student,'sale_user_id', $salesUserOptions, $disabledAttrs); ?>
					<?php echo $form->error($student,'sale_user_id'); ?>
					<?php if(!$student->isNewRecord && Yii::app()->user->isAdmin()):?>
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
			<?php echo $form->labelEx($student,'sale_status'); ?>
		</div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($student,'sale_status', array_merge($readonlyAttrs, array('size'=>60,'maxlength'=>80))); ?>
				<?php echo $form->error($student,'sale_status'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($student,'last_sale_date', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php echo $form->textField($student,'last_sale_date', array('class'=>'datepicker','placeholder'=>'Định dạng ngày tư vấn cuối yyyy-mm-dd')); ?>
					<?php echo $form->error($student,'last_sale_date'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($student,'sale_note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($student,'sale_note',array_merge($readonlyAttrs, array('rows'=>6, 'cols'=>5, 'style'=>'height:4em;'))); ?>
			<?php echo $form->error($student,'sale_note'); ?>
		</div>	
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<fieldset>
	<?php $checkedNewHistory = isset($_POST['chkAddNewHistory'])? true: false;?>
	<legend>
		<input type="checkbox" id="chkAddNewHistory" name="chkAddNewHistory" value="1" <?php echo ($checkedNewHistory)? 'checked="checked"': "";?> onclick="checkNewHistory();" >
		<span class="clrRed">Thêm lịch sử chăm sóc, tư vấn</span> <span class="fs13">(Chọn nếu bạn muốn thêm lịch sử chăm sóc, tư vấn)</span>
	</legend>
	<div class="form-element-container row addNewSaleHistory" <?php echo ($checkedNewHistory)?'': 'style="display:none;"'?>>
		<div class="col col-lg-3"><?php echo $form->labelEx($saleHistory,'sale_date')?></div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php if(!$saleHistory->sale_date) $saleHistory->sale_date = date('Y-m-d H:i');?>
				<?php echo $form->textField($saleHistory,'sale_date', array('placeholder'=>'Định dạng ngày tư vấn yyyy-mm-dd H:i')); ?>
				<?php echo $form->error($saleHistory,'sale_date'); ?>
				<label class="hint">Định dạng ngày tư vấn yyyy-mm-dd H:i. Ví dụ <?php echo date('Y-m-d H:i');?></label>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($saleHistory,'next_sale_date', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php echo $form->textField($saleHistory,'next_sale_date', array('class'=>'datepicker','placeholder'=>'Ngày tư vấn tiếp theo yyyy-mm-dd')); ?>
					<?php echo $form->error($saleHistory,'next_sale_date'); ?>
					<label class="hint">Ngày dự định tư vấn tiếp theo (nếu có)</label>
				</div>
			</div>
		</div>
	</div>
	<div class="form-element-container row addNewSaleHistory" <?php echo ($checkedNewHistory)?'': 'style="display:none;"'?>>
		<div class="col col-lg-3"><?php echo $form->labelEx($saleHistory,'sale_note')?></div>
		<div class="col col-lg-9">
			<div class="col col-lg-5 pL0i pR0i">
				<?php echo $form->textField($saleHistory,'sale_note', array('placeholder'=>'Ghi chú tư vấn, chăm sóc')); ?>
				<?php echo $form->error($saleHistory,'sale_note'); ?>
			</div>
			<div class="col col-lg-7 pL0i pR0i">
				<div class="col col-lg-4 pL0i text-right">
					<?php echo $form->labelEx($saleHistory,'sale_status', array('class'=>'mT10')); ?>
				</div>
				<div class="col col-lg-8 pL0i pR0i">
					<?php echo $form->textField($saleHistory,'sale_status', array('placeholder'=>'Trạng thái tư vấn. Ví dụ L0, L1...')); ?>
					<?php echo $form->error($saleHistory,'sale_status'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="form-element-container row addNewSaleHistory" <?php echo ($checkedNewHistory)?'': 'style="display:none;"'?>>
		<div class="col col-lg-3"><?php echo $form->labelEx($saleHistory,'sale_question')?></div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($saleHistory,'sale_question', array('rows'=>6, 'cols'=>5, 'style'=>'height:5em;')); ?>
			<?php echo $form->error($saleHistory,'sale_question'); ?>
		</div>
	</div>
	<div class="form-element-container row addNewSaleHistory" <?php echo ($checkedNewHistory)?'': 'style="display:none;"'?>>
		<div class="col col-lg-3"><?php echo $form->labelEx($saleHistory,'user_answer')?></div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($saleHistory,'user_answer', array('rows'=>6, 'cols'=>5, 'style'=>'height:5em;')); ?>
			<?php echo $form->error($saleHistory,'user_answer'); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>	
<fieldset>
	<legend><a href="/admin/userSalesHistory/index?student_id=<?php echo $student->user_id;?>">Lịch sử các lần chăm sóc, tư vấn của Sale</a></legend>
	<?php if(count($saleUserHistories)>0):
		foreach($saleUserHistories as $history):
	?>
	<div class="form-element-container borderGrey">
		<div class="form-element-container row">
			<div class="col col-lg-4">
				<a href="/admin/userSalesHistory/update/id/<?php echo $history->id;?>">
					<i class="btn-edit"></i>&nbsp;<b>Ngày tư vấn:&nbsp;</b><?php echo ($history->sale_date)? date('m/d/Y, H:i', strtotime($history->sale_date)): "";?>
				</a>
			</div>
			<div class="col col-lg-4">
				<label>Trạng thái tư vấn:&nbsp;</label><?php echo $history->sale_status;?>
			</div>
			<div class="col col-lg-4">
				<label>Ngày tư vấn tiếp theo (nếu có):&nbsp;</label><?php echo ($history->next_sale_date)? date('m/d/Y', strtotime($history->next_sale_date)): "";?>
			</div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-8">
				<label>Ghi chú tư vấn:&nbsp;</label><?php echo $history->sale_note;?>
			</div>
			<div class="col col-lg-4">
				<label>Người tạo lịch sử tư vấn:&nbsp;</label><?php echo ($history->created_user_id)? User::model()->displayUserById($history->created_user_id):"";?>
			</div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-12">
				<label>Nội dung tư vấn:&nbsp;</label><?php echo $history->sale_question;?>
			</div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-12">
				<label>Kết quả tư vấn:&nbsp;</label><?php echo $history->user_answer;?>
			</div>
		</div>
	</div>
	<?php endforeach; endif;?>
</fieldset>
<div class="clearfix h30">&nbsp;</div
<?php $this->endWidget(); ?>

</div><!-- form -->