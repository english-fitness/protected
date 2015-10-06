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
		<div class="col col-lg-3"><label>Report File</label></div>
		<div class="col col-lg-9">
            <input type="file" name="report_file" style="line-height:10px">
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
		<div class="col col-lg-1">
			<button class="btn btn-primary">Save report</button>
		</div>
		<div class="col col-lg-1" style="margin-left:40px">
			<a class="btn btn-primary" href="<?php echo Yii::app()->request->urlReferrer?>">Cancel</a>
		</div>
	</div>
<?php $this->endWidget();?>
</div>