<div class="page-title"><label class="tabPage"> Khóa học: <span class="aCourseTitle"><?php echo $course->title; ?></span> </label></div>
<?php
$baseurl = Yii::app()->baseurl."/student";
$menuLeftStudent  =  array(
    array("label"=>Yii::t('nav','Thông tin khóa học'),"url"=>$baseurl."/class/courseProfile/id/$course->id"),
    array("label"=>Yii::t('nav','Danh sách lịch học'),"url"=>$baseurl."/class/course/id/$course->id"),
    array("label"=>Yii::t('nav','Xem dạng lịch'),"url"=>$baseurl."/class/calendar/course/$course->id"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>