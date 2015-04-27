<!-- Begin Partial Widget: Generate all subject by class -->
<select name="SubjectSuggestion[subject_id]" id="SubjectSuggestion_subject_id">
	<option value="">Chọn môn...</option>
	<?php if(count($subjects)>0):?>
	<?php foreach($subjects as $subject):?>
	<option value="<?php echo $subject->id?>"><?php echo $subject->name?></option>
	<?php endforeach;?>
	<?php endif;?>
</select>
<!-- End Partial Widget -->