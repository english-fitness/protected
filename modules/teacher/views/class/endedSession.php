<!-- not using tab anymore
<div class="page-title"><label class="tabPage"> The training was completed</label></div>
-->
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Completed Sessions</p></div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
    <div class="session">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th>Course</th>
            <th>Session Number</th>
            <th class="w150">Student</th>
            <th class="w100">Date</th>
            <th class="w150">Time slot</th>
			<th>Revisit class</th>
			<th>Reminders</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($sessions)>0):?>
        	<?php foreach ($sessions as $key=>$session): ?>
            <tr class="even">
           		<td>
           			<a href="<?php echo Yii::app()->baseUrl; ?>/teacher/class/course/id/<?php echo $session->course_id;?>">
           				<?php echo $session->course->title; ?>
           			</a>
           		</td>
                <td><a href="<?php echo Yii::app()->baseUrl; ?>/teacher/class/session/id/<?php echo $session->id?>" title="<?php echo $session->content;?>"><?php echo $session->subject; ?></a></td>
                <td>
                <?php $sessionStudentValues = array_values($session->getAssignedStudentsArrs());
				  	echo implode(', <br/>', $sessionStudentValues);
				?>
                </td>
                <td><?php echo Common::formatDate($session->plan_start); ?></td>
                <td><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></td>
				<td>
					<div class="go" style="text-align:center">                        
                        <?php  ClsSession::displayEnterBoardButton($session->whiteboard); ?>
                    </div>
				</td>
				<td>
                    <?php if(SessionComment::checkReminderExistence($session->id)):?>
                    <a href="/teacher/sessionComment/view?sessionId=<?php echo $session->id?>">Reminders</a>
                    <?php else:?>
                    <a href="/teacher/sessionComment/update?sessionId=<?php echo $session->id?>">Add Reminders</a>
                    <?php endif;?>
                </td>
            </tr>
        	<?php endforeach; ?>
        	<?php if($pages->pageCount>1):?>
	        	<tr><td colspan="7" style="text-align:center">
	        		<?php $this->widget('CustomLinkPager', array('pages' => $pages,));?></td>
	        	</tr>
        	<?php endif;?>
        <?php else:?>
        <tr><td colspan="7">There doesn't seem to be anything here!</td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->