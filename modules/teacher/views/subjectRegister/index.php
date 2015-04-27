<div class="page-title"><label class="tabPage"> Register subjects taught</label></div>
<?php $this->renderPartial('/account/accountTab'); ?>
<div class="form">
    <form method="post" action="<?php echo Yii::app()->baseurl; ?>/teacher/subjectRegister/AjaxSubjectRegister" class="myForm checkSubjectAjax" style="padding-top:0px;">
        <div class="noticeForm"></div>
        <div class="row-form">
            <div><label>Please select the subject can be taught:</label> </div>
            <div style="border: 1px solid #cccccc; ">
                <div class="mA10 checkSubjectAjax">
                    <?php foreach($classSubjects as $clsId=>$classSubject):?>
                        <div class="clearfix pA5" style="border-bottom:1px dashed #EDEDED;"><label><?php echo $classSubject['name']?></label>
                            <?php foreach($classSubject['subject'] as $subject):?>
                                <div class="subjectList">
                                    <span class="fL w10">&nbsp;</span>
                                        <input type="checkbox" <?php if(in_array($subject['id'], $abilitySubjects)):?> checked="checked" <?php endif;?>
                                        class="fL mL10" name="abilitySubjects[]" value="<?php echo $subject['id'];?>"/><span class="fL">&nbsp;<?php echo $subject['name'];?></span> &nbsp;
                                </div>
                             <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="row-form clearfix">
            	<div class="fL w300">
            		<input type="submit" name="save"  class="btn btn-primary fs13 pA5 fsBold text-center fL" style="width:200px" value="Update subjects taught"/>
            	</div>
            	<div class="fL w300">
            		<span class="fL mL15 mT10"><a href="/teacher/presetRequest/create"><b style="color:#325DA7;">[Sign up for courses & training schedule]</b></a></span>
            	</div>
            </div>
        </div>
    </form>
</div>

