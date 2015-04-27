<div class="page-title">
	<label class="tabPage">List of registered courses</label>
</div>
<?php $this->renderPartial('/class/myCourseTab'); ?>
<div class="session">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
            <th>Subject Course</th>
            <th class="w180">Short description</th>
            <th class="w120">Class/subjective</th>            
            <th class="w60">Number of class</th>
            <th class="w90">Start date</th>
            <th class="w150">Specific academic calendar</th>
            <th class="w90">Fee/1 student</th>
            <th class="w120">Deals tuition</th>
            <th class="w120">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($presetCourses)>0):?>
        	<?php foreach ($presetCourses as $key=>$presetCourse): ?>
            <tr class="even">
           		<td>
           			<a href="/teacher/presetRequest/view/id/<?php echo $presetCourse->id;?>">
           				<?php echo $presetCourse->title;?>
           		</td>
           		<td><?php echo $presetCourse->short_description;?></td>
           		<td>
           			<p><b>Giáo viên: </b><br/><a href="/teacher/presetRequest/viewTeacher/id/<?php echo $presetCourse->id;?>"><?php echo $presetCourse->getTeacher();?></a></p>
           			<p><b>Môn học: </b><br/><?php echo Subject::model()->displayClassSubject($presetCourse->subject_id); ?></p>
           			<p><b>Kiểu lớp: </b><?php echo PreregisterCourse::model()->displayTotalOfStudentStr($presetCourse->max_student, true);?></p>
           		</td>                
                <td class="text-center"><?php echo $presetCourse->total_of_session;?></td>
                <td><?php echo date('d/m/Y', strtotime($presetCourse->start_date)); ?></td>
                <td><?php echo $presetCourse->displaySessionPerWeek($presetCourse->session_per_week, '<br/>'); ?></td>
                <td class="text-right"><b><?php echo number_format($presetCourse->final_price_per_student());?></b></td>
                <td><?php echo ($presetCourse->status==PresetCourse::STATUS_REGISTERING)? $presetCourse->getDiscountPriceDescription(): "";?></td>
                <td>
                	<?php $statusOptions = PresetCourse::model()->statusOptions();?>
        			<p><b>Trạng thái: </b><span><?php echo isset($statusOptions[$presetCourse->status])? $statusOptions[$presetCourse->status]: 'Chưa xác định';?></span></p>
        			<?php if($presetCourse->status>=PresetCourse::STATUS_REGISTERING):?>
	        			<p><b><?php echo $presetCourse->countRegisteredStudents();?> Students already enrolled!</b></p>
	        			<p><b style="color:#468847;"><?php echo $presetCourse->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID);?> học sinh đã nộp tiền học phí!</b></p>
        			<?php endif;?>
                </td>
            </tr>
        	<?php endforeach; ?>
        <?php else:?>
        <tr><td colspan="9">No courses are registered!</td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->