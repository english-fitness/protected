<!-- Begin Partial Widget: Generate all subject by class -->
<?php foreach($students as $student):?>
	<span class="fL w10">&nbsp;</span><input type="checkbox" class="fL mL10 assignedStudent" name="assignStudents[]" value="<?php echo $student->id?>"/>
	<span class="fL">&nbsp;<?php echo $student->firstname.' '.$student->lastname.' ('.$student->email.')';?></span><br/>
<?php endforeach;?>
<!-- End Partial Widget -->