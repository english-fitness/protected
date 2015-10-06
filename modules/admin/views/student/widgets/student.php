<?php
	function getGenderDisplay($gender){
		switch ($gender) {
			case '0':
				return "Chưa xác định";
				break;
			case '1':
				return "Nữ";
				break;
			case '2':
				return "Nam";
				break;
			default:
				break;
		}
	}
?>
<div style="width:320px; font-size:14px" class="container">
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>ID:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->user_id;?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Tài khoản:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->user->username;?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Họ tên:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->user->fullname();?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Email:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->user->email;?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Điện thoại:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo Common::formatPhoneNumber($student->user->phone);?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Năm sinh:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->user->birthday?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Giới tính:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo getGenderDisplay($student->user->gender)?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Trạng thái:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->statusOptions($student->user->status)?></span>
		</div>
	</div>
	<div class="row">
		<h3><b>Thông tin liên hệ</b></h3>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Họ tên:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->contact_name?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Điện thoại:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo Common::formatPhoneNumber($student->contact_phone);?></span>
		</div>
	</div>
	<div class="row">
		<div class="col col-xs-4 pL0i">
			<span class="fL"><b>Email:</b></span>
		</div>
		<div class="col col-xs-8 pL0i">
			<span class="fL"><?php echo $student->contact_email?></span>
		</div>
	</div>
</div>