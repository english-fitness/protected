<?php
$baseurl = Yii::app()->baseurl."/".$this->getModule()->id;
$menuLeftStudent  =  array(
    array("label"=>Yii::t('nav','Send a message to '.Yii::app()->params['copyright']),"url"=>$baseurl."/messages/send"),
    array("label"=>Yii::t('nav','Inbox'),"url"=>$baseurl."/messages"),
    array("label"=>Yii::t('nav','Sent Messages'),"url"=>$baseurl."/messages/sent"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs AjaxLoadPage'));
?>