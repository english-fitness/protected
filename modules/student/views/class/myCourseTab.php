<?php
$baseurl = Yii::app()->baseurl."/student";
$menuLeftStudent  =  array(
	array("label"=>Yii::t('nav','Buổi học gần nhất'),"url"=>$baseurl."/class/nearestSession"),
	array("label"=>Yii::t('nav','Khóa học đã/đang tham gia'),"url"=>$baseurl."/class/index"),
    array("label"=>Yii::t('nav','Khóa học đã đăng ký'),"url"=>$baseurl."/courseRequest/list"),	
	array("label"=>Yii::t('nav','Buổi học đã hoàn thành'),"url"=>$baseurl."/class/endedSession"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>