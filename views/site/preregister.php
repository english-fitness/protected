<div id="popupAll">
	<?php if(isset($_GET['status']) && $_GET['status']=='success'):?>
		<p class="text-center alert-success">Bạn đã đăng ký tư vấn thành công!</p>
		<script type="text/javascript">
			setTimeout(function() {window.close();}, 3000);
		</script>
	<?php endif;?>
	<h3 class="text-center mT8">Đăng ký để nhận thông tin tư vấn trực tiếp</h3>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'preregister-user-form',
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'enableAjaxValidation'=>false,
	)); ?>
		<div id="accountRegister">
		    <div class="row-form">
				<div class="col-sm-3 label">
					Họ và tên học sinh&nbsp;<span class="clrRed">*</span>
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'fullname', array('size'=>60,'maxlength'=>256, 'placeholder'=>'Họ và tên của học sinh', 'style'=>'width:230px;')); ?>
					<b>Điện thoại&nbsp;<span class="clrRed">*</span>&nbsp;</b><?php echo $form->textField($model,'phone', array('size'=>20,'maxlength'=>20, 'style'=>'width:120px;')); ?>
				</div>
			</div>
			<div class="row-form">
				<div class="col-sm-3 label">
					Khối lớp đang học&nbsp;<span class="clrRed">*</span>
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'class_name', array('size'=>60,'maxlength'=>256, 'placeholder'=>'Ví dụ: lớp 8, 9, 11, 12 ...')); ?>
				</div>
			</div>
			<div class="row-form">
				<div class="col-sm-3 label">
					Môn học muốn được gia sư&nbsp;<span class="clrRed">*</span>
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'subject_note',array('size'=>60,'maxlength'=>256, 'placeholder'=>'Ví dụ: Toán, Lý, Hóa, Tiếng Anh ...')); ?>
				</div>
			</div>
			<div class="row-form">
				<div class="col-sm-3 label">
					Địa chỉ liên hệ
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'address',array('size'=>60,'maxlength'=>256)); ?>
				</div>
			</div>
			<div class="row-form">
				<div class="col-sm-3 label">
					<span>Họ tên phụ huynh</span>
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'parent_name', array('size'=>60,'maxlength'=>256, 'style'=>'width:230px;', 'placeholder'=>'Họ và tên của phụ huynh',)); ?>
					<b>ĐT phụ huynh:&nbsp;</b><?php echo $form->textField($model,'parent_name', array('size'=>60,'maxlength'=>256, 'style'=>'width:120px;')); ?>
				</div>
			</div>
			<div class="row-form">
				<div class="col-sm-3 label">
					Mục tiêu học tập (nếu có)
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'objective',array('size'=>60,'maxlength'=>256)); ?>
				</div>
			</div>
			<div class="row-form">
				<div class="col-sm-3 label">
					Yêu cầu về giáo viên (nếu có)
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'teacher_request',array('size'=>60)); ?>
				</div>
			</div>
			<div class="row-form">
				<div class="col-sm-3 label">
					Yêu cầu về nội dung học (nếu có) 
				</div>
				<div class="col-sm-8 value">
					<?php echo $form->textField($model,'content_request',array('size'=>60)); ?>
				</div>
			</div>
			<div class="row-form">
		            <div class="col-sm-3 label">&nbsp; </div>
		            <div class="col-sm-8 value">
		                <button type="submit"  name="save">Đăng ký để nhận tư vấn</button>
		            </div>
		        </div>
		</div>
	<?php $this->endWidget(); ?>
</div>