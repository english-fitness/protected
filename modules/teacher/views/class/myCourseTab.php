<?php
$baseurl = Yii::app()->baseurl."/teacher";
$menuCourseTeacher  =  array(
	array("label"=>Yii::t('nav','On-going session'),"url"=>$baseurl."/class/nearestSession"),
	/* REMOVE
	array("label"=>Yii::t('nav','Joined class '),"url"=>$baseurl."/class/index"),
    array("label"=>Yii::t('nav','Registered class'),"url"=>$baseurl."/presetRequest/index"),
	*/
	array("label"=>Yii::t('nav','Completed session'),"url"=>$baseurl."/class/endedSession"),
	/* REMOVE
	array("label"=>Yii::t('nav','Rearest hour'),"url"=>$baseurl."/class/attendingSession"),
	*/
);
echo Html::createNavMenu($menuCourseTeacher,array('class'=>'nav nav-tabs'));
?>