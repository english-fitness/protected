<?php $this->renderPartial('student.views.messages.tab'); ?>
<?php $this->renderPartial('student.views.messages.tools'); ?>

<div class="message_view pL15">
    <div class="author">
        <span class="name">Gửi đến:
            <?php $getAllRecipient = $message->getAllRecipient();
                foreach($getAllRecipient as $recipient) {
                    echo $recipient->getUser()->fullName().",";
                }
            ?>
        </span>
        <span class="time">Lúc: <?php echo Common::formatDatetime($message->created_date); ?></span>
    </div>
    <div class="content"><?php echo $message->content; ?></div>
</div>
<!--.Page-title-->