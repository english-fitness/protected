<?php $user= Yii::app()->user->getData(); ?>
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Messages</p></div>
<?php $this->renderPartial('teacher.views.messages.tools'); ?>
<?php $disabledMessageAttr = '';?>
<div class="form">
    <form method="post" action="<?php echo Yii::app()->baseurl; ?>/<?php echo $this->getModule()->id; ?>/messages/ajaxSent" class="ajaxForm" style="padding-top:0px;">
        <div class="row-form">
            <div class="label col-sm-3">Subject <span class="required">*</span></div>
            <div class="value col-sm-7" ><input type="text" value="" name="title" placeholder="Subject" <?php echo $disabledMessageAttr;?>></div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Message <span class="required">*</span></div>
            <div class="value col-sm-7" ><textarea name="content" placeholder="Message" style="height: 120px;"  <?php echo $disabledMessageAttr;?>></textarea></div>
        </div>        
        <div class="row-form">
            <div class="label col-sm-3">&nbsp;</div>
            <div class="value col-sm-7" >
                <button type="submit" name="save" class="btn btn-primary"  <?php echo $disabledMessageAttr;?>> Send</button>
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
