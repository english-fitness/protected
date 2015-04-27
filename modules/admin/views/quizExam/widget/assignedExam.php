<!-- Begin Partial Widget: Generate writing exam -->
<?php foreach($assignedExams as $exam):?>
	<span class="fL">&nbsp;<b><?php echo Subject::model()->displayClassSubject($exam->subject_id)?>:</b></span>
	<span class="fL">
		&nbsp;<a href="/admin/quizExam/preview/id/<?php echo $exam->id;?>"><?php echo $exam->name;?></a>
		<span class="fs12"><i>(<b>Thời lượng:</b> <?php echo $exam->duration; ?> phút, <b>Kiểu đề:</b> <?php echo $exam->typeOptions($exam->type);?>, <b>Độ khó:</b> <?php echo $exam->levelOptions($exam->level);?>)</i></span>
	</span><br/>
<?php endforeach;?>
<!-- End Partial Widget -->