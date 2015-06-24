<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>

<h5><b>B) <?php echo Yii::t('lang','Kiểm tra mic và loa (với trình duyệt');?>  <b style="color: #275cb3"><?php echo TestConditions::app()->getBrowser(); ?></b>)</b></h5>
<br/>
<!--
<p><div class="content"><img src="<?php echo Yii::app()->baseurl; ?>/media/images/help/mic_<?php echo TestConditions::app()->getBrowser('name'); ?>1.jpg"></div> </p>
-->
<div id="frameCanvasTestMic" class="fL" style="width:420px;">
    <div id="controls">
        <button id="start_button"><i class="icon-play"></i><?php echo Yii::t('lang','Ghi âm');?> </button>
        <button id="playback_button" style="display: none"><i class="icon-volume-up"></i><?php echo Yii::t('lang','Nghe lại');?> </button>
        <button id="disable_audio" style="display: none" ><i class="icon-remove"></i><?php echo Yii::t('lang','Tắt chế độ ghi âm');?> </button>
    </div>
    <div id="dCanvas">
        <canvas id="canvas" width="400" height="200" ></canvas>
    </div>
</div>
<?php $user = Yii::app()->user->getData();
	if($user->status < User::STATUS_ENOUGH_AUDIO && $user->role==User::ROLE_STUDENT):
?>
	<script type="text/javascript">
		//After record function for student
		function listenOk(){
			$.ajax({
			    type:"post",
			    data:'update=true',
			    url:"<?php echo Yii::app()->baseurl; ?>/<?php echo $this->getModule()->id ?>/testCondition/userUpdateStatus"
			});
			setTimeout(function(){window.location.href="/student/presetRequest/index"},2000);
		}
		function testMicCancel(){
			window.location.href = "/student/presetRequest/index";
		}
	</script>
	<div id="RecordTrueConfirm" class="fL" style="width:420px;height:200px; display:none; border: 1px solid #CCCCCC;">
		<p class="text-center" style="background-color:#CCCCCC;"><b style="color:red;"><?php echo Yii::t('lang','Bạn có nghe rõ không?');?></b></p>
		<button id="btnListenOk" onclick="listenOk();" class="fL pL5 pR5 w200 mL5"><b><?php echo Yii::t('lang','Nghe rõ, tiếp tục bước tiếp theo');?></b></button>
		<button id="btnListenFalseCancel" onclick="testMicCancel()" class="pL5 pR5 w200 mL5"><b><?php echo Yii::t('lang','Không nghe rõ, bỏ qua bước này');?></b></button>
	</div>
	<div id="RecordFalseConfirm" class="fL" style="width:420px;height:200px; display:none; border: 1px solid #CCCCCC;">
		<p class="text-center" style="background-color:#CCCCCC;"><b style="color:red;"><?php echo Yii::t('lang','Micro của bạn hoạt động ko tốt, vui lòng kiểm tra lại');?>!</b></p>
		<button id="btnListenFalseCancel" onclick="testMicCancel()" class="pL5 pR5 w200 mL5"><b><?php echo Yii::t('lang','Bỏ qua bước này');?></b></button><br/>
	</div>
<?php endif;?>
<div style="clear: both"></div>
<br/>
<p>
    <?php 
        if($languages=='vi'){
    ?>
    <div class="content"><img src="<?php echo Yii::app()->baseurl; ?>/media/images/help/mic_<?php echo TestConditions::app()->getBrowser('name'); ?>1.png"></div> 
    <?php 
        }
    ?>
    <?php 
        if($languages=='en'){
    ?>
    <div class="content"><img src="<?php echo Yii::app()->baseurl; ?>/media/images/help/mic_Chrome1e.png"></div> 
    <?php 
        }
    ?>
