<div class="page-title"><label class="tabPage"> Session: <span class="aCourseTitle"><?php echo $course->title; ?></span> </label></div>
<?php
$baseurl = Yii::app()->baseurl."/teacher";
$menuCourseTab  =  array(
    array("label"=>Yii::t('nav','Session information'),"url"=>$baseurl."/class/courseProfile/id/$course->id"),
    array("label"=>Yii::t('nav','Schedule'),"url"=>$baseurl."/class/course/id/$course->id"),
    array("label"=>Yii::t('nav','View as calendar'),"url"=>$baseurl."/class/calendar/id/$course->id"),
);
echo Html::createNavMenu($menuCourseTab,array('class'=>'nav nav-tabs'));
?>