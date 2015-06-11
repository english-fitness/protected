<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>

<?php $this->renderPartial('student.views.messages.tab'); ?>
<!--.Page-title-->
<?php $this->renderPartial('student.views.messages.tools'); ?>

<div class="list-message">
    <?php if($messages): foreach($messages as $message): ?>
        <div class="message">
            <div class="title">
            	<a class="AjaxLoadPage" href="/<?php echo $this->getModule()->id; ?>/messages/viewSent/id/<?php echo $message->id;?>"><?php echo $message->title;?></a>
            </div>
            <div class="author">
                <span class="time">Lúc: <?php echo Common::formatDatetime($message->created_date); ?></span>
            </div>
            <span class="perform mR10">
            	<?php $deleteMessageLink = '/'.$this->getModule()->id.'/messages/deleteSentMessage/id/'.$message->id;?>
            	<a class="AjaxLoadPage" href="<?php echo $deleteMessageLink;?>">
            		<i class="icon-remove"></i><span class="error">Hủy</span>
            	</a>
            </span>
        </div>
    <?php endforeach;?>
        <?php if($pages->pageCount>1):?>
            <div class="mL10">
                <?php $this->widget('CLinkPager', array('pages' => $pages,"htmlOptions"=>array("class"=>'yiiPager AjaxLoadPage')));?></td>
            </div>
       <?php endif;?>
    <?php else: ?>
        <div style="padding-left: 10px">
            <?php echo Yii::t('lang','Không có tin nhắn gửi đi');?>.
        </div>
    <?php endif; ?>
</div>
