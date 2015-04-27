<div id="viewTest">
    <div class="browser">
    <span><b ><span class="error">Note:</span></b> Please check audio befor entering the class.  <?php $this->renderPartial("student.views.widgets.miniTestMic"); ?>
        <?php if(isset(Yii::app()->session["testMic"]) or Yii::app()->session["testMic"] ==true): ?>
           <span class="loadNoticeTestMic msg">(Have you checked the speaker, microphone success. You can try again)</span>
        <?php else: ?>
            <span class="loadNoticeTestMic error"></span>
        <?php endif; ?>
    </span>
    </div>
</div>

