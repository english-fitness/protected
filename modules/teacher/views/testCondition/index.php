<!-- not using tab anymore
<div class="page-title">
    <label class="tabPage">Kiểm tra loa, mic</label>
</div>
-->
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Test Speaker, Microphone</p></div>
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

<script type="text/javascript" src="<?php echo Yii::app()->baseurl; ?>/media/js/chroma.js"></script>

<div id="testCondition" style="line-height: 25px;">
    <h5><b>Computer/laptop Pre-requisites: </b></h5>
    <div class="content">
    	 <b>Required:</b><br/>
        - ADSL internet connection or better   <br/>
        - Using Google Chrome or Mozilla Firefox browser <br/>
        - Microphone and Speaker works properly<br/>
         <b>Recommended:</b><br/>
		- Webcam functions properly<br/>
    </div><br/>
    <h5><b>A) Browser check: </b></h5>
    <div class="content">
        You are using: <b style="color: #275cb3"><?php echo TestConditions::app()->getBrowser(); ?> version <?php echo round(TestConditions::app()->getVersion()); ?> </b><br/>
    </div>
<?php
    $browser = $testCondition->getBrowserSupport();
    if($browser && $browser['version'] <= $testCondition->getVersion()){
?>
	<?php if (TestConditions::app()->getBrowser() != "Chrome") {?>
		<p><b style="color:red">
			Note!:</b>
			You are using <?php echo TestConditions::app()->getBrowser(); ?> browser. We recommend that you use Google Chrome for best compabilities. A download link can be found below.
			<br><a href="https://www.google.com/chrome">Download Google Chrome</a>
		</p>
		<br>
	<?php } else {?>
        <div class="content">
            <b style="color: #0e9e19">Your browser meets the requirements</b>
        </div><br/>
	<?php }?>
        <?php
        if($checkStatus):
            $this->renderPartial('teacher.views.testCondition.testMic');
        endif;
    }elseif($testCondition->getBrowserSupport()==null){
?>
        <b style="color: red">
            Your browser is not supported, please use one of these: Google Chrome or Mozilla Firefox 
        </b><br/>
        <b style="color: #0E9E19">Click (<a href="https://www.google.com/intl/en/chrome/browser/" target="_blank">here</a>)  to install Google Chrome. </b>
<?php }else{?>
        <b style="color: red">
            Version <?php echo $testCondition->getBrowser(); ?> <?php echo round(TestConditions::app()->getVersion("version")); ?> of the browser is not supported, please update your browser.
        </b>
<?php } ?>
