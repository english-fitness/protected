<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'report-form',
	"htmlOptions"=>array(
		"enctype"=>"multipart/form-data"
	),
	'enableAjaxValidation'=>false,
));?>
	<div class="row">
		<div class="col col-lg-3"><label>Course</label></div>
		<div class="col col-lg-9"><span><?php echo $course->title?></span></div>
	</div>
	<div class="row">
		<div class="col col-lg-3"><label>Start date</label></div>
		<div class="col col-lg-9"><span><?php echo $course->getFirstDateInList('ASC')?></span></div>
	</div>
	<div class="row">
		<div class="col col-lg-3"><label>End date</label></div>
		<div class="col col-lg-9"><span><?php echo $course->getFirstDateInList('DESC')?></span></div>
	</div>
	<div class="row">
		<div class="col col-lg-3"><label>Student</label></div>
		<div class="col col-lg-9"><span><?php echo array_values($course->getAssignedStudentsArrs())[0]?></span></div>
	</div>
	<div class="row">
		<div class="col col-lg-3"><label>Report Type</label></div>
		<div class="col col-lg-9">
            <?php
            if ($report->report_type != null){
                $type = $report->report_type;
            } else {
                if ($course->type == Course::TYPE_COURSE_NORMAL){
                    $type = CourseReport::REPORT_TYPE_PROGRESS;
                } else {
                    $type = CourseReport::REPORT_TYPE_ENTRY;
                }
            }
            $typeOptions = array(
            	CourseReport::REPORT_TYPE_ENTRY=>"Entry Assessment",
            	CourseReport::REPORT_TYPE_PROGRESS=>"Progress Report"
        	);
            echo $form->dropdownList($report, 'report_type', $typeOptions, array(
            	"options"=>array($type=>array("selected"=>true)),
            	"style"=>"width:300px; margin-left:0",
        	));
            ?>
            <?php echo $form->error($report, 'report_type')?>
		</div>
	</div>
	<div class="row">
		<div class="col col-lg-3"><label>Report File</label></div>
		<div class="col col-lg-9">
            <input type="file" name="report_file" style="line-height:10px; margin-left:0">
            <?php echo $form->error($report, 'report_file')?>
		</div>
	</div>
	<?php if(isset($error)):?>
		<div class="row">
			<div class="col">
				<span class="error"><?php echo $error?></span>
			</div>
		</div>
	<?php endif;?>
	<div class="row">
		<div class="col-lg-1">
			<button class="btn btn-primary">Save report</button>
		</div>
		<div class="col-lg-1" style="margin-left:40px">
			<a class="btn btn-primary" href="<?php echo Yii::app()->request->urlReferrer?>">Cancel</a>
		</div>
	</div>
<?php $this->endWidget();?>
</div>