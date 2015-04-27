<!-- Begin Partial Widget: Generate all subject by class -->
<select id="Course_teacher_id" name="Course[teacher_id]">
	<option value=''>Chọn giáo viên...</option>
	<?php foreach($teachers as $teacher):?>
		<option value="<?php echo $teacher->id?>">
		<?php echo $teacher->fullName().' ('.$teacher->email.')';?>
		</option>
	<?php endforeach;?>
</select>
<!-- End Partial Widget -->