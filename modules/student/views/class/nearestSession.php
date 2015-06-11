<!-- not using tab anymore
<div class="page-title">
	<label class="tabPage">Buổi học gần nhất</label>
</div>
-->
<?php
    $userID = Yii::app()->user->id;
    $languages = User::model()->findByPk($userID)->language;
    Yii::app()->language=$languages;
?>
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;"><?php echo  Yii::t('lang','Lịch học') ;?></p></div>
<?php if(Yii::app()->controller->action->id=='nearestSession'):?>
	<script type="text/javascript">
	   	setTimeout(function(){window.location.href="/student/class/nearestSession"},60000);
	</script>
<?php endif;?>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
    <?php $this->renderPartial('/widgets/viewTest'); ?>
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
            <th class="w150"> <?php echo Yii::t('lang','Loại lớp học');?></th>
            <th><?php echo Yii::t('lang','Tên lớp học');?></th>
            <th> <?php echo Yii::t('lang','Học sinh');?> </th>
            <th class="w150"> <?php echo Yii::t('lang','Giáo viên');?> </th>
            <th class="w100"><?php echo Yii::t('lang','Ngày học');?></th>
            <th class="w100"><?php echo Yii::t('lang','Giờ học');?></th>
            <th class="w100"><?php echo Yii::t('lang','Vào lớp');?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($nearestSessions)>0):?>
        	<?php foreach ($nearestSessions as $key=>$session): ?>
            <tr class="even">
           		<td><?php echo $session->course->subject->name;?></td>
           		<td style="min-width:130px">
					<a href="<?php echo Yii::app()->baseUrl; ?>/student/class/session/id/<?php echo $session->id?>" title="<?php echo $session->content;?>">
					<?php echo $session->subject; ?></a>
				</td>
                <td>
					<?php $sessionStudentValues = array_values($session->getAssignedStudentsArrs());
						echo implode(', ', $sessionStudentValues);
					?>
				</td>
                <td><?php $teacher = $session->getTeacher();
                		echo ($teacher)? $teacher: Yii::t('lang',"Chưa xác định");
                	?>
                </td>
                <td><?php echo Common::formatDate($session->plan_start); ?></td>
                <td><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></td>
                <td>
                	<?php if($session->checkDisplayBoard(10)):?>
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
        <tr><td colspan="7"><?php echo Yii::t('lang','Không có buổi học nào đang diễn ra hoặc sắp tới trong vòng 1 tuần!');?></td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->