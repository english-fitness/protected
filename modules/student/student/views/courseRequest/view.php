<?php $this->renderPartial('preregisterTab',array('preregisterCourse'=>$preregisterCourse)); ?>
<?php $registration = new ClsRegistration();?>
<div class="session" style="line-height:20px;">
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Lớp/môn học</label></div>
        <div class="col col-lg-9"><?php echo Subject::model()->displayClassSubject($preregisterCourse->subject_id); ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Chủ đề đăng ký</label></div>
        <div class="col col-lg-9"><?php echo $preregisterCourse->title; ?></div>
    </div>
    <?php if($preregisterCourse->course_type!=Course::TYPE_COURSE_PRESET):?>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Kiểu lớp </label></div>
        <div class="col col-lg-9">
			<?php echo $preregisterCourse->displayTotalOfStudentStr(); ?>
		</div>
    </div>
    <?php endif;?>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Tổng số buổi/khóa</label></div>
        <div class="col col-lg-9">
		<?php
			$totalSessionOptions = $registration->totalSessionOptions();
			if(isset($totalSessionOptions[$preregisterCourse->total_of_session])){
				echo $totalSessionOptions[$preregisterCourse->total_of_session];
			}else{
				echo $preregisterCourse->total_of_session." buổi";
			}
        ?>
		</div>
    </div>
	<div class="form-element-container row">
        <div class="col col-lg-3"><label>Kiểu khóa học</label></div>
		<?php $typeOptions = Course::model()->typeOptions(); ?>
        <div class="col col-lg-9"><?php echo $typeOptions[$preregisterCourse->course_type];?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Học phí khóa học</label></div>
        <div class="col col-lg-9">
            <b style="color:blue;">
            	<?php echo number_format($preregisterCourse->getTotalFinalPrice());?>
            </b>
        </div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Ưu đãi học phí (nếu có)</label></div>
        <div class="col col-lg-9">
            <?php echo $preregisterCourse->payment_note;?>
        </div>
    </div>
	<div class="form-element-container row">
        <div class="col col-lg-3"><label>Tiền học phí đã đóng</label></div>
        <div class="col col-lg-9">
            <b style="color:blue;"><?php echo number_format($preregisterCourse->getTotalPaidAmount());?></b>
            <a href="/student/payment/history/id/<?php echo $preregisterCourse->id;?>">[Xem lịch sử thanh toán]</a>
        </div>
    </div>    
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Trạng thái thanh toán</label></div>
        <div class="col col-lg-9">
            <?php
				$paymentStatuses = ClsCourse::paymentStatuses();
				echo $paymentStatuses[$preregisterCourse->payment_status];
				if($preregisterCourse->checkDisplayNganluongPayment()):
			?>
			&nbsp;&nbsp;<a href="/student/payment/history/id/<?php echo $preregisterCourse->id;?>">
			<img border="0" src="https://www.nganluong.vn/data/images/buttons/3.gif" />
			</a>
			<?php endif;?>
        </div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Ngày bắt đầu dự kiến</label></div>
        <div class="col col-lg-9"><?php echo Common::formatDate($preregisterCourse->start_date);?> </div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Số buổi học/tuần</label></div>
        <div class="col col-lg-9">
            <?php $daysOfWeek = $registration->daysOfWeek();?>
            <?php if($calendar && (is_array($calendar)||is_object($calendar))): foreach($calendar as $kDay=>$day): ?>
            <div class="form-element-container row">
                <div class="col col-lg-2 pL0i"><label><?php echo $daysOfWeek[$kDay]; ?></label></div>
                <div class="col col-lg-10"><?php echo $calendar->$kDay;?></div>
            </div>
            <?php endforeach; else: ?>
			<?php 
				echo $preregisterCourse->session_per_week;
			endif;
			?>
        </div>
    </div>
	<div class="form-element-container row">
        <div class="col col-lg-3"><label>Yêu cầu học tập</label></div>
        <div class="col col-lg-9"><?php echo $preregisterCourse->note; ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Ngày đăng ký</label></div>
        <div class="col col-lg-9">
            <?php echo Common::formatDate($preregisterCourse->created_date);?>
        </div>
    </div>
	
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Trạng thái khóa học</label></div>
        <div class="col col-lg-9">
            <?php
            $statusOptions = $preregisterCourse->statusOptions();
            echo $statusOptions[$preregisterCourse->status]; ?>
        </div>
    </div>
	<?php if($preregisterCourse->status==PreregisterCourse::STATUS_PENDING || $preregisterCourse->status==PreregisterCourse::STATUS_REFUSED):?>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Thao tác</label></div>
        <div class="col col-lg-9">
            <a style="color: red; font-weight: bold" onclick="return confirm_value('Bạn có chắc chắn muốn hủy đăng ký khóa học: <?php echo  $preregisterCourse->title; ?>')" href="<?php echo Yii::app()->baseurl ?>/student/courseRequest/delete/id/<?php echo $preregisterCourse->id; ?>"><i class="icon-remove"></i> Hủy đăng ký</a> &nbsp;&nbsp;
        </div>
    </div>
	<?php endif; ?>

</div>
<!--.class-->