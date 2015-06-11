<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>
    
<?php
$baseurl = Yii::app()->baseurl."/".$this->getModule()->id;
$menuLeftStudent  =  array(
    array("label"=>Yii::t('lang','Gửi tin nhắn cho '.Yii::app()->params['copyright']),"url"=>$baseurl."/messages/send"),
    array("label"=>Yii::t('lang','Hộp tin nhắn đến'),"url"=>$baseurl."/messages"),
    array("label"=>Yii::t('lang','Tin nhắn đã gửi'),"url"=>$baseurl."/messages/sent"),
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs AjaxLoadPage'));
?>
