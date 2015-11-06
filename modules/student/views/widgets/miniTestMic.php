<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>

<ul class="miniTestMic">
    <li>
        <i><b><span class="error"><?php echo Yii::t('lang','Click để kiểm tra ngay');?></span></b></i>
        <div class="frame">
            <div id="microCanvas"><canvas id="canvas" width="220px" height="130px"></canvas></div>
            <div class="button">
                <button id="start_button"><i class="icon-play"></i><?php echo Yii::t('lang','Bắt đầu');?> </button>
                <button id="playback_button"><i class="icon-volume-up"></i><?php echo Yii::t('lang','Nghe lại');?> </button>
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
                        $(".loadNoticeTestMic").html("(<?php echo Yii::t('lang','Bạn đã kiểm tra loa, microphone thành công. Bạn có thể thử lại lần nữa');?>)");
                    }
                });
                e.viewCanvas('+ <?php echo Yii::t('lang','Micrô đang hoạt động tốt');?> \n+ <?php echo Yii::t('lang','Bấm nghe lại để kiểm tra');?>');
            },

            /* guide */
            guide: function(e) {
                var txt,validBrowserSupport = '<?php echo TestConditions::app()->validBrowserSupport(); ?>',
                    validBrowserVersion = '<?php echo TestConditions::app()->validBrowserVersion(); ?>';
                if(validBrowserSupport==""){
                    $("#start_button").hide();
                    txt = '1: <?php echo Yii::t('lang','Trình duyệt không hỗ trợ');?> \n2: <?php echo Yii::t('lang','Vui lòng sử dụng Chrome');?>,\n <?php echo Yii::t('lang','Firefox hoặc CờRôm');?>+';
                    return e.viewCanvas(txt);
                }else if(validBrowserVersion=="") {
                    $("#start_button").hide();
                    txt = '1: <?php echo Yii::t('lang','Phiên bản trình duyệt không hỗ trợ');?> \n2: <?php echo Yii::t('lang','Vui lòng nâng cấp phiên bản');?>.';
                    return e.viewCanvas(txt);
                }
                var browser = '<?php echo TestConditions::app()->getBrowser(); ?>';
                txt = '1: <?php echo Yii::t('lang','Bấm');?> "<?php echo Yii::t('lang','ghi âm');?>" \n';
                if(browser == "Chrome") {
                    txt += '2: <?php echo Yii::t('lang','Bấm');?> "<?php echo Yii::t('lang','cho phép');?>" <?php echo Yii::t('lang','nếu trình duyệt hỏi');?> \n';
                }else if(browser =="Firefox") {
                    txt += '2: <?php echo Yii::t('lang','Bấm');?> "<?php echo Yii::t('lang','chia sẻ');?>" <?php echo Yii::t('lang','nếu trình duyệt hỏi');?> \n';
                }else{
                    txt += '2: <?php echo Yii::t('lang','Cho phép sử dụng thiết bị nếu');?> \n <?php echo Yii::t('lang','trình duyệt hỏi');?>\n';
                }
                txt += '3: <?php echo Yii::t('lang','Nói gần mic, rõ ràng');?> \n';
                txt += '4: <?php echo Yii::t('lang','Nghe lại để kiểm tra loa');?> \n';
                return e.viewCanvas(txt);
            },

            /* play back */
            playback: function(e) {
                e.viewCanvas("<?php echo Yii::t('lang','Bạn đang trong chế độ nghe lại');?>,\n<?php echo Yii::t('lang','nếu như bạn không nghe thấy âm thanh thì hãy kiểm tra lại loa đã hoạt động chưa');?>?");
            },

            /* error */
            error: function(e){
                e.viewCanvas('+ <?php echo Yii::t('lang','Micro hoạt động không tốt');?> \n+ <?php echo Yii::t('lang','Vui lòng');?> "<?php echo Yii::t('lang','ghi âm');?>" lại \n+ <?php echo Yii::t('lang','Nói gần mic, rõ ràng');?>');
            }
        });
    });
</script>
