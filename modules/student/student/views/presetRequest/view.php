<div class="page-title">
	<label class="tabPage">Chi tiết khóa học đang tuyển sinh</label>
</div>
<?php $this->renderPartial('presetTab',array('presetCourse'=>$presetCourse)); ?>
<?php $registration = new ClsRegistration();?>
<div class="session" style="line-height:20px;">
	<?php $user= Yii::app()->user->getData(); ?>
	<?php if($user->status < User::STATUS_ENOUGH_PROFILE):?>
	<div class="content pT10 pB10 text-center"><i class="icon-warning-sign"></i>
	    <b class="error">Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi đăng ký khóa học <a href="/student/account/index">( Cập nhật thông tin cá nhân )</a></b>
	</div>
	<?php endif;?>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Lớp/môn học</label></div>
        <div class="col col-lg-9"><?php echo Subject::model()->displayClassSubject($presetCourse->subject_id); ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Giáo viên dạy</label></div>
        <div class="col col-lg-9"><a href="/student/presetRequest/viewTeacher/id/<?php echo $presetCourse->id;?>"><?php echo $presetCourse->getTeacher();?></a></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Chủ đề khóa học</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->title; ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Tổng số buổi/khóa</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->total_of_session;?> buổi</div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Học phí / 1 học sinh</label></div>
        <div class="col col-lg-9"><b style="color:blue;"><?php echo number_format($presetCourse->final_price_per_student());?></b></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Ưu đãi học phí</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->getDiscountPriceDescription(); ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Ngày bắt đầu dự kiến</label></div>
        <div class="col col-lg-9"><?php echo date('d/m/Y', strtotime($presetCourse->start_date));?> </div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Số buổi học/tuần</label></div>
        <div class="col col-lg-9">
            <?php 
            	$daysOfWeek = $registration->daysOfWeek();
            	$schedule = json_decode($presetCourse->session_per_week);
            ?>
            <?php if($schedule): foreach($schedule as $kDay=>$day): ?>
            <div class="form-element-container row">
                <div class="col col-lg-2 pL0i"><label><?php echo $daysOfWeek[$kDay]; ?></label></div>
                <div class="col col-lg-10"><?php echo $schedule->$kDay;?></div>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
	<div class="form-element-container row">
        <div class="col col-lg-3"><label>Mô tả ngắn</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->short_description; ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Mô tả khóa học</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->description; ?></div>
    </div>
    <div class="form-element-container row" style="border-bottom:none;">
        <div class="col col-lg-3"><label class="pT5">Đăng ký khóa học</label></div>
        <div class="col col-lg-9">
        	<?php $statusOptions = PresetCourse::model()->statusOptions();?>
        	<?php $statusOptions = PresetCourse::model()->statusOptions();?>
        			<p><b>Trạng thái: </b><span><?php echo isset($statusOptions[$presetCourse->status])? $statusOptions[$presetCourse->status]: 'Chưa xác định';?></span></p>
        	<!--p><b><?php echo $presetCourse->countRegisteredStudents();?> học sinh đã đăng ký học!</b></p>
        	<p><b style="color:#468847;"><?php echo $presetCourse->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID);?> học sinh đã nộp tiền học phí!</b></p-->
            <?php if($presetCourse->status==PresetCourse::STATUS_REGISTERING):?>
	        	<?php $registeredPreCourse = $presetCourse->checkRegisteredByStudent(Yii::app()->user->getId());?>
	            <?php if($registeredPreCourse!==false):?>
	            	<span class="error">Bạn đã đăng ký khóa học này!</span>
	            	<a href="/student/payment/history/id/<?php echo $registeredPreCourse->id;?>" class="mL15"><img border="0" src="https://www.nganluong.vn/data/images/buttons/3.gif" /></a>
	            <?php elseif($user->status >= User::STATUS_ENOUGH_PROFILE):?>
	            	<a href="/student/presetRequest/register/id/<?php echo $presetCourse->id;?>">
		            	<input type="button" name="btnRegister"  class="btn btn-primary fs13" style="padding:3px;" value="Đăng ký tham gia khóa học"/>
		            </a>
		        <?php else:?>
		        	<input type="button" name="btnRegister" disabled="disabled" class="btn btn-default fs13" style="padding:3px; background-color:#CCCCCC;" value="Đăng ký tham gia khóa học"/>
	            <?php endif;?>
	       <?php endif;?>
        </div>
    </div>
</div>
<!--.class-->