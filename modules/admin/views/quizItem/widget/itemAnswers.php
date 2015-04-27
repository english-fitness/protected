<div id="divItemId<?php echo $quizItem->id;?>" class="form-element-container row">
	<?php if($quizItem->parent_id>0):?>
		<div class="col col-lg-2">
			<textarea rows="6" cols="50" class="h40" name="ItemContent<?php echo $quizItem->id;?>" placeholder="Nội dung câu hỏi con"><?php echo $quizItem->content;?></textarea>
		</div>
		<div class="col col-lg-10">
	<?php endif;?>
	<?php
		$itemAnswers = $quizItem->generateAnswers();
		foreach($itemAnswers as $key=>$value):
			$radioChecked = (isset($quizItem->correct_answer) && $quizItem->correct_answer==$key)? 'checked="checked"': "";
	?>
		<div class="col col-lg-3">
			<input type="text" value="<?php echo $key;?>" class="fL w40 fsBold" disabled="disabled"/>
			<textarea rows="6" cols="50" name="ItemAnswers<?php echo $quizItem->id;?>[<?php echo $key;?>]" class="fL h40 <?php echo ($quizItem->parent_id>0)? 'w150': 'w200';?>"><?php echo $value;?></textarea>
			<input type="radio" class="fL mT12i" name="CorrectAnswer<?php echo $quizItem->id;?>" value="<?php echo $key;?>" <?php echo $radioChecked;?> />
			<?php if($key=='D' && $quizItem->parent_id>0):?>
				<?php if(!(isset($parentStatus) && $parentStatus==QuizItem::STATUS_APPROVED)):?>
					<a class="btn-remove mT10 fR" title="" href="javascript:deleteSubItem(<?php echo $quizItem->id;?>);"></a>
				<?php endif;?>
			<?php endif;?>
		</div>
	<?php endforeach;?>
	<?php echo ($quizItem->parent_id>0)? '</div>': ""; ?>
</div>