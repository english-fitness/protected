
<ul class="miniTestMic">
    <li>
        <i><b><span class="error">Check</span></b></i>
        <div class="frame">
            <div id="microCanvas"><canvas id="canvas" width="220px" height="130px"></canvas></div>
            <div class="button">
                <button id="start_button"><i class="icon-play"></i> Recording</button>
                <button id="playback_button"><i class="icon-volume-up"></i> Listen again</button>
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
<script type="text/javascript" src="<?php echo Yii::app()->baseurl;?>/media/js/chroma.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseurl;?>/media/js/microphone.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $("#microCanvas").microphone({
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
                        $(".loadNoticeTestMic").html("(Bạn đã kiểm tra loa, micrô thành công. Bạn có thể thử lại lần nữa)");
                    }
                });
                e.viewCanvas('+ Micrô đang hoạt động tốt \n+ Bấm nghe lại để kiểm tra');
            },

            /* guide */
            guide: function(e) {
                var txt,validBrowserSupport = '<?php echo TestConditions::app()->validBrowserSupport(); ?>',
                    validBrowserVersion = '<?php echo TestConditions::app()->validBrowserVersion(); ?>';
                if(validBrowserSupport==""){
                    $("#start_button").hide();
                    txt = '1: Trình duyệt không hỗ trợ \n2: Vui lòng sử dụng Chrome,\n Firefox hoặc CờRôm+';
                    return e.viewCanvas(txt);
                }else if(validBrowserVersion=="") {
                    $("#start_button").hide();
                    txt = '1: Phiên bản trình duyệt không hỗ trợ \n2: Vui lòng nâng cấp phiên bản.';
                    return e.viewCanvas(txt);
                }
                var browser = '<?php echo TestConditions::app()->getBrowser(); ?>';
                txt = '1: Bấm "ghi âm" \n';
                if(browser == "Chrome") {
                    txt += '2: Bấm "cho phép" nếu trình duyệt hỏi \n';
                }else if(browser =="Firefox") {
                    txt += '2: Bấm "chia sẻ" nếu trình duyệt hỏi \n';
                }else{
                    txt += '2: Cho phép sử dụng thiết bị nếu \n trình duyệt hỏi\n';
                }
                txt += '3: Nói gần mic, rõ ràng \n';
                txt += '4: Nghe lại để kiểm tra loa \n';
                return e.viewCanvas(txt);
            },

            /* play back */
            playback: function(e) {
                e.viewCanvas("Bạn đang trong chế độ nghe lại,\nnếu như bạn không  nghe thấy  âm \nthanh thì hãy kiểm tra lại loa\nđã hoạt động chưa?");
            },

            /* error */
            error: function(e){
                e.viewCanvas('+ Micro hoạt động không tốt \n+ Vui lòng "ghi âm" lại \n+ Nói gần mic, rõ ràng');
            }
        });
    });
</script>
