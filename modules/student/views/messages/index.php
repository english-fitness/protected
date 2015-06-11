<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>
  
<?php $this->renderPartial('student.views.messages.tab'); ?>
<!--.Page-title-->
<?php $this->renderPartial('student.views.messages.tools'); ?>
<div class="list-message">
    <?php if($messages): foreach($messages as $message):
    ?>
    <div class="message">
        <div class="title">
            <a class="AjaxLoadPage" href="<?php Yii::app()->baseurl; ?>/<?php echo $this->getModule()->id; ?>/messages/viewInbox/id/<?php echo $message->getMessage()->id;?>"><?php echo $message->getMessage()->title;?>
                <?php if($message->read_flag ==0): ?>
                <span class="new">Mới</span>
                <?php endif; ?>
            </a>
        </div>
        <div class="author">
            <span class="name">Từ: <?php echo $message->getMessage()->getUser()->fullName(); ?>, </span>
            <span class="time">Lúc: <?php echo Common::formatDatetime($message->getMessage()->created_date); ?></span>
        </div>
    </div>
    <?php endforeach;?>
    <?php if($pages->pageCount>1):?>
      <div class="mL10">
          <?php $this->widget('CLinkPager', array('pages' => $pages,"htmlOptions"=>array("class"=>'yiiPager AjaxLoadPage')));?></td>
      </div>

    <?php endif;?>
    <?php else: ?>
        <div style="padding-left: 10px">
              <?php echo Yii::t('lang','Không có tin nhắn đến');?>.
        </div>
    <?php endif; ?>
</div>