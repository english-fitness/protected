<?php
    $userID = Yii::app()->user->id;
    $languages = User::model()->findByPk($userID)->language;
    Yii::app()->language=$languages;
?>

<?php $user= Yii::app()->user->getData(); ?>
<?php $this->renderPartial('student.views.messages.tab'); ?>
<?php $this->renderPartial('student.views.messages.tools'); ?>
<?php $disabledMessageAttr = '';?>
<?php if(isset($user->role) && $user->role==User::ROLE_STUDENT && $user->status < User::STATUS_ENOUGH_PROFILE):?>
<div class="content pT15 pB15 text-center"><i class="icon-warning-sign"></i>
    <b class="error"><?php echo Yii::t('lang','Vui lòng cập nhật đầy đủ thông tin cá nhân trước gửi tin nhắn');?> <a href="/student/account/index">( Cập nhật thông tin cá nhân )</a></b>
    <?php $disabledMessageAttr = 'disabled="disabled"';?>
</div>
<?php endif;?>
<div class="form">
    <form method="post" action="<?php echo Yii::app()->baseurl; ?>/<?php echo $this->getModule()->id; ?>/messages/ajaxSent" class="ajaxForm" style="padding-top:0px;">
        <div class="row-form">
            <div class="label col-sm-3"><?php echo Yii::t('lang','Tiêu đề tin nhắn');?> <span class="required">*</span></div>
            <div class="value col-sm-7" ><input type="text" value="" name="title" placeholder="<?php echo Yii::t('lang','Tiêu đề tin nhắn');?>" <?php echo $disabledMessageAttr;?>></div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3"><?php echo Yii::t('lang','Nội dung tin nhắn');?> <span class="required">*</span></div>
            <div class="value col-sm-7" ><textarea name="content" placeholder="<?php echo Yii::t('lang','Nội dung tin nhắn');?>" style="height: 120px;"  <?php echo $disabledMessageAttr;?>></textarea></div>
        </div>        
        <div class="row-form">
            <div class="label col-sm-3">&nbsp;</div>
            <div class="value col-sm-7" >
                <button type="submit" name="save" class="btn btn-primary"  <?php echo $disabledMessageAttr;?>><?php echo Yii::t('lang','Gửi tin nhắn');?> </button>
            </div>
        </div>
		<div class="row-form">
            <div class="label col-sm-3">&nbsp;</div>
            <div class="value col-sm-7" >
                <div class="successMsg"></div>
                <div class="successErrors error"></div>
            </div>
        </div>
    </form>
</div>
