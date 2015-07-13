<?php
$baseurl = Yii::app()->baseurl."/teacher";
$menuCourseTeacher  =  array(
	array("label"=>"My documents","url"=>$baseurl."/file/index"),
	array("label"=>"Public Library","url"=>$baseurl."/file/publicLibrary"),
);
echo Html::createNavMenu($menuCourseTeacher,array('class'=>'nav nav-tabs'));
?>
