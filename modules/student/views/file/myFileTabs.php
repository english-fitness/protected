<?php
$menuCourseTeacher  =  array(
	array("label"=>Yii::t('lang',"student_documents"),"url"=>"/student/file/index"),
	array("label"=>Yii::t('lang',"public_library"),"url"=>"/student/file/publicLibrary"),
);
echo Html::createNavMenu($menuCourseTeacher,array('class'=>'nav nav-tabs'));
?>
