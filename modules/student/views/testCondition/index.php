<!--
<div class="page-title">
    <label class="tabPage">Kiểm tra loa, mic</label>
</div>
-->
<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>

<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;"><?php echo Yii::t('lang','Kiểm tra loa, microphone');?></p></div>
<!--
<?php
    $testCondition = TestConditions::app();
    $baseModule =Yii::app()->baseurl.'/'.$this->getModule()->id;
    $user= Yii::app()->user->getData();
    $userStatus = $user->status;
    $checkStatus = true;
    if($userStatus<User::STATUS_ENOUGH_PROFILE && $user->role==User::ROLE_STUDENT):
    $checkStatus = false;
?>
   <div class="content pT25 pL25">
        <b class="error"> <i class=" icon-warning-sign"></i>
            Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi kiểm tra loa, mic
            <a href="<?php echo $baseModule; ?>/account/index">( Cập nhật thông tin cá nhân )</a>
        </b>
    </div>
<?php endif;?>
-->

<script type="text/javascript" src="<?php echo Yii::app()->baseurl; ?>/media/js/chroma.js"></script>

<div id="testCondition" style="line-height: 25px;">
    <h5><b><?php echo Yii::t('lang','Để tham gia chương trình học này yêu cầu bạn cần có các điều kiện sau');?>: </b></h5>
    <div class="content">
		<br>
        -<?php echo Yii::t('lang','Máy tính có kết nối internet ADSL hoặc tốt hơn');?>    <br/>
        -<?php echo Yii::t('lang','Sử dụng trình duyệt Chrome, CờRôm+, hoặc Firefox');?>  <br/>
        -<?php echo Yii::t('lang','Loa và Micro sử dụng tốt');?> <br/>
        -<?php echo Yii::t('lang','Nên có webcam');?> <br/>
    </div><br/>
    <h5><b>A) <?php echo Yii::t('lang','Kiểm tra trình duyệt');?>: </b></h5>
    <div class="content">
        <?php echo Yii::t('lang','Trình duyệt bạn đang sử dụng là');?>: <b style="color: #275cb3"><?php echo TestConditions::app()->getBrowser(); ?><?php echo Yii::t('lang','phiên bản');?>  <?php echo round(TestConditions::app()->getVersion()); ?> </b><br/>
    </div>
<?php
    $browser = $testCondition->getBrowserSupport();
    if($browser && $browser['version'] <= $testCondition->getVersion()){
?>
	<?php if (TestConditions::app()->getBrowser() != "Chrome") {?>
		<p><b style="color:red">
			<?php echo Yii::t('lang','Chú ý');?>!:</b>
			<?php echo Yii::t('lang','Bạn đang dùng trình duyệt Firefox. Chúng tôi khuyên dùng Google Chrome để có thể tương thích tốt nhất với hệ thống');?>.
			<br><a href="https://www.google.com/chrome"><?php echo Yii::t('lang','Tải Google Chrome');?></a>
		</p>
		<br>
	<?php } else {?>
        <div class="content">
            <b style="color: #0e9e19"><?php echo Yii::t('lang','Đạt yêu cầu để tham gia chương trình');?></b>
        </div><br/>
	<?php }?>
        <?php
        //if($checkStatus):
            $this->renderPartial('student.views.testCondition.testMic');
        //endif;
    }elseif($testCondition->getBrowserSupport()==null){
?>
        <b style="color: red">
            <?php echo Yii::t('lang','Trình duyệt của bạn không được hỗ trợ vui lòng sử dụng một trong  các trình duyệt Chrome, CờRôm+, hoặc Firefox');?> 
        </b><br/>
        <b style="color: #0E9E19">Vui lòng (<a href="https://www.google.com/intl/en/chrome/browser/" target="_blank"><?php echo Yii::t('lang','click  vào đây');?></a>)<?php echo Yii::t('lang','để cài đặt trình duyệt Chrome');?>  . </b>
<?php }else{?>
        <b style="color: red">
            <?php echo Yii::t('lang','Phiên bản');?> <?php echo $testCondition->getBrowser(); ?> <?php echo round(TestConditions::app()->getVersion("version")); ?> <?php echo Yii::t('lang','của bạn không được hỗ trợ xin vui lòng nâng cấp phiên bản cao hơn');?>.
        </b>
<?php } ?>
