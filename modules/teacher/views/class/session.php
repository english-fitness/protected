<div class="page-title">Information sessions</div>
<ol class="breadcrumb">
    <li><a href="<?php echo Yii::app()->baseurl; ?>/teacher">Home</a> </li>
    <li><a href="<?php echo Yii::app()->baseurl; ?>/teacher/class/index">List of courses</a> </li>
    <li><a href="<?php echo Yii::app()->baseurl; ?>/teacher/class/course/id/<?php echo $session->course_id; ?>"><?php echo $session->course->title; ?></a> </li>
</ol>
<div class="details-class">
    <div class="session">
        <div class="form-element-container row">
			<div class="col col-lg-3"><label>Class</label></div>
			<div class="col col-lg-9"><?php echo $session->course->subject->class->name.' - '.$session->course->subject->name;?></div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Course</label></div>
			<div class="col col-lg-9"><?php echo $session->course->title;?></div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Subjective</label></div>
			<div class="col col-lg-9"><?php echo $session->subject;?></div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Student</label></div>
			<div class="col col-lg-9">
			<?php $sessionStudentValues = array_values($session->getAssignedStudentsArrs());
			  	echo implode(', ', $sessionStudentValues);
			?>
			</div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Date</label></div>
			<div class="col col-lg-9">
				<div class="fL w200"><?php echo Common::formatDate($session->plan_start); ?></div>
		    </div>
		</div>
        <div class="form-element-container row">
            <div class="col col-lg-3"><label>Time slot</label></div>
            <div class="col col-lg-9">
                <div class="fL w200"><?php echo Common::formatDuration($session->plan_start,$session->plan_duration); ?></div>
            </div>
        </div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Status</label></div>
			<div class="col col-lg-9">
				<div class="fL w200"><?php echo $session->getStatus(); ?></div>
				<div class="col col-lg-6">
					<?php if(!$session->checkDisplayBoard(10)):?>
                       	<span class="fL mT2"><?php echo $session->displayRemainTime();?></span>
                    <?php endif;?>
                	<?php if($session->checkDisplayBoard(10080)):?>
                	<div class="go fL mL15">                        
                        <?php ClsSession::displayEnterBoardButton($session->whiteboard); ?>
                    </div>
                	<?php endif;?>
				</div>
			</div>
		</div>
		<div class="form-element-container row">
			<div class="col col-lg-3"><label>Details</label></div>
			<div class="col col-lg-9"><?php echo $session->content; ?></div>
		</div>
    </div>
</div>
<!--.class-->