<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Messages</p></div>
<?php $this->renderPartial('teacher.views.messages.tools'); ?>

<div class="message_view pL15">
    <div class="author">
        <span class="name">To:
            <?php $getAllRecipient = $message->getAllRecipient();
                foreach($getAllRecipient as $recipient) {
                    echo $recipient->getUser()->fullName().",";
                }
            ?>
        </span>
        <span class="time">At: <?php echo Common::formatDatetime($message->created_date); ?></span>
    </div>
    <div class="content"><?php echo $message->content; ?></div>
</div>
<!--.Page-title-->