<div class="page-title"><label class="tabPage">Schedule</label></div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
    <div class="session">
	<table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th class="w150">Class</th>
            <th>Subjective</th>
            <th class="w100">Number of sessions / courses</th>
            <th class="w100">Start date</th>
            <th class="w100">End date</th>
            <th class="w100">Status</th>
        </tr>
        </thead>
        <tbody>
        <?php if($teacherCourses):
        	 foreach($teacherCourses as $course):
        ?>
        <tr>
        	<td><?php echo $course->subject->class->name.' - '.$course->subject->name;?></td>
            <td><a href="<?php echo Yii::app()->baseUrl; ?>/teacher/class/course/id/<?php echo $course->id;?>"><?php echo $course->title;?></a></td>
            <td class="text-center"><?php echo $course->countSessions(); ?></td>
            <td><?php echo $course->getFirstDateInList('ASC'); ?></td>
            <td><?php echo $course->getFirstDateInList('DESC'); ?></td>
            <td><?php echo $course->getStatus(); ?></td>
        </tr>
        <?php endforeach;
        	else:
        ?>
        <tr>
            <td colspan="4">You have not assigned any course!</td>
        </tr>
        <?php endif; ?>
    </table>
</div>
<!--.class-->