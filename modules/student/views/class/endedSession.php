<!-- WHY YOU USE ONE TAB GUY
<div class="page-title">
	<label class="tabPage">Buổi học đã hoàn thành</label>
</div>
-->
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Buổi học đã hoàn thành</p></div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
    <div class="session">
    <table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th class="w150">Lớp/môn học</th>
        	<th>Khóa học</th>
            <th>Chủ đề buổi học</th>
            <th class="w150">Giáo viên</th>
            <th class="w100">Ngày học</th>
            <th class="w100">Thời gian học</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($sessions)>0):?>
        	<?php foreach ($sessions as $key=>$session): ?>
            <tr class="even">
           		<td><?php echo $session->course->subject->class->name.' - '.$session->course->subject->name;?></td>
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
                		echo ($teacher)? $teacher: "Chưa xác định";
                	?>
                </td>
                <td><?php echo Common::formatDate($session->plan_start); ?></td>
                <td><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></td>
            </tr>
        	<?php endforeach; ?>
        	<?php if($pages->pageCount>1):?>
	        	<tr><td colspan="5">
	        		<?php $this->widget('CustomLinkPager', array('pages' => $pages,));?></td>
	        	</tr>
        	<?php endif;?>
        <?php else:?>
        <tr><td colspan="6">Không có buổi học nào đã hoàn thành!</td></tr>
        <?php endif;?>
        </tbody>
    </table>
    </div>
</div>
<!--.class-->