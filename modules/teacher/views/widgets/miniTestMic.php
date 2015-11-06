
<ul class="miniTestMic">
    <li>
        <i><b><span class="error">Click here to test now</span></b></i>
        <div class="frame">
            <div id="microCanvas"><canvas id="canvas" height="180" width="220"></canvas></div>
            <div class="button">
                <button id="start_button"><i class="icon-play"></i> Start</button>
                <button id="playback_button"><i class="icon-volume-up"></i> Listen</button>
                <button id="disable_audio" style="display: none" ><i class="icon-remove"></i></button>
                <button id="audio_options"><i class="icon-asterisk"></i>
                    <div class="options" style="display: none">
                        <select id="selectDeviceMicro">
                            <option value="" selected ="selected">Default</option>
                        </select>
                    </div>
                </button>

            </div>
        </div>
    </li>
</ul>
<script type="text/javascript" src="<?php echo $this->baseAssetsUrl;?>/js/chroma.js"></script>
<script type="text/javascript" src="<?php echo $this->baseAssetsUrl;?>/js/microphone.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $("#microCanvas").microphone({
			canvasWidth: 240,
			canvasHeight: 150,
            /* events */
            events: function(e) {
                var _e =e;
                $("ul.miniTestMic li").click(function(){
                    $("ul.miniTestMic .frame").slideDown();
                });
                $("#audio_options").click(function(){
                    $("#audio_options .options").slideDown();
                });
                $("body").click(function(e){
                    var _target = $(e.target).parents("ul.miniTestMic").html();
                    if(!_target) {
                        $("ul.miniTestMic .frame").slideUp();
                        _e.disable_audio();
                    }
                });
                $("body").click(function(e){
                    var _target = $(e.target).parents("#audio_options").html();
                    if(!_target) {
                        $("#audio_options .options").slideUp();
                    }
                });
            },

            /* success is doing something */
            success: function(e) {
                $.ajax({
                    type:"get",
                    url:"<?php echo Yii::app()->baseurl; ?>/<?php echo $this->getModule()->id ?>/testCondition/testMic",
                    success: function() {
                        $(".loadNoticeTestMic").removeClass("error").addClass("msg");
                        $(".loadNoticeTestMic").html("(You have completed checking your audio, you can try again)");
                    }
                });
                e.viewCanvas('+ Your microphone functions properly \n+ You can click "Listen" to listen again');
            },

            /* guide */
            guide: function(e) {
                var txt,validBrowserSupport = '<?php echo TestConditions::app()->validBrowserSupport(); ?>',
                    validBrowserVersion = '<?php echo TestConditions::app()->validBrowserVersion(); ?>';
                if(validBrowserSupport==""){
                    $("#start_button").hide();
                    txt = '1: You browser is not supported \n2: Please use Chrome(recommended) or Firefox';
                    return e.viewCanvas(txt);
                }else if(validBrowserVersion=="") {
                    $("#start_button").hide();
                    txt = '1: This version of your browser is not supported \n2: Please update your browser.';
                    return e.viewCanvas(txt);
                }
                var browser = '<?php echo TestConditions::app()->getBrowser(); ?>';
                txt = '1: Click "Start" \n';
                if(browser == "Chrome") {
                    txt += '2: Click "Allow" if the browser\nask you to\n';
                }else if(browser =="Firefox") {
                    txt += '2: Click "Share the selected device" if the browser ask you to \n';
                }else{
                    txt += '2: Allow using your device\nif the browser ask you to\n';
                }
                txt += '3: Speak clearly \n';
                txt += '4: Click "Listen" to listen again and make\nsure your speaker functions properly';
                return e.viewCanvas(txt);
            },

            /* play back */
            playback: function(e) {
                e.viewCanvas("You are in playback mode, if you cannot\nhear anything, please check your speaker");
            },

            /* error */
            error: function(e){
                e.viewCanvas('Your microphone did not function properly,\nplease check again, speak to the microphone clearly');
            }
        });
    });
</script>
