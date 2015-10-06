<style type="text/css">
    .table th{
        text-align: center;
    }
</style>
<div class="page-title"><p>List of Courses</p></div>
<div class="details-class">
    <div class="session">
	<table class="table text-center table-bordered table-striped data-grid">
        <thead>
        <tr>
            <th class="w150">Title</th>
            <th class="w150">Student(s)</th>
            <th class="w100">Level</th>
            <th class="w100">Curriculum</th>
            <th class="w80">Number of sessions</th>
            <th class="w100">Start date</th>
            <th class="w100">End date</th>
            <th class="w100"></th>
        </tr>
        </thead>
        <tbody>
        <?php if($teacherCourses):
        	 foreach($teacherCourses as $course):
        ?>
        <tr>
            <td><a href="<?php echo Yii::app()->baseUrl; ?>/teacher/class/course/id/<?php echo $course->id;?>"><?php echo $course->title;?></a></td>
            <td><?php echo implode(',', $course->getAssignedStudentsArrs())?></td>
            <td><?php echo $course->level?></td>
            <td><?php echo $course->curriculum?></td>
            <td><?php echo $course->countSessions(); ?></td>
            <td><?php echo $course->getFirstDateInList('ASC'); ?></td>
            <td><?php echo $course->getFirstDateInList('DESC'); ?></td>
            <td><?php echo CHtml::link('Progress Report', '/teacher/courseReport/course/id/'.$course->id)?></td>
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