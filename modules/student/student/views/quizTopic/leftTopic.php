<div class="page_body page_topic clearfix">
	<?php if(isset($_SESSION['quizCurrentSubject'])):
		$parentTopicAttrs = array('parent_id'=>0, 'status'=>QuizTopic::STATUS_APPROVED, 'subject_id'=>$_SESSION['quizCurrentSubject']);
		$parentTopics = QuizTopic::model()->findAllByAttributes($parentTopicAttrs);
		if(count($parentTopics)>0): foreach($parentTopics as $topic):
			if(trim($topic->content)!="" || $topic->countChildren()>0):
	?>
	<div class="row">
		<?php $topicActiveCss = (isset($_SESSION['quizParentTopic']) && $topic->id==$_SESSION['quizParentTopic'])? 'topic_active': "";?>
        <div class="row_style <?php echo $topicActiveCss;?>">
            <a href="/student/quizTopic/view/id/<?php echo $topic->id;?>" class="pL0i">
                <i class="quiz_icon_topic"></i>
                <span class="text"><?php echo $topic->name;?></span>
                <?php 
                	if($topicActiveCss=='topic_active'){
                		echo '<div class="pL25 pT10">';
                		$subTopics = QuizTopic::model()->generateTopicsBySubject($_SESSION['quizCurrentSubject'], $subPrevStr="&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;", false, $_SESSION['quizParentTopic']);
                		foreach($subTopics as $key=>$name){
                			$subActiveClass = (isset($currentTopic->id) && $key==$currentTopic->id)? 'fsBold clrBlack': 'fsNormal';
                			echo '<p><a href="/student/quizTopic/view/id/'.$key.'" class="pL0i '.$subActiveClass.'">'.$name.'</a></p>';
                		}
                		echo '</div>';
                	}
                ?>
            </a>
        </div>
	</div>
    <?php endif; endforeach;?>
    <?php else: ?>
        <p class="pL15"><span class="error">Không tìm thấy chủ đề nào phù hợp!</span></p>
    <?php endif;
     endif;?>
</div>