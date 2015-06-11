<div class="page-title"><label class="tabPage">Đăng ký khóa học</label></div>
<?php
	$registration = new ClsRegistration();
	$currentAction = Yii::app()->controller->action->id;
	$registerSteps = array('index','schedule','review','preferTeacher','finish');
	$stepAttrs = array();//Step attributes
	foreach($registerSteps as $step){
		$stepAttrs[$step]['class'] = ($step==$currentAction)? "class='active'": "";
		$stepAttrs[$step]['style'] = "style='color:gray;cursor:default;'";
		$stepAttrs[$step]['link'] = "#";//Init step link
		if($registration->isActivatedStep($step)){
			$stepAttrs[$step]['link'] = Yii::app()->baseUrl.'/student/courseRegistration/'.$step;
			$stepAttrs[$step]['style'] = "style='color:#325da7 ;font-weight:bold;'";
		}
	}
?>
<ul class="nav nav-tabs" style="margin: 5px;">
    <li <?php echo $stepAttrs["index"]["class"]; ?>><a href="<?php echo $stepAttrs["index"]["link"];?>" <?php echo $stepAttrs["index"]['style'];?>>Chọn môn</a></li>
    <li <?php echo $stepAttrs["schedule"]["class"]; ?>><a href="<?php echo $stepAttrs["schedule"]["link"];?>" <?php echo $stepAttrs["schedule"]['style'];?>>Đặt lịch học</a></li>
    <li <?php echo $stepAttrs["review"]["class"]; ?>><a href="<?php echo $stepAttrs["review"]["link"];?>" <?php echo $stepAttrs["review"]['style'];?>>Xem lịch</a></li>
    <li <?php echo $stepAttrs["preferTeacher"]["class"]; ?>><a href="<?php echo $stepAttrs["preferTeacher"]["link"];?>"  <?php echo $stepAttrs["preferTeacher"]['style'];?>>Giáo viên</a></li>
    <li <?php echo $stepAttrs["finish"]["class"]; ?>><a href="<?php echo $stepAttrs["finish"]["link"];?>" <?php echo $stepAttrs["finish"]['style'];?>>Hoàn thành</a></li>
</ul>