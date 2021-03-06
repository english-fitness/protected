<div class="page-title"><label class="tabPage">Thông tin tài khoản </label></div>
<?php $this->renderPartial('accountTab'); ?>
<div class="form">
    <?php $form=$this->beginWidget('CActiveForm',array(
        "htmlOptions"=>array(
            "enctype"=>"multipart/form-data"
        ),
        "action"=>Yii::app()->request->requestUri
    )); ?>
    <?php $clsNotification = new ClsNotification();
        $enoughProfile = $clsNotification->enoughProfile($user->id);
    ?>
    <?php if(!$enoughProfile || $user->lastname=="" || $user->firstname==""):?>
        <div class="content pL100 pB10"><i class="icon-warning-sign"></i>
	        <b class="error">Bạn vui lòng điền đầy đủ, chính xác tất cả các thông tin bắt buộc:</b>
	        <b>Họ tên, Ngày sinh, Số điện thoại, Địa chỉ.</b></div>
    <?php elseif(isset($_POST['User'])):?>
        <div class="content pL100 pB10"><b class="alert-success mL100">Bạn đã cập nhật thông tin cá nhân thành công!</b></div>
     <?php endif;?>
    <div class="notice editProfile"></div>
    <div class="row-form">
        <div class="label col-sm-3">Họ tên đệm: <span class="required">*</span></div>
        <div class="value col-lg-7">
            <?php echo $form->textField($user,'lastname',array("maxlength"=>"128","style"=>"width:150px")); ?> <b> Tên: <span class="required">*</span></b>
            <?php echo $form->textField($user,'firstname',array("maxlength"=>"128","style"=>"width:160px")); ?>
            <?php echo $form->error($user,'firstname'); ?>
        </div>
    </div>
    <div class="row-form">
        <div class="label col-sm-3">Ảnh đại diện: </div>
        <div class="value col-sm-7 loadImageJavascript">
            <img src="<?php echo Yii::app()->user->getProfilePicture(); ?>" class="w50" alt="avartar"/>
            <input type="file" name="profilePicture" style="width: auto; margin-left:25px;"/>
        </div>
    </div>
    <div class="row-form">
        <div class="label col-sm-3">Giới tính: </div>
        <div class="value col-lg-7">
            <?php $genderOptions = array(0=>'Nữ', 1=>'Nam');?>
            <?php echo CHtml::dropDownList('User[gender]', $user->gender, $genderOptions, array('style'=>'width: 80px;'));?>
        </div>
    </div>
    <div class="row-form">
        <div class="label col-sm-3">Ngày sinh: <span class="required">*</span> </div>
        <div class="value col-lg-7">
            <?php
            $birthday = isset($user->birthday)? explode("-", $user->birthday): array("", "", "");
            if(isset($_POST['birthday'])) $birthday = array($_POST['birthday']['year'], $_POST['birthday']['month'], $_POST['birthday']['date']);
            echo CHtml::dropDownList('birthday[date]', $birthday[2], Common::numberOptions(31, 1), array('style'=>'width:100px;'));
            echo CHtml::dropDownList('birthday[month]', $birthday[1], Common::numberOptions(12, 1), array('style'=>'width:100px;'));
            echo CHtml::dropDownList('birthday[year]', $birthday[0], Common::numberOptions(date('Y')-10, 1970), array('style'=>'width:100px;'));
            ?>
        </div>
    </div>
    <div class="row-form">
		<div class="label col-sm-3">Số điện thoại: <span class="required">*</span></div>
		<div class="value col-sm-7">
			<?php if(isset($_POST['User'])) $user->phone = $_POST['User']['phone'];?>
			<?php echo $form->textField($user,'phone',array('class'=>"form-control","maxlength"=>"11","style"=>"width:350px")); ?>
            <br/><span class="fs11" style="width:100%; color:#8F8F8F;">Số điện thoại hợp lệ gồm: 9 số, 10 số hoặc 11 số viết liền!</span>
		</div>
	</div>
    <div class="row-form">
        <div class="label col-sm-3">Địa chỉ: </div>
        <div class="value col-lg-7">
            <?php echo $form->textField($user,'address',array('class'=>"form-control","maxlength"=>"256")); ?>
        </div>
    </div>
    <div class="row-form">
        <div class="label col-sm-3">Mô tả ngắn về bản thân: </div>
        <div class="value col-lg-7">
            <?php echo $form->textArea($teacher,'short_description',array('class'=>"form-control","maxlength"=>"256","style"=>"height:80px;")); ?>
        </div>
    </div>
    <div class="row-form">
        <div class="label col-sm-3">Mô tả chi tiết bản thân: </div>
        <div class="value col-lg-7">
            <?php $this->widget('application.extensions.ckeditor.CKEditor', array(
				'model'=>$teacher,
				'attribute'=>'description',
				'language'=>'en',
				'editorTemplate'=>'basic'
			)); ?>
        </div>
    </div>
    <div class="row-form">
        <div class="label col-sm-3">&nbsp;</div>
        <div class="value col-lg-7">
        	<input type="submit" name="save"  class="btn btn-primary fs13 fsBold" style="width:200px; text-align:center;" value="Cập nhật thông tin cá nhân"/>
        </div>
    </div>
    <?php $this->endWidget(); ?>

</div>
<!--.account-->