<style type="text/css">
    .table th{
        text-align: center;
    }
</style>
<div class="page-title"><p><?php echo  Yii::t('lang','courses') ;?></p></div>
<div class="details-class">
	<div class="session">
    <table class="table text-center table-bordered table-striped data-grid">
        <thead>
        <tr>
            <th class="w150"><?php echo Yii::t('lang', 'course_title')?></th>
            <th class="w100"><?php echo Yii::t('lang', 'course_teacher')?></th>
            <th class="w50"><?php echo Yii::t('lang', 'course_number_of_sessions')?></th>
            <th class="w100"><?php echo Yii::t('lang', 'course_level')?></th>
            <th class="w100"><?php echo Yii::t('lang', 'course_curriculum')?></th>
            <th class="w100"><?php echo Yii::t('lang', 'course_start_date')?></th>
            <th class="w100"><?php echo Yii::t('lang', 'course_end_date')?></th>
            <th class="w100"><?php echo Yii::t('lang', 'course_status')?></th>
            <th class="w80"><?php echo Yii::t('lang', 'course_report_th')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if($studentCourses):
        	 foreach($studentCourses as $course):
        ?>
        <tr>
            <td><a href="<?php echo Yii::app()->baseUrl; ?>/student/class/course/id/<?php echo $course->id;?>"><?php echo $course->title;?></a></td>
            <td><?php echo ($course->teacher_id)? $course->getTeacher(): Yii::t('lang', 'unassigned'); ?></td>
            <td><?php echo $course->countSessions(); ?></td>
            <td><?php echo $course->level?></td>
            <td><?php echo $course->curriculum?></td>
            <td><?php echo $course->getFirstDateInList('ASC'); ?></td>
            <td><?php echo $course->getFirstDateInList('DESC'); ?></td>
            <td><?php echo $course->getStatus(); ?></td>
            <td><?php echo CHtml::link(Yii::t('lang', 'course_report_link'), "/student/courseReport/course/id/".$course->id)?></td>
        </tr>
        <?php endforeach;
        	else:
        ?>
        <tr>
            <td colspan="9">Không tìm thấy dữ liệu</td>
        </tr>
        <?php endif; ?>
    </table>
	</div>
</div>
<!--.class-->