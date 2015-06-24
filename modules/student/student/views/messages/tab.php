<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>
    
<?php
$url = Yii::app()->baseurl.'/'.$this->module->getName();



function check($controller,$action,$url) {
    $check = Yii::app()->controller->id;
    if($check == $controller)
        return "#";
    return $url.'/'.$controller.'/'.$action;

}
?>
<div class="page-title">
    <label class="tabPage"><a href="<?php echo check("account",'index',$url); ?>"><?php echo Yii::t('lang','Thông tin tài khoản');?></a></label>
    <label class="tabPage"><a href="<?php echo check("messages","send",$url); ?>"><?php echo Yii::t('lang','Tin nhắn');?></a></label>
</div>