<div class="page-title">
    <label class="tabPage">Ôn tập lý thuyết</label>
</div>
<?php $this->renderPartial('/quiz/quizTab'); ?>
<?php
	$quizIndexLink = "/student/quizTopic/index";
	$this->renderPartial('student.views.quiz.quizFilter', array('quizIndexLink'=>$quizIndexLink));
?>
<div class="form-element-container row clearfix">
	<div class="leftQuizTopic">
		<?php echo $this->renderPartial('/quizTopic/leftTopic'); ?>
	</div>
	<div class="mainQuizTopic"></div>
</div>