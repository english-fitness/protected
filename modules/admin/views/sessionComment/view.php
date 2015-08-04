<?php
/* @var $this SessionCommentController */
/* @var $model SessionComment */
?>

<div id="sessionDetail">
	<div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Buổi học:</b>&nbsp;<?php echo $session->subject?></span>
    		</div>
    		<div class="col col-lg-8 pL0i">
    			<span class="fL"><b>ID:</b>&nbsp;<?php echo $session->id;?></span>
    		</div>
    	</p>
    </div>    
    <div class="col col-lg-12">
    	<div class="col col-lg-3 pL0i">
    		<b>Giáo viên:</b>&nbsp;<?php $teacher = $session->getTeacher("/admin/teacher/view/id");?>
    		<?php echo ($teacher)? $teacher: "Chưa gán giáo viên";?>
    	</div>
    	<div class="col col-lg-8 pL0i"><b>Học sinh:</b>&nbsp;
	        <?php $courseStudentValues = array_values($session->getAssignedStudentsArrs("/admin/student/view/id"));?>
			<?php $students = implode(', ', $courseStudentValues);?>
			<?php echo ($students!="")? $students: "Chưa gán học sinh";?>
		</div>
	</div>
	<div class="col col-lg-12">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Ngày học :</b>&nbsp;
    			<?php echo date('Y-m-d', strtotime($session->plan_start))?></span>
    		</div>
    		<div class="col col-lg-8 pL0i">
    			<span class="fL">
					<b>Thời gian học:</b>
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
<div id="teacherComment">
	<p>Ghi chú của giáo viên</p>
	<?php echo $teacherComment->comment?>
</div>
<div id="studentComment">
	<p>Nhận xét của học sinh</p>
</div>