</p>
<div class="content">
    <?php echo Yii::t('lang','Sau 4 bước trên nếu bạn không gặp lỗi gì thì có thể tham gia chương trình của chúng tôi');?>
    .<br/>
    <?php echo Yii::t('lang','Nếu bạn không ghi âm hoặc nghe lại được vui lòng liên hệ với chúng tôi để được tư vấn cụ thể hơn');?> <br/>
    <?php echo Yii::t('lang','Bạn có thể gọi vào số hotline 0961.00.50.57 hoặc để lại lời nhắn gồm');?> <strong><?php echo Yii::t('lang','Tên và số điện thoại');?></strong> <?php echo Yii::t('lang','trong ô LiveChat "Cần hỗ trợ?" ở góc dưới bên phải, đội ngũ tư vấn của chúng tôi sẽ gọi điện hỗ trợ bạn');?><br/>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->baseurl;?>/media/js/microphone.js"></script>
<script type="text/javascript">
    (function() {
        $(document).ready(function(){
            /*config microphone*/
            $("#dCanvas").microphone({

                /* events */
                events: function(e) {
                    var _e =e;
                    $("body").click(function(e){
                        var _target = $(e.target).parents("#frameCanvasTestMic").html();
                        if(!_target) {
                            $("ul.miniTestMic .frame").slideUp();
                            _e.disable_audio();
                        }
                    });
                },

                canvasWidth:400,
                canvasHeight:230,

                /* canvasOptions */
                canvasOptions:{
                    font:"18px arial",
                    lineHeight:"30",
                    y:60
                },

                /* play back */
                playback: function(e) {
                   e.viewCanvas("Bạn đang trong chế độ nghe lại, nếu như  \n sau 10 giây nếu bạn vẫn không  nghe thấy  \nâm thanh thì hãy kiểm tra lại loa đã \nhoạt động chưa?");
                   <?php $user = Yii::app()->user->getData();
	               		if($user->status < User::STATUS_ENOUGH_AUDIO && $user->role==User::ROLE_STUDENT):
	               ?>
	 	            $('#RecordTrueConfirm').show();
	 	            $('#RecordFalseConfirm').hide();
	 	           <?php endif;?>
                },

                /* guide */
                guide: function(e) {
                    var browser = '<?php echo TestConditions::app()->getBrowser(); ?>',txt;
                    txt = '1: <?php echo Yii::t('lang','Bấm');?> "<?php echo Yii::t('lang','ghi âm');?>" \n';
                    if(browser == "Chrome") {
                        txt += '2: <?php echo Yii::t('lang','Bấm');?> "<?php echo Yii::t('lang','cho phép');?>" <?php echo Yii::t('lang','nếu trình duyệt hỏi');?> \n';
                    }else if(browser =="Firefox") {
                        txt += '2: <?php echo Yii::t('lang','Bấm');?> "<?php echo Yii::t('lang','cho phép');?>" <?php echo Yii::t('lang','nếu trình duyệt hỏi');?> \n';
                    }else{
                        txt += '2: Cho phép sử dụng thiết bị nếu trình duyệt hỏi';
                    }
                    txt += '(<?php echo Yii::t('lang','Xem ảnh hướng dẫn ở dưới');?>) \n';
                    txt += '3: <?php echo Yii::t('lang','Nói gần mic, rõ ràng');?> \n';
                    txt += '4: <?php echo Yii::t('lang','Nghe lại để kiểm tra loa');?> \n';
                    e.viewCanvas(txt);
                },

                /* success */
                success: function(e){
                   $("#playback_button").click();
                },

                /* error */
                error: function(e){
                    e.viewCanvas('+ Micro hoạt động không tốt \n+ Vui lòng "ghi âm" lại \n+ Nói gần mic, rõ ràng');
                    <?php $user = Yii::app()->user->getData();
	               		if($user->status < User::STATUS_ENOUGH_AUDIO && $user->role==User::ROLE_STUDENT):
	               ?>
	 	            $('#RecordTrueConfirm').hide();
	 	            $('#RecordFalseConfirm').show();
	 	           <?php endif;?>
                }
            });
        });
    })();

</script>
