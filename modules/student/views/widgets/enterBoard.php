<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>
<?php
	$boardLink = Yii::app()->board->generateUrl($whiteboard);
    $btnClass = "btn-primary"; $checkValidBrowser = 1;
    $checkEnterTime = 1;//Check enter time in whiteboard
    if($checkBrowser && !$validBrowserVersion){
    	$checkValidBrowser = 0;//Check valid browser false
    }
    if((Yii::app()->user->getId() && Yii::app()->user->role==User::ROLE_SUPPORT)){
    	$checkEnterTime = 0;//Not check enter time
    }
?>
<a href="#" onclick='enterWhiteboard("<?php echo $whiteboard;?>", "<?php echo $boardLink; ?>", <?php echo $checkValidBrowser; ?>, <?php echo $checkEnterTime;?>)'>
<button class="btn <?php echo $btnClass;?> pT0 pR10 pB0 pL10 fs14">
	<?php echo Yii::t('lang','Vào lớp');?>
</button></a> 

