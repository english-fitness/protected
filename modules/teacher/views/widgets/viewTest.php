<div id="viewTest">
    <div class="browser">
    <span><b ><span class="error">Note:</span></b> Please check your audio before entering the class to avoid later problem.  <?php $this->renderPartial("/widgets/miniTestMic"); ?>
        <?php if(isset(Yii::app()->session["testMic"]) or Yii::app()->session["testMic"] ==true): ?>
           <span class="loadNoticeTestMic msg"><br>&nbsp&nbsp&nbsp(You have completed checking your audio. You can try again if you like)</span>
        <?php else: ?>
            <span class="loadNoticeTestMic error"></span>
        <?php endif; ?>
    </span>
    </div>
</div>

