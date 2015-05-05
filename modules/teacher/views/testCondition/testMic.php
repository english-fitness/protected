<h5><b>B) Test Speaker and Microphone (using <b style="color: #275cb3"><?php echo TestConditions::app()->getBrowser(); ?></b>)</b></h5>
<br/>
<!--
<p><div class="content"><img src="<?php echo Yii::app()->baseurl; ?>/media/images/help/mic_<?php echo TestConditions::app()->getBrowser('name'); ?>1e.jpg"></div> </p>
-->
<div id="frameCanvasTestMic" class="fL" style="width:420px;">
    <div id="controls">
        <button id="start_button"><i class="icon-play"></i> Record</button>
        <button id="playback_button" style="display: none"><i class="icon-volume-up"></i> Playback</button>
        <button id="disable_audio" style="display: none" ><i class="icon-remove"></i> Stop recording</button>
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
		<p class="text-center" style="background-color:#CCCCCC;"><b style="color:red;">Does the recording work?</b></p>
		<button id="btnListenOk" onclick="listenOk();" class="fL pL5 pR5 w200 mL5"><b>Yes, proceed to next part</b></button>
		<button id="btnListenFalseCancel" onclick="testMicCancel()" class="pL5 pR5 w200 mL5"><b>No, skip this part</b></button>
	</div>
	<div id="RecordFalseConfirm" class="fL" style="width:420px;height:200px; display:none; border: 1px solid #CCCCCC;">
		<p class="text-center" style="background-color:#CCCCCC;"><b style="color:red;">Your microphone does not function properly, please check again!</b></p>
		<button id="btnListenFalseCancel" onclick="testMicCancel()" class="pL5 pR5 w200 mL5"><b>Skip</b></button><br/>
	</div>
<?php endif;?>
<div style="clear: both"></div>
<br/>
<p><div class="content"><img src="<?php echo Yii::app()->baseurl; ?>/media/images/help/mic_<?php echo TestConditions::app()->getBrowser('name'); ?>1e.png"></div> </p>
<div class="content">
    After all tests are passed, you can start using our program.<br/>
    If the recording or playback did not goes well, you may contact us for more information. <br/>
    Hotline:<strong> 0961.00.50.57</strong> or leave us a message with your <strong>name and phone number</strong> in the LiveChat pannel in the bottom right, our support team will contact you ASAP.<br/>
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

                canvasWidth:495,
                canvasHeight:240,

                /* canvasOptions */
                canvasOptions:{
                    font:"18px arial",
                    lineHeight:"30",
                    y:60
                },

                /* play back */
                playback: function(e) {
                   e.viewCanvas("You are in playback mode, after\n10 seconds, if you still don't here anything\nplease check if the speaker \nfunctions properly");
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
                    txt = '1: Press "Record" \n';
                    if(browser == "Chrome") {
                        txt += '2: Press "Allow" if the browser asks you to \n';
                    }else if(browser =="Firefox") {
                        txt += '2: Press "Share selected device" if the browser asks you to \n';
                    }else{
                        txt += '2: Allow sharing device if the browser asks you to';
                    }
                    txt += '(Please see the instruction below) \n';
                    txt += '3: Speak to the mic clearly \n';
                    txt += '4: Listen to the playback to make sure the speaker functions\nproperly';
                    e.viewCanvas(txt);
                },

                /* success */
                success: function(e){
                   $("#playback_button").click();
                },

                /* error */
                error: function(e){
                    e.viewCanvas('+ Your microphone did not function properly. \n+ Please press the "Record" again\n+ Speak to the mic clearly');
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
