<div class="page-title">
	<label class="tabPage"><span class="aCourseTitle"><?php echo $titlePage; ?></span></label>
</div>
<?php $this->renderPartial('/class/myCourseTab'); ?>
<div class="session">
    <div class="list">
        <table class="table table-bordered table-striped data-grid">
            <thead>
                <tr>
                    <th class="w200">Lớp/Môn</th>
                    <th>Chủ đề</th>
                    <th class="w150">Kiểu khóa học</th>
                    <th class="w150">Trạng thái thanh toán</th>
                    <th class="w100">Ngày đăng ký</th>
                    <th class="w100">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
            <?php if($preregisterCourse):
                $statusOptions = PreregisterCourse::model()->statusOptions();
                $paymentTypes = PreregisterCourse::model()->paymentTypes();
                $paymentStatuses = PreregisterCourse::model()->paymentStatuses();
                $preCourseTypes = Course::model()->typeOptions();
                foreach($preregisterCourse as $preCourse): ?>
                <tr class="even">
                    <td><?php echo Subject::model()->displayClassSubject($preCourse->subject_id); ?></td>
                    <td><a href="<?php echo Yii::app()->baseUrl; ?>/student/courseRequest/view/id/<?php echo $preCourse->id;?>"><?php echo $preCourse->title; ?></a></td></td>
                    <td><?php echo $preCourseTypes[$preCourse->course_type]; ?></td>
                    <td>
                    	<p><?php echo $paymentStatuses[$preCourse->payment_status]; ?></p>
                    	<?php if($preCourse->checkDisplayNganluongPayment()):?>
                    	<a href="/student/payment/history/id/<?php echo $preCourse->id;?>"><img border="0" src="https://www.nganluong.vn/data/images/buttons/3.gif" /></a>
						<?php endif;?>
                    </td>
                    <td><?php echo Common::formatDate($preCourse->created_date); ?></td>
                    <td><?php echo $statusOptions[$preCourse->status]; ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr class="even">
                   <td colspan="6">Không tìm thấy dữ liệu</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!--.class-->