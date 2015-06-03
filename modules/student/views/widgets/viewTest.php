<div id="viewTest">
    <div class="browser">
    <span><b ><span class="error">Lưu ý:</span></b> Kiểm tra audio trước khi vào học để tránh ảnh hưởng đến buổi học.  <?php $this->renderPartial("student.views.widgets.miniTestMic"); ?>
        <?php if(isset(Yii::app()->session["testMic"]) or Yii::app()->session["testMic"] ==true): ?>
           <span class="loadNoticeTestMic msg">(Bạn đã kiểm tra loa và microphone thành công, bạn có thể thử lại lần nữa)</span>
        <?php else: ?>
            <span class="loadNoticeTestMic error"></span>
        <?php endif; ?>
    </span>
    </div>
</div>

