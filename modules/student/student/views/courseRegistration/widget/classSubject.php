<!-- Begin Partial Widget: Generate all subject by class -->
<select name="Course[subject_id]" id="Course_subject_id" class="form-control" onchange="suggestTitles();">
	<option value="">Chọn môn...</option>
	<?php if(count($subjects)>0):?>
	<?php foreach($subjects as $subject):?>
	<option value="<?php echo $subject->id?>"><?php echo $subject->name?></option>
	<?php endforeach;?>
	<?php endif;?>
</select>
<!-- End Partial Widget -->