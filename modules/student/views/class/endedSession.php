<!-- WHY YOU USE ONE TAB GUY
<div class="page-title">
	<label class="tabPage">Buổi học đã hoàn thành</label>
</div>
-->
<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>
<style>
th{
	text-align:center;
}
td{
	text-align:center;
}
</style>
<div class="page-title">
	<p style="color:#ffffff; text-align:center; font-size:20px;"><?php echo Yii::t('lang','student_schedule');?></p>
</div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
    <div class="session">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th><?php echo Yii::t('lang','Khóa học');?></th>
            <th class="w150"><?php echo Yii::t('lang','Chủ đề buổi học');?></th>
            <th class="w150"><?php echo Yii::t('lang','Giáo viên');?></th>
            <th class="w100"><?php echo Yii::t('lang','Ngày học');?></th>
            <th class="w150"><?php echo Yii::t('lang','Thời gian học');?></th>
			<th class="w100"><?php echo Yii::t('lang', 'whiteboard_link')?></th>
			<th class="w150"><?php echo Yii::t('lang', 'view_comment')?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($sessions)>0):?>
        	<?php foreach ($sessions as $key=>$session): ?>
            <tr class="even">
           		<td>
           			<a href="<?php echo Yii::app()->baseUrl; ?>/student/class/course/id/<?php echo $session->course_id;?>">
           				<?php echo $session->course->title; ?>
           			</a>
           		</td>
                <td>
                	<a href="<?php echo Yii::app()->baseUrl; ?>/student/class/session/id/<?php echo $session->id?>" title="<?php echo $session->content;?>">
                		<?php echo $session->subject; ?>
                	</a>
                </td>
                <td><?php $teacher = $session->getTeacher();
                		echo ($teacher)? $teacher: Yii::t('lang',"Chưa xác định");
                	?>
                </td>
                <td><?php echo Common::formatDate($session->plan_start); ?></td>
                <td><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></td>
				<td>
					<div class="go">
                		<?php ClsSession::displayEnterBoardButton($session->whiteboard); ?>
                    </div>
				</td>
				<td><a href="/student/sessionComment/view?sessionId=<?php echo $session->id?>">Xem ghi chú</a></td>
            </tr>
        	<?php endforeach; ?>
        	<?php if($pages->pageCount>1):?>
	        	<tr><td colspan="7">
	        		<?php $this->widget('CustomLinkPager', array('pages' => $pages,));?></td>
	        	</tr>
        	<?php endif;?>
        <?php else:?>
        <tr><td colspan="7"><?php echo Yii::t('lang','Không có buổi học nào đã hoàn thành!');?></td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->