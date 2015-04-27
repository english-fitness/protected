<div id="clock" class="clock_background_<?php echo ($history->status!=QuizExamHistory::STATUS_ENDED)? 1: 0; ?>">
	<span class="remaining_time">
		<?php if($history->status==QuizExamHistory::STATUS_ENDED): ?>
			<?php echo $history->displayScore(true);?> điểm
		<?php endif; ?>
	</span>
	<div class="button align_center">
		<?php if($history->status==QuizExamHistory::STATUS_WORKING): ?>
			<input type="hidden" name="submissions" value="Nộp bài"/>
			<button type="button" class="btn btn-primary exam_submit_form_save">Lưu bài</button>
			<button type="submit" value="<?php echo $exam->id; ?>" name="submissions_form_exam" class="btn btn-primary exam_submit_form">Nộp bài</button>
		<?php elseif($history->status==QuizExamHistory::STATUS_PENDING): ?>
			<a href="/student/quizExam/restart/id/<?php echo $exam->id;?>"><button type="button" class="pA5 btn btn-primary exam_submit_form">Bắt đầu làm bài</button></a>
		<?php elseif($history->status==QuizExamHistory::STATUS_ENDED): ?>
			<a href="/student/quizExam/restart/id/<?php echo $exam->id;?>"><button type="button" class="pA5 btn btn-primary exam_submit_form">Làm lại bài</button></a>
			<?php $this->renderPartial('student.views.quizExam.widgets.shareButton'); ?>
		<?php endif; ?>
	</div>
</div>