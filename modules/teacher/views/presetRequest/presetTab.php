<div class="page-title"><label class="tabPage">Session: <span class="aCourseTitle"><?php echo $presetCourse->title; ?></span> </label></div>
<?php
$baseurl = Yii::app()->baseurl."/teacher";
$menuPresetCourse  =  array(
	array("label"=>Yii::t('nav','List of registered courses'),"url"=>$baseurl."/presetRequest/index"),
    array("label"=>Yii::t('nav','Details courses'),"url"=>$baseurl."/presetRequest/view/id/$presetCourse->id"),
    array("label"=>Yii::t('nav','Information teacher'),"url"=>$baseurl."/presetRequest/viewTeacher/id/$presetCourse->id"),
);
echo Html::createNavMenu($menuPresetCourse,array('class'=>'nav nav-tabs'));
?>