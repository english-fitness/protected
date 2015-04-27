<!-- Begin Partial Widget: Generate all Tutor Classes-->
<?php $classes = Classes::model()->findAll(array('order'=>'name ASC'));?>
<select name="tutorClasses" id="tutorClasses">
	<option value="">Chọn lớp...</option>
	<?php if(count($classes)>0):?>
	<?php foreach($classes as $class):?>
	<?php $isSelected = ($class->id==$selectedId)? true: false;?>
	<option value="<?php echo $class->id?>" <?php if($isSelected):?> selected="selected" <?php endif;?>>
		<?php echo $class->name?>
	</option>
	<?php endforeach;?>
	<?php endif;?>
</select>
<!-- End Partial Widget -->