<div class="page-title"><label class="tabPage">Thông tin buổi học</label></div>
<ol class="breadcrumb">
    <li><a href="<?php echo Yii::app()->baseurl; ?>/student">Trang chủ</a> </li>
    <li><a href="<?php echo Yii::app()->baseurl; ?>/student/class/index">Danh sách khóa học</a> </li>
    <li><a href="<?php echo Yii::app()->baseurl; ?>/student/class/course/id/<?php echo $session->course_id; ?>"><?php echo $session->course->title; ?></a> </li>
</ol>
<div class="details-class">
    <div class="session" style="line-height:20px;">
    	<div class="form-element-container row">
			<div class="col col-lg-3"><label>Lớp/môn học</label></div>
			<div class="col col-lg-9"><?php echo $session->course->subject->class->name.' - '.$session->course->subject->name;?></div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Khóa học</label></div>
			<div class="col col-lg-9"><?php echo $session->course->title;?></div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Chủ đề buổi học</label></div>
			<div class="col col-lg-9"><?php echo $session->subject;?></div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Giáo viên</label></div>
			<div class="col col-lg-9"><?php echo ($session->teacher_id)?$session->getTeacher():"Chưa xác định"; ?></div>
		</div>
		<div class="form-element-container row">
            <div class="col col-lg-3"><label>Ngày học</label></div>
            <div class="col col-lg-9">
                <div class="fL w200"><?php echo Common::formatDate($session->plan_start); ?></div>
            </div>
        </div>
        <div class="form-element-container row">
            <div class="col col-lg-3"><label>Thời gian học </label></div>
            <div class="col col-lg-9">
                <div class="fL w200"><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></div>
            </div>
        </div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Trạng thái</label></div>
			<div class="col col-lg-9">
				<div class="fL w200"><?php echo $session->getStatus(); ?></div>
				<div class="col col-lg-6">
					<?php if($session->checkDisplayBoard()):?>
			        <div class="button">
			        	<?php ClsSession::displayEnterBoardButton($session->whiteboard); ?>
			        </div>
					<?php else:?>
                	<span>
                		<?php $displayRemain = $session->displayRemainTime();?>
                		<?php echo ($displayRemain)? "<b>Vào lớp:</b>&nbsp;".$displayRemain: "";?>
                	</span>
                	<?php endif;?>
				</div>
			</div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Nội dung chi tiết</label></div>
			<div class="col col-lg-9"><?php echo $session->content; ?></div>
		</div>
    </div>
</div>
<!--.class-->