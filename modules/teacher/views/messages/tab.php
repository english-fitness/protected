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
    <label class="tabPage"><a href="<?php echo check("messages","send",$url); ?>">Messages</a></label>
</div>