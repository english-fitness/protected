<div class="page-title">
	<label class="tabPage">Details are enrollment courses</label>
</div>
<?php $this->renderPartial('presetTab',array('presetCourse'=>$presetCourse)); ?>
<?php $registration = new ClsRegistration();?>
<div class="session" style="line-height:20px;">
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Class/subjective</label></div>
        <div class="col col-lg-9"><?php echo Subject::model()->displayClassSubject($presetCourse->subject_id); ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Teacher</label></div>
        <div class="col col-lg-9"><a href="/student/presetRequest/viewTeacher/id/<?php echo $presetCourse->id;?>"><?php echo $presetCourse->getTeacher();?></a></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Subjective</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->title; ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Type of class </label></div>
        <div class="col col-lg-9">
			<?php echo PreregisterCourse::model()->displayTotalOfStudentStr($presetCourse->max_student);?>
		</div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Total number of classes / courses</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->total_of_session;?> Number of classes</div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Fee / 1 student</label></div>
        <div class="col col-lg-9"><b style="color:blue;"><?php echo number_format($presetCourse->final_price_per_student());?></b></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Deals tuition</label></div>
        <div class="col col-lg-9"><?php echo ($presetCourse->status==PresetCourse::STATUS_REGISTERING)? $presetCourse->getDiscountPriceDescription(): ""; ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Start date scheduled</label></div>
        <div class="col col-lg-9"><?php echo date('d/m/Y', strtotime($presetCourse->start_date));?> </div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Number of sessions / week</label></div>
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
        <div class="col col-lg-3"><label>Short description</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->short_description; ?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Course description</label></div>
        <div class="col col-lg-9"><?php echo $presetCourse->description; ?></div>
    </div>
    <?php if($presetCourse->status>=PresetCourse::STATUS_REGISTERING):?>
	    <div class="form-element-container row">
	        <div class="col col-lg-3"><label class="pT5">Students register</label></div>
	        <div class="col col-lg-9">
	        	<p><b><?php echo $presetCourse->countRegisteredStudents();?> Students already enrolled!</b></p>
	        	<p><b style="color:#468847;"><?php echo $presetCourse->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID);?> học sinh đã nộp tiền học phí!</b></p>
	        </div>
	    </div>
    <?php endif;?>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label class="pT5">Status of course</label></div>
        <div class="col col-lg-9">
        	<?php $statusOptions = PresetCourse::model()->statusOptions();?>
   			<p>
   				<?php 
   					echo isset($statusOptions[$presetCourse->status])? $statusOptions[$presetCourse->status]: 'Anonymous';
   					if($presetCourse->status==PresetCourse::STATUS_PENDING):
   				?>
   				<a class="error fsBold mL50" onclick="return confirm('Are you sure you want to unsubscribe courses: <?php echo  $presetCourse->title; ?>')" href="/teacher/presetRequest/delete/id/<?php echo $presetCourse->id; ?>"><i class="icon-remove"></i> Hủy đăng ký</a>
   				<?php endif;?>
   			</p>
        </div>
    </div>
</div>
<!--.class-->