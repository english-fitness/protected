<!-- not using tab anymore
<div class="page-title"><label class="tabPage"> Schedule</label></div>
-->
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Schedule</p></div>
<?php if(Yii::app()->controller->action->id=='nearestSession'):?>
	<script type="text/javascript">
	   	setTimeout(function(){window.location.href="/teacher/class/nearestSession"},60000);
	</script>
<?php endif;?>
<?php $this->renderPartial('myCourseTab'); ?>
<?php $this->renderPartial('student.views.widgets.viewTest'); ?>
<div class="details-class">
    <div class="session">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th class="w150">Type of Class </th>
			<!-- Remove it for now
        	<th>Class Name</th>
			-->
            <th class="w150">Session Number</th>
            <th class="w250">Attendees</th>
            <th class="w100">Date</th>
            <th class="w100">Time</th>
            <th class="w100">Enter The Class</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($nearestSessions)>0):?>
        	<?php foreach ($nearestSessions as $key=>$session): ?>
            <tr class="even">
           		<td><?php echo $session->course->subject->class->name.' - '.$session->course->subject->name;?></td>
				<!-- Remove course title for now
           		<td><a href="<?php echo Yii::app()->baseUrl; ?>/teacher/class/course/id/<?php echo $session->course_id;?>">
           				<?php echo $session->course->title; ?>
           			</a>
				</td>
				-->
                <td style="min-width:120px"><a href="<?php echo Yii::app()->baseUrl; ?>/teacher/class/session/id/<?php echo $session->id?>" title="<?php echo $session->content;?>"><?php echo $session->subject; ?></a></td>
                <td>
                <?php $sessionStudentValues = array_values($session->getAssignedStudentsArrs());
				  	echo implode(', ', $sessionStudentValues);
				?>
                </td>
                <td><?php echo Common::formatDate($session->plan_start); ?></td>
                <td><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></td>
                <td>
                	<?php if(!$session->checkDisplayBoard(15)):?>
                       	<p><span><?php echo $session->displayRemainTime();?></span></p>
                    <?php endif;?>
                	<?php if($session->checkDisplayBoard(15)):?>
                	<div class="go">                        
                        <?php  ClsSession::displayEnterBoardButton($session->whiteboard); ?>
                    </div>
                	<?php endif;?>
                </td>
            </tr>
        	<?php endforeach; ?>
        <?php else:?>
        <tr><td colspan="7">No session in progress or coming within 1 week!</td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->