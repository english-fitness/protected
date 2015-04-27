<div class="page-title">
	<label class="tabPage">The class nearest hour</label>
</div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
	<div class="session">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th class="w150">Class</th>
        	<th>Session</th>
            <th>Objective</th>
            <th class="w150">Student</th>
            <th class="w100">Date</th>
            <th class="w100">Time slot</th>
            <th class="w100">Enter the class</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($nearestSessions)>0):?>
        	<?php foreach ($nearestSessions as $key=>$session): ?>
            <tr class="even">
           		<td><?php echo Subject::model()->displayClassSubject($session->course->subject_id);?></td>
           		<td><?php echo $session->course->title; ?></td>
                <td><?php echo $session->subject; ?></td>
                <td><?php $teacher = $session->getTeacher();
                		echo ($teacher)? $teacher: "Anonymous";
                	?>
                </td>
                <td><?php echo Common::formatDate($session->plan_start); ?></td>
                <td><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></td>
                <td>
                	<?php if($session->checkDisplayBoard()):?>
                	<div class="go">
                		<?php ClsSession::displayEnterBoardButton($session->whiteboard); ?>
                    </div>
                	<?php else:?>
                	<span><?php echo $session->displayRemainTime();?></span>
                	<?php endif;?>
                </td>
            </tr>
        	<?php endforeach; ?>
        <?php else:?>
        <tr><td colspan="7">No training session in progress or coming within one week!</td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->