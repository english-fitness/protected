<script type="text/javascript">
	//Auto complete suggest title for course
	function markRead(noticeId){
		var data = {'noticeId': noticeId};
		var countNotConfirmed = parseInt($("#countNotConfirmed").html());
		$.ajax({
			url: "<?php echo Yii::app()->baseUrl; ?>" + "/teacher/notification/markRead",
			type: "POST", dataType: 'JSON',data:data,
			success: function(data) {
				if(data.success){
					$("#unreadNotice"+noticeId).hide();
					$("#markRead"+noticeId).hide();
					recountNotConfirmed = countNotConfirmed-1;
					if(recountNotConfirmed<0) recountNotConfirmed = 0;
					$("#countNotConfirmed").html(recountNotConfirmed);
				}
			}
		});
	}
</script>
<div class="page-title"><label class="tabPage"><a style="color:#325DA7;" href="/teacher/notification">Thông báo từ Dạykèm123</a></label></div>
<div class="mA10 clearfix">
	<?php
		$user = Yii::app()->user;
		$countNotConfirmed = Notification::model()->getNotifications($user, null, false, true);
		if($countNotConfirmed>0):
	?>
	<span class="error">Bạn có <b id="countNotConfirmed"><?php echo $countNotConfirmed;?></b> thông báo từ hệ thống Dạykèm123 được đánh dấu là chưa đọc! Vui lòng xem thông báo và xác nhận đã đọc!</span>
	<?php endif;?>
</div>
<div class="list-notice">
    <?php if($notifications): foreach($notifications as $item): ?>
    <div class="row-notice">
        <p><?php echo $item->content; ?></p>
        <?php if(!$item->isConfirmed($user->id)):?>
        	<p><span id="unreadNotice<?php echo $item->id;?>" >(<a id="markRead<?php echo $item->id;?>" href="javascript:markRead(<?php echo $item->id;?>)">Xác nhận đã đọc</a>)</span></p>
        <?php endif;?>
        <p>
        	<label class="hint"><?php echo date("d/m/Y H:i",strtotime($item->created_date))?></label>
        </p>
    </div>
    <?php endforeach; else: ?>
    <div class="row-notice">Hiện tại không có thông báo mới.</div>
    <?php endif; ?>
</div>
