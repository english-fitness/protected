 <?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        if($languages!=''){
            Yii::app()->language=$languages;
        }
    ?>
<div id="viewTest">
    <div class="browser">
        <span><b ><span class="error"><?php echo Yii::t('lang','Lưu ý');?>:</span></b> <?php echo Yii::t('lang','Kiểm tra audio trước khi vào học để tránh ảnh hưởng đến buổi học');?>.  <?php $this->renderPartial("student.views.widgets.miniTestMic"); ?>
        <?php if(isset(Yii::app()->session["testMic"]) or Yii::app()->session["testMic"] ==true): ?>
           <span class="loadNoticeTestMic msg"><?php echo Yii::t('lang','(Bạn đã kiểm tra loa và microphone thành công, bạn có thể thử lại lần nữa)');?></span>
        <?php else: ?>
            <span class="loadNoticeTestMic error"></span>
        <?php endif; ?>
    </span>
    </div>
</div>

