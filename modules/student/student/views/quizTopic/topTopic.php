<div class="row clearfix">
	<div class="col col-lg-12 pL0i quizBreadcrumbs pA5">
		<span class="fL mL15"><b>Chủ đề</b></span>
		<span class="fL"><?php echo $currentTopic->displayBreadcrumbs('/student/quizTopic/view/id/', '&nbsp;>&nbsp;', '', 'topParentTopic');?></span>
	</div>
	<?php
		$topParentId = $currentTopic->id;
		if($currentTopic->parent_id!=0 && $currentTopic->countChildren()==0){
			$topParentId = $currentTopic->parent_id;
		}
		$topTopics = $currentTopic->getTopicSubset(1, $topParentId);	
		if(count($topTopics)>0):
			foreach($topTopics as $key=>$topic):
				$topicActiveCss = ($topic->id==$currentTopic->id)? 'topic_active': "";
	?>
	<?php if($key%3==0):?><div class="form-element-container row pL0i"><?php endif;?>
	<div class="col col-lg-4 pL0i">
		<a href="<?php echo $this->createUrl("view",array("id"=>$topic->id)); ?>">
			<i class="quiz_icon_topic"></i>
			<span class="text <?php echo $topicActiveCss;?>"><?php echo $topic->name;?></span>
		</a>
	</div>
	<?php if($key%3==2 || $key==(count($topTopics)-1)):?></div><?php endif;?>
	<?php endforeach; endif; ?>
</div>