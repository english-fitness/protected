<?php
$baseurl = Yii::app()->baseurl."/teacher";
$menuLeftStudent  =  array(
    array("label"=>Yii::t('nav','Thông tin cá nhân'),"url"=>$baseurl."/account/index"),	
    array("label"=>Yii::t('nav','Kết nối Facebook, Gmail, Hocmai.vn'),"url"=>$baseurl."/account/socialNetwork"),
	array("label"=>Yii::t('nav','Đăng ký môn dạy'),"url"=>$baseurl."/subjectRegister/index"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>