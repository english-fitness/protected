<div class="page-title">
    <label class="tabPage"><?php echo $exam->name; ?></label>
</div>
<?php $this->renderPartial('/quiz/quizTab'); ?>
<form id="exam_item_form" method="post" action="<?php echo $this->createUrl('updateExamHistory',array('id'=>$exam->id)); ?>">
	<div class="page_header">
        <div class="row">
            <div class="col col-lg-12 align_center">
                <h4><?php echo $exam->name; ?></h4>
                <h5>Môn <?php echo Subject::model()->displayClassSubject($exam->subject_id); ?> - Thời lượng đề thi: <?php echo $exam->duration; ?> phút</h5>
                <h5>-------***-------</h5>
            </div>
        </div>
        <?php if($history->status==QuizExamHistory::STATUS_ENDED):?>
        <div class="row">
            <div class="history_profile text-center">
                <span>Thời gian bắt đầu: <label><?php echo Common::formatDatetime($history->actual_start); ?></label></span> &nbsp; | &nbsp;
                <span>Thời gian nộp bài: <label><?php echo Common::formatDatetime($history->actual_end); ?></label></span> &nbsp; | &nbsp;
                <span>Số điểm đạt được: <label><?php echo Common::formatScore($history->correct_percent,count($items))?> điểm</label>(<?php echo $history->correct_percent; ?>/<?php echo count($items); ?>)</span> &nbsp;
            </div>
        </div>
        <?php endif; ?>
	</div>
	<div class="page_body page_view_exam">
		<?php if($items): foreach($items as $key=>$item): ?>
			<div class="row" id="item_<?php echo ($key+1); ?>">
                <div class="row_exam">
                    <div class="row">
                        <div class="left">
                            <div class="stt"><span><?php echo ($key+1); ?></span></div>
                        </div>
                        <div class="item_content content_view">
                            <?php echo $item->content; ?>
                        </div>
                        <div class="item_content  item_answer">
                            <div class="left">
                                <label><i>Chọn đáp án đúng</i></label>
                            </div>
                            <div class="item_answer_content">
                                    <?php $answers = json_decode($item->answers);
                                    $itemHistory = $item->getItemHistoryByUser(Yii::app()->user->id);
                                    if($answers): foreach($answers as $keyAnswer=>$valueAnswer):
                                    $checked = ($itemHistory && $itemHistory->answer == $keyAnswer)?'checked="checked"':"";
                                    $disabled = ($history->status!=QuizExamHistory::STATUS_WORKING)?'disabled="disabled"':'';
                                    $isCorrectClass = ""; $wrongAnswerClass = "";
                                    if($history->status==QuizExamHistory::STATUS_ENDED){
                                    	if($item->correct_answer==$keyAnswer){
                                    		$isCorrectClass = 'correct_answer';
                                    	}
                                    	if($itemHistory && $itemHistory->answer==$keyAnswer && $itemHistory->answer!=$item->correct_answer){
                                    		$wrongAnswerClass = 'wrong_answer';
                                    	}
                                    }
                                    ?>
                                    <div class="row">
                                        <input <?php echo $checked; ?> <?php echo $disabled; ?> type="radio" name="answer[<?php echo $item->id ?>]" value="<?php echo $keyAnswer ?>" />
                                        <span  class="<?php echo $wrongAnswerClass; ?> <?php echo $isCorrectClass; ?> text"><?php echo $keyAnswer;  ?>: <?php echo $valueAnswer; ?></span>
                                    </div>
                                    <?php endforeach;endif;  ?>
                            </div>
                            <div class="explanation">
                                <div class="button" >
                                    <?php if($item->suggestion && $history->status!=QuizExamHistory::STATUS_PENDING):  ?>
                                        <a href="#" rel="button_suggestion">Gợi ý</a>
                                    <?php endif; ?>
                                    <?php if($item->explaination && $history->status==QuizExamHistory::STATUS_ENDED):  ?>
                                        <a href="#" rel="button_explanation">Lời giải</a>
                                    <?php endif; ?>
                                </div>
                                <?php if($item->explaination && $history->status==QuizExamHistory::STATUS_ENDED):  ?>
                                    <div class="view button_explanation" style="display: none"><?php echo $item->explaination; ?></div>
                                <?php endif; ?>
                                <?php if($item->suggestion):  ?>
                                    <div class="view button_suggestion" style="display: none"><?php echo $item->suggestion; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
               </div>
			</div>
			<?php endforeach; endif; ?>
	</div>
<?php $itemsCount = count($items);
	if($itemsCount>=10): 
?>
<ul class="listQuestionCount">
   <?php for($i=1;$i<=$itemsCount;$i++): ?>
        <li  class="float_l "><a href="#item_<?php echo $i; ?>"><?php echo $i; ?></a> </li>
    <?php endfor; ?>
</ul>
<?php endif; ?>
<?php $this->renderPartial('student.views.quizExam.widgets.timerBlock', array('history'=>$history, 'exam'=>$exam)); ?>
</form>

<div class="row">
    <div class="col col-lg-12 text-center">
       <span>--------------End--------------</span>
    </div>
</div>

<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/media/js/jquery/jquery.countdown.min.js',CClientScript::POS_END); ?>
<script>
	$(function() {
        $(".explanation .button a").click(function(){
            var rel = $(this).attr('rel');
            $('.explanation').find('.view:not(".'+rel+'")').hide();
            $(this).parents('.explanation').find('.'+rel).stop().slideToggle();
            return false;
        });
		<?php if($itemsCount>=10):  ?>
		 $(document).scroll(function(){
            var scrollTop = $(this).scrollTop();
            if(scrollTop>180){
                $('.listQuestionCount').slideDown();
            }else{
                $('.listQuestionCount').slideUp();
            }
        });
        $('.listQuestionCount a').click(function(){
            var id = $(this).attr('href');
            var top = $(id).offset().top;
            $('html, body').animate({
                scrollTop: (parseInt(top) - 100)
            }, 500);
            return false;
        });
        <?php endif; ?>
		
		<?php if($history->status==QuizExamHistory::STATUS_WORKING): ?>
		$(".remaining_time").countdownTime({
            totalSeconds:'<?php echo $exam->getRemainingTime(); ?>',
            endStart: function(event){
                $("#exam_item_form").submit();
            }
        });
        setInterval(saveExam,180000);//Auto save exam history
		$(".item_answer_content .row").click(function(e){
            $(this).find("input").prop("checked","checked");
        });
		$(".exam_submit_form_save").click(function(e){
            saveExam();
			alert("Lưu bài làm thành công");
            return false;
		});
        function saveExam(){
            $.ajax({
                type:'POST',
                url:'<?php echo $this->createUrl("AjaxSaveItems",array('id'=>$exam->id));?>',
                data:$("#exam_item_form").serialize()
            });
        }
		<?php endif; ?>
	}); 
</script>