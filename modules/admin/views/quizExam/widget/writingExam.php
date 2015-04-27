<!-- Begin Partial Widget: Generate writing exam -->
<?php foreach($writingExams as $exam):?>
	<?php 
		$writingExamId = Yii::app()->session['writingExamId'];
		if((isset($writingExamId) && $writingExamId && $exam->id==$writingExamId)
			|| !isset($writingExamId) || !$writingExamId):
	?>
	<?php $checkedExam = (isset($checkedExamIds) && in_array($exam->id, $checkedExamIds))? 'checked="checked" disabled="disabled"': "";?>
		<?php if($checkedExam==""):?>
		<span class="fL w10">&nbsp;</span><input type="checkbox" <?php echo $checkedExam;?> class="fL mL10" name="writingExam[]" value="<?php echo $exam->id?>"/>
		<span class="fL">&nbsp;<b><?php echo Subject::model()->displayClassSubject($exam->subject_id)?>:</b></span>
		<span class="fL">
			&nbsp;<a href="/admin/quizExam/preview/id/<?php echo $exam->id;?>"><?php echo $exam->name;?></a>
			<span class="fs12"><i>(<b>Thời lượng:</b> <?php echo $exam->duration; ?> phút, <b>Kiểu đề:</b> <?php echo $exam->typeOptions($exam->type);?>, <b>Độ khó:</b> <?php echo $exam->levelOptions($exam->level);?>)</i></span>
		</span><br/>
		<?php endif;?>
	<?php endif;?>
<?php endforeach;?>
<!-- End Partial Widget -->