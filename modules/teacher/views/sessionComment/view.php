<?php
/* @var $this SessionCommentController */
/* @var $model SessionComment */
?>

<style>
#studentCommentTable{
	width:100%;
	border:1px solid black;
	text-align:center;
}
#studentCommentTable th{
	padding:4px;
	border:1px solid black;
	text-align:center;
}
#studentCommentTable td{
	padding:4px;
	border:1px solid black;
}
</style>

<div class="page-title">
	<p style="color:#ffffff; text-align:center; font-size:20px;">Session Reminders</p>
</div>
<?php $this->renderPartial('teacher.views.class.myCourseTab'); ?>
<div id="sessionDetail" style="border-bottom:1px solid rgb(222,222,222); padding-bottom:10px" class="clearfix">
	<div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Subject:</b>&nbsp;<?php echo $session->subject?></span>
    		</div>
    	</p>
    </div>    
    <div class="col col-lg-12">
    	<div class="col col-lg-8 pL0i"><b>Students:</b>&nbsp;
	        <?php $courseStudentValues = array_values($session->getAssignedStudentsArrs());?>
			<?php $students = implode(', ', $courseStudentValues);?>
			<?php echo ($students!="")? $students: "No student assigned";?>
		</div>
	</div>
	<div class="col col-lg-12">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Date :</b>&nbsp;
    			<?php echo date('d/m/Y', strtotime($session->plan_start))?></span>
    		</div>
    		<div class="col col-lg-8 pL0i">
    			<span class="fL">
					<b>Time:</b>
					&nbsp;
					<?php 
						echo date('H:i', strtotime($session->plan_start)) . 
							 ' - ' .
							 date('H:i', strtotime('+' . $session->plan_duration . ' minute', strtotime($session->plan_start)))
					?>
				</span>
    		</div>
    	</p>
    </div>
</div>
<div id="teacherComment" style="margin-top:10px;margin-left:15px">
	<p><b>Session Reminders</b></p>
	<?php if($teacherComment == null):?>
	<p>No reminders</p>
	<?php else:?>
	<p><?php echo nl2br($teacherComment->comment)?></p>
	<?php endif;?>
</div>
<div style="margin-left:15px">
	<a href="/teacher/class/endedSession"><< Back</a>
	<a style="margin-left:30px;" href="/teacher/sessionComment/update?sessionId=<?php echo $session->id?>"><?php echo ($teacherComment != null) ? "Edit" : "Add Reminders"?></a>
</div>
<script>
	$(".nav.nav-tabs").children().first().next().addClass("active");
</script>