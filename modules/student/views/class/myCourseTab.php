<?php
$baseurl = Yii::app()->baseurl."/student";
$menuLeftStudent  =  array(
	array("label"=>Yii::t('lang','Buổi học gần nhất'),"url"=>$baseurl."/class/nearestSession"),
	/* REMOVE THESE TABS
	array("label"=>Yii::t('nav','Khóa học đã/đang tham gia'),"url"=>$baseurl."/class/index"),
    array("label"=>Yii::t('nav','Khóa học đã đăng ký'),"url"=>$baseurl."/courseRequest/list"),	
	*/
	array("label"=>Yii::t('lang','Buổi học đã hoàn thành'),"url"=>$baseurl."/class/endedSession"),
	array("label"=>Yii::t('lang','student_schedule_calendar_view'),"url"=>$baseurl."/schedule/calendar"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>