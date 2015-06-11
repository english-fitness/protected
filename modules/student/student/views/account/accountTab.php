<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>
<?php
$baseurl = Yii::app()->baseurl."/student";
$menuLeftStudent  =  array(
    array("label"=>Yii::t('lang','Thông tin cá nhân'),"url"=>$baseurl."/account/index"),
    /*array("label"=>Yii::t('nav','Kết nối Facebook, Gmail, Hocmai.vn'),"url"=>$baseurl."/account/socialNetwork"),*/
);
echo Html::createNavMenu($menuLeftStudent,array('class'=>'nav nav-tabs'));
?>
