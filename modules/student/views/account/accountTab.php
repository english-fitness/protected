<?php
$baseurl = Yii::app()->baseurl."/student";
$menuLeftStudent  =  array(
    array("label"=>Yii::t('nav','Thông tin cá nhân'),"url"=>$baseurl."/account/index"),
    /*array("label"=>Yii::t('nav','Kết nối Facebook, Gmail, Hocmai.vn'),"url"=>$baseurl."/account/socialNetwork"),*/
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>
