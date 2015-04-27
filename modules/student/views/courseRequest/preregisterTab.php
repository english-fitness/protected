<div class="page-title"><label class="tabPage"> Khóa học: <span class="aCourseTitle"><?php echo $preregisterCourse->title; ?></span> </label></div>
<?php
$baseurl = Yii::app()->baseurl."/student";
$menuLeftStudent  =  array(
	array("label"=>Yii::t('nav','Quay lại danh sách khóa học đã đăng ký'),"url"=>$baseurl."/courseRequest/list"),
    array("label"=>Yii::t('nav','Chi tiết khóa học đăng ký'),"url"=>$baseurl."/courseRequest/view/id/$preregisterCourse->id"),
    array("label"=>Yii::t('nav','Lịch sử thanh toán học phí'),"url"=>$baseurl."/payment/history/id/$preregisterCourse->id"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>