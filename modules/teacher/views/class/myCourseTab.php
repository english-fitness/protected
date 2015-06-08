<?php
$baseurl = Yii::app()->baseurl."/teacher";
$menuCourseTeacher  =  array(
	array("label"=>Yii::t('nav','On-going sessions'),"url"=>$baseurl."/class/nearestSession"),
	/* REMOVE
	array("label"=>Yii::t('nav','Joined class '),"url"=>$baseurl."/class/index"),
    array("label"=>Yii::t('nav','Registered class'),"url"=>$baseurl."/presetRequest/index"),
	*/
	array("label"=>Yii::t('nav','Completed sessions'),"url"=>$baseurl."/class/endedSession"),
	/* REMOVE
	array("label"=>Yii::t('nav','Rearest hour'),"url"=>$baseurl."/class/attendingSession"),
	*/
	array("label"=>Yii::t('nav','Register schedule'),"url"=>$baseurl."/class/Registerschedule"),
);
echo Html::createNavMenu($menuCourseTeacher,array('class'=>'nav nav-tabs'));
?>
