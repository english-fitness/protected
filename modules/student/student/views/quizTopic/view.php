<div class="page-title">
    <label class="tabPage">Chủ đề: <?php echo $topic->name; ?></label>
</div>
<?php $this->renderPartial('/quiz/quizTab'); ?>
<?php
	$quizIndexLink = "/student/quizTopic/index";
	$this->renderPartial('student.views.quiz.quizFilter', array('quizIndexLink'=>$quizIndexLink));
?>
<div class="form-element-container row clearfix">
	<div class="leftQuizTopic">
		<?php echo $this->renderPartial('/quizTopic/leftTopic', array('currentTopic'=>$topic)); ?>
	</div>
	<div class="mainQuizTopic">
		<div class="page_body page_topic page_topic_view">
			<?php echo $this->renderPartial('/quizTopic/breadcrumbs', array('currentTopic'=>$topic)); ?>
			<div class="topic_content"><?php echo $topic->displayTopicContent(); ?></div>
		</div>
	</div>
</div>