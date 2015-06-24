<?php

$url = Yii::app()->baseurl.'/student';



function check($controller,$action,$url) {
    $check = Yii::app()->controller->id;
    if($check == $controller)
        return "#";
    return $url.'/'.$controller.'/'.$action;

}
?>
<div class="page-title">
    <label class="tabPage"><a href="<?php echo check("courseRequest",'index',$url); ?>">Đăng ký khóa học theo yêu cầu</a></label>
    <label class="tabPage"><a href="<?php echo check("presetRequest","list",$url); ?>">Đăng ký khóa học có sẵn</a></label>
</div>