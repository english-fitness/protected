<div class="page-title">
	<label class="tabPage">Information for teachers</label>
</div>
<?php $this->renderPartial('presetTab',array('presetCourse'=>$presetCourse)); ?>
<div class="session" style="line-height:20px;">
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Teacher</label></div>
        <div class="col col-lg-9"><?php echo $teacher->fullName();?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Short description</label></div>
        <div class="col col-lg-9"><?php echo $teacherProfile->short_description;?></div>
    </div>
    <div class="form-element-container row">
        <div class="col col-lg-3"><label>Full description</label></div>
        <div class="col col-lg-9"><?php echo $teacherProfile->description;?></div>
    </div>
</div>