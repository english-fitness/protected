<div class="page-title"><label class="tabPage">Khóa học: <span class="aCourseTitle"><?php echo $presetCourse->title; ?></span> </label></div>
<?php

$baseurl = Yii::app()->baseurl."/student";
$menuLeftStudent  =  array(
	array("label"=>Yii::t('nav','Danh sách khóa học đang tuyển sinh'),"url"=>$baseurl."/presetRequest/list"),
    array("label"=>Yii::t('nav','Thông tin chi tiết khóa học'),"url"=>$baseurl."/presetRequest/view/id/$presetCourse->id"),
    array("label"=>Yii::t('nav','Thông tin giáo viên dạy'),"url"=>$baseurl."/presetRequest/viewTeacher/id/$presetCourse->id"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>