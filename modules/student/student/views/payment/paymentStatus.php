<div class="page-title"> <label class="tabPage">
	<span class="aCourseTitle">Trạng thái thanh toán của khóa học</span></label>
</div>
<div class="form-element-container row">
    <div class="col col-lg-12">
        
	    <?php if(isset($paymentStatus) && $paymentStatus==PreregisterCourse::PAYMENT_STATUS_PAID):?>
	    	<p class="pT10"><b style="color: #0e9e19">Bạn vừa thanh toán học phí thành công, chúng tôi sẽ sắp xếp lớp, chọn giáo viên và liên hệ sớm với bạn!</b></p>
			<p>Cần thêm thông tin vui lòng liên hệ hotline: 0969496795</p>
			<p>Chúc bạn học tập tốt!</p>
		<?php else:?>
			<p class="pT10"><b class="error">Quá trình thanh toán không thành công, bạn vui lòng thực hiện lại!</b></p>
			<p>Cần thêm thông tin vui lòng liên hệ hotline: 0969496795</p>
		<?php endif;?>
	    <script type="text/javascript">
	    	setTimeout(function(){window.location.href="/student/courseRequest/view/id/<?php echo $preregisterCourse->id;?>"},3000);
	   	</script>
	</div>
</div>