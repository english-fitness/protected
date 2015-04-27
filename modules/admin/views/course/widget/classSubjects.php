<!-- Begin Partial Widget: Generate all subject by class -->
<select name="Course[subject_id]" id="Course_subject_id" onchange="ajaxSubjectChange();">
	<option value="">Chọn môn học...</option>
	<?php if(count($subjects)>0):?>
	<?php foreach($subjects as $subject):?>
	<option value="<?php echo $subject->id?>" <?php if(isset($subjectId) && $subject->id==$subjectId): ?> selected="selected" <?php endif;?>>
		<?php echo $subject->name?>
	</option>
	<?php endforeach;?>
	<?php endif;?>
</select>
<!-- End Partial Widget -->