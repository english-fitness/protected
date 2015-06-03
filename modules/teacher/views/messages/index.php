<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Messages</p></div>
<!--.Page-title-->
<?php $this->renderPartial('teacher.views.messages.tools'); ?>
<div class="list-message">
    <?php if($messages): foreach($messages as $message):
    ?>
    <div class="message">
        <div class="title">
            <a class="AjaxLoadPage" href="<?php Yii::app()->baseurl; ?>/<?php echo $this->getModule()->id; ?>/messages/viewInbox/id/<?php echo $message->getMessage()->id;?>"><?php echo $message->getMessage()->title;?>
                <?php if($message->read_flag ==0): ?>
                <span class="new">New</span>
                <?php endif; ?>
            </a>
        </div>
        <div class="author">
            <span class="name">From: <?php echo $message->getMessage()->getUser()->fullName(); ?>, </span>
            <span class="time">At: <?php echo Common::formatDatetime($message->getMessage()->created_date); ?></span>
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
            There doesn't seem to be anything here.
        </div>
    <?php endif; ?>
</div>