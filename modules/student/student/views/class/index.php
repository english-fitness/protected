<div class="page-title">
	<label class="tabPage">Danh sách khóa học</label>
</div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
	<div class="session">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th class="w150">Lớp/môn học</th>
            <th>Chủ đề khóa học</th>
            <th class="w150">Giáo viên</th>
            <th class="w60">Số buổi</th>
            <th class="w100">Ngày bắt đầu</th>
            <th class="w100">Ngày kết thúc</th>
            <th class="w100">Trạng thái</th>
        </tr>
        </thead>
        <tbody>
        <?php if($studentCourses):
        	 foreach($studentCourses as $course):
        ?>
        <tr>
        	<td><?php echo $course->subject->class->name.' - '.$course->subject->name;?></td>
            <td><a href="<?php echo Yii::app()->baseUrl; ?>/student/class/course/id/<?php echo $course->id;?>"><?php echo $course->title;?></a></td>
            <td><?php echo ($course->teacher_id)? $course->getTeacher(): "Chưa xác định"; ?></td>
            <td><?php echo $course->countSessions(); ?></td>
            <td><?php echo $course->getFirstDateInList('ASC'); ?></td>
            <td><?php echo $course->getFirstDateInList('DESC'); ?></td>
            <td><?php echo $course->getStatus(); ?></td>
        </tr>
        <?php endforeach;
        	else:
        ?>
        <tr>
            <td colspan="7">Không tìm thấy dữ liệu</td>
        </tr>
        <?php endif; ?>
    </table>
	</div>
</div>
<!--.class-->