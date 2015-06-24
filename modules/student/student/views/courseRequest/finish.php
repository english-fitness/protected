<?php $this->renderPartial('step',array("titlePage"=>$titlePage)); ?>
<div class="form-group pL20 pR15">
	<p class="pT10"><b class="fs14">Cảm ơn bạn đã tham gia chương trình Gia sư và Dạy kèm Tương tác Trực tuyến Daykem.Hocmai.vn</b></p>
	<p>Bạn vừa đăng ký khóa học thành công, chúng tôi sẽ tiếp nhận yêu cầu, sắp xếp lớp, chọn giáo viên và liên hệ sớm với bạn.</p>
	<?php if(isset($preCourse) && $preCourse!=NULL):
			if($preCourse->payment_type==PreregisterCourse::PAYMENT_TYPE_NOT_FREE):
	?>
		<p>
			<a href="/student/courseRequest/view/id/<?php echo $preCourse->id;?>"><b style="color:#325DA7;" >Xem lại thông tin khóa học bạn vừa đăng ký và đóng tiền học phí!</b></a>
			<a href="/student/payment/history/id/<?php echo $preCourse->id;?>" class="mL15"><img border="0" src="https://www.nganluong.vn/data/images/buttons/3.gif" /></a>
		</p>
		<?php endif;?>
		<script type="text/javascript">
	       	setTimeout(function(){window.location.href="/student/courseRequest/view/id/<?php echo $preCourse->id;?>"},3000);
	    </script>
	<?php endif;?>
	<p>Cần thêm thông tin vui lòng liên hệ hotline: 0969496795</p>
	<p>Chúc bạn học tập tốt!</p>

</div>
