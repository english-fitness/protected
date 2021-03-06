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
	padding:8px;
	border:1px solid black;
    vertical-align:top;
}
</style>

<div id="sessionDetail" style="border-bottom:1px solid rgb(222,222,222); padding-bottom:10px" class="clearfix">
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
    		<div class="col col-lg-3 pL0i">
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
            <div class="col-lg3 pl0i" style="margin-top:-10px; float:right">
                <a class="btn btn-primary" href="/admin/sessionComment/send?sessionId=<?php echo $session->id?>">Gửi nhận xét</a>
            </div>
    	</p>
    </div>
</div>
<div id="teacherComment" style="margin-top:10px; margin-left:15px">
	<p><b>Ghi chú của giáo viên</b></p>
	<?php if($teacherComment == null):?>
	<p>Chưa có ghi chú</p>
	<?php else:?>
	<p><?php echo nl2br($teacherComment->comment)?></p>
	<?php endif;?>
</div>
<div id="studentComment" style ="margin-top:10px; margin-left:15px">
	<p><b>Nhận xét của học sinh</b></p>
	<?php if(count($studentComment) <= 0):?>
	<p>Chưa có nhận xét</p>
	<?php else:?>
	<table id="studentCommentTable">
		<thead>
			<th style="width:200px">Học sinh</th>
			<th style="width:100px">Đánh giá</th>
			<th>Nhận xét</th>
		</thead>
		<tbody>
		<?php foreach($studentComment as $comment):?>
			<tr>
				<td><?php echo Yii::app()->user->getFullNameById($comment->user_id)?></td>
				<td><?php echo $comment->rating?></td>
				<td style="text-align:left"><?php echo ($comment->comment != null && $comment->comment != "") ? nl2br($comment->comment) : "Chưa có nhận xét"?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
	<?php endif;?>
</div>