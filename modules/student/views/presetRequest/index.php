<?php $this->renderPartial('/courseRequest/tab'); ?>
<?php $user= Yii::app()->user->getData(); ?>
<?php if($user->status < User::STATUS_ENOUGH_PROFILE):?>
<div class="content pT25 text-center"><i class="icon-warning-sign"></i>
  	<?php if($user->status < User::STATUS_ENOUGH_PROFILE):?>
      	<b class="error">Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi đăng ký khóa học <a href="/student/account/index">( Cập nhật thông tin cá nhân )</a></b>
    <?php else:?>
      	<b class="error">Vui lòng kiểm tra loa, micrô trước khi đăng ký khóa học <a href="/student/testCondition/index">( Tiến hành kiểm tra loa, micrô )</a></b>
    <?php endif;?>
</div>
<?php endif;?>
<div class="row-form mT15 pL20">
	 <div class="row-form fL pR15" style="width:50%;">
		<div class="row-form clearfix">
			<p class="pL15 pA5 fsBold text-center mB0" style="background-color:#CCCCCC;">KHÓA HỌC THEO YÊU CẦU</p>
			<div class="row-form clearfix" style="border: 1px solid #CCCCCC;">
				<div class="row-form fL pL30 pR15 pT10" style="height: 180px;">
					<p>+ Khóa học nhóm nhỏ (01 giáo viên với 01 học sinh hoặc 01 giáo viên với 02-03 học sinh)</p>
					<p>+ Được yêu cầu thời gian dạy phù hợp với cá nhân</p>
					<p>+ Được yêu cầu thay đổi giáo viên nếu thấy không phù hợp (tối đa 3 lần thay đổi/ 1 khoá)</p>
					<p>+ Nội dung dạy học theo yêu cầu cá nhân của học sinh, giáo viên chủ động thay đổi cho phù hợp với lực học và mục tiêu thi cử của học sinh</p>
				</div>
			</div>
		</div>
		<div class="row-form clearfix">
			<p class="pL15 pA5 fsBold text-center mB0" style="background-color:#CCCCCC;">CÁC BƯỚC THỰC HIỆN ĐĂNG KÝ</p>
			<div class="row-form clearfix pB10" style="border: 1px solid #CCCCCC;">
				<div class="row-form fL pL30 pR15 pT10" style="height: 180px;">
					<p>1) Chọn khối lớp, môn học, chủ đề và yêu cầu học tập cụ thể</p>
					<p>2) Chọn kiểu lớp, tổng số buổi</p>
					<p>3) Đặt lịch học theo thời gian yêu cầu riêng</p>
					<p>4) Xem lại và hoàn thành đăng ký khoá học</p>
					<p>5) Thực hiện thanh toán học phí</p>
					<p>6) Chờ ghép lớp với giáo viên</p>
				</div>
				<div class="row-form clearfix fL pL25">
					<?php if($user->status >= User::STATUS_ENOUGH_PROFILE):?>
						<a href="<?php echo Yii::app()->baseurl; ?>/student/courseRequest/index">
							<input type="button" name="btnRegister"  class="btn btn-primary fs13 pA5 fsBold" style="width:200px" value="Đăng ký ngay"/>
						</a>
					<?php else:?>
						<input type="button" name="btnRegister"  class="btn btn-default fs13 pA5 fsBold" style="width:200px;background-color:#CCCCCC;" disabled="disabled" value="Đăng ký ngay"/>
					<?php endif;?>
				</div>
			</div>			
		</div>
		<div class="clearfix h20">&nbsp;</div>
	 </div>
	 <div class="row-form fL pL15 pR20" style="width:50%;">
		<div class="row-form clearfix">
			<p class="pL15 pA5 fsBold text-center mB0" style="background-color:#CCCCCC;">KHÓA HỌC CÓ SẴN</p>
			<div class="row-form clearfix" style="border: 1px solid #CCCCCC;">
				<div class="row-form fL pL30 pR15 pT10" style="height: 180px;">
					<p>+ Khoá học nhóm lớn (01 giáo viên với 4-6 học sinh hoặc 01 giáo viên với 20 học sinh)</p>
					<p>+ Thời gian học đã cố định, học sinh phải tự sắp thời gian cá nhân để phù hợp với lớp học</p>
					<p>+ Giáo viên đã cố định, không được yêu cầu thay đổi giáo viên</p>
					<p>+ Nội dung dạy học đã được lên sẵn theo chủ đề cụ thể</p>
				</div>
			</div>
		</div>
		<div class="row-form clearfix">
			<p class="pL15 pA5 fsBold text-center mB0" style="background-color:#CCCCCC;">CÁC BƯỚC THỰC HIỆN ĐĂNG KÝ</p>
			<div class="row-form clearfix pB10" style="border: 1px solid #CCCCCC;">
				<div class="row-form fL pL30 pR15 pT10" style="height: 180px;">
					<p>1) Xem danh sách, thông tin các khoá học có sẵn</p>
					<p>2) Chọn khoá phù hợp</p>
					<p>3) Đăng ký tham gia</p>
					<p>4) Thực hiện thanh toán học phí</p>
					<p>5) Tham gia học theo lịch đã có</p>
				</div>
				<div class="row-form clearfix fL pL25">
					<?php if($user->status >= User::STATUS_ENOUGH_PROFILE):?>
						<a href="<?php echo Yii::app()->baseurl; ?>/student/presetRequest/list">
							<input type="button" name="btnRegister"  class="btn btn-primary fs13 pA5 fsBold" style="width:200px" value="Đăng ký ngay"/>
						</a>
					<?php else:?>
						<input type="button" name="btnRegister"  class="btn btn-default fs13 pA5 fsBold" style="width:200px;background-color:#CCCCCC;" disabled="disabled" value="Đăng ký ngay"/>
					<?php endif;?>
				</div>
			</div>			
		</div>
		<div class="clearfix h20">&nbsp;</div>
	 </div>
</div>