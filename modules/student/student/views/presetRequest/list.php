<?php $this->renderPartial('/courseRequest/tab'); ?>
<?php $user= Yii::app()->user->getData(); ?>
<?php if($user->status < User::STATUS_ENOUGH_PROFILE):?>
<div class="content pT15 pB15 text-center"><i class="icon-warning-sign"></i>
    <b class="error">Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi đăng ký khóa học <a href="/student/account/index">( Cập nhật thông tin cá nhân )</a></b>
</div>
<?php endif;?>
<div class="details-class">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
            <th>Chủ đề khóa học</th>
            <th class="w150">Mô tả ngắn</th>
            <th class="w120">Lớp/Môn học</th>            
            <th class="w60">Số buổi</th>
            <th class="w90">Ngày bắt đầu</th>
            <th class="w150">Lịch học cụ thể</th>
            <th class="w90">Học phí/1 HS</th>
            <th class="w120">Ưu đãi học phí</th>
            <th class="w120">Đăng ký học</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($presetCourses)>0):?>
        	<?php foreach ($presetCourses as $key=>$presetCourse): ?>
            <tr class="even">
           		<td>
           			<a href="/student/presetRequest/view/id/<?php echo $presetCourse->id;?>">
           				<?php echo $presetCourse->title;?>
           		</td>
           		<td><?php echo $presetCourse->short_description;?></td>
           		<td>
           			<p><b>Giáo viên: </b><br/><a href="/student/presetRequest/viewTeacher/id/<?php echo $presetCourse->id;?>"><?php echo $presetCourse->getTeacher();?></a></p>
           			<p><b>Môn học: </b><br/><?php echo Subject::model()->displayClassSubject($presetCourse->subject_id); ?></p>
           		</td>                
                <td class="text-center"><?php echo $presetCourse->total_of_session;?></td>
                <td><?php echo date('d/m/Y', strtotime($presetCourse->start_date)); ?></td>
                <td><?php echo $presetCourse->displaySessionPerWeek($presetCourse->session_per_week, '<br/>'); ?></td>
                <td class="text-right"><b><?php echo number_format($presetCourse->final_price_per_student());?></b></td>
                <td><?php echo $presetCourse->getDiscountPriceDescription();?></td>
                <td>
                	<?php $statusOptions = PresetCourse::model()->statusOptions();?>
        			<p><b>Trạng thái: </b><span><?php echo isset($statusOptions[$presetCourse->status])? $statusOptions[$presetCourse->status]: 'Chưa xác định';?></span></p>
        			<!--p><b><?php echo $presetCourse->countRegisteredStudents();?> học sinh đã đăng ký học!</b></p>
        			<p><b style="color:#468847;"><?php echo $presetCourse->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID);?> học sinh đã nộp tiền học phí!</b></p-->
                	<?php if($presetCourse->status==PresetCourse::STATUS_REGISTERING):?>
	                	<?php 
	                		$registeredPreCourse = $presetCourse->checkRegisteredByStudent(Yii::app()->user->id);
	                		if($registeredPreCourse!==false):
	                	?>
	                		<p class="pT5"><span class="error">Bạn đã đăng ký khóa học này!</span><br/><br/>
		            			<a href="/student/payment/history/id/<?php echo $registeredPreCourse->id;?>"><img border="0" src="https://www.nganluong.vn/data/images/buttons/3.gif" /></a>
		            		</p>
		            	<?php elseif($user->status >= User::STATUS_ENOUGH_PROFILE):?>
		            		<p class="pT5"><a href="/student/presetRequest/register/id/<?php echo $presetCourse->id;?>">
			            		<input type="button" name="btnRegister"  class="btn btn-primary fs13" style="padding:3px;" value="Đăng ký ngay"/>
			            	</a></p>
			            <?php else:?>
			            	<input type="button" name="btnRegister" disabled="disabled" class="btn btn-default fs13" style="padding:3px;background-color:#CCCCCC;" value="Đăng ký ngay"/>
		            	<?php endif;?>
	            	<?php endif;?>
	            </td>
            </tr>
        	<?php endforeach; ?>
        <?php else:?>
        <tr><td colspan="9">Chưa có khóa học nào đang tuyển sinh!</td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->