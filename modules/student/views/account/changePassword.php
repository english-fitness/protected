<?php $this->renderPartial('/messages/tab'); ?>
<div class="nav nav-tabs"><p style="text-align:center; font-size:20px; padding-top:5px;">Đổi mật khẩu</p></div>
<div class="form">
    <div class="account">
        <?php $form=$this->beginWidget('CActiveForm',array(
            "htmlOptions"=>array(
                "class"=>"myForm"
            ),
            "action"=>"/student/account/AjaxChangePassword"
        )); ?>
        <div class="notice editPassword"></div>
        <div class="row-form">
            <div class="label col-sm-3">Địa chỉ Email: </div>
            <div class="value col-sm-7">
                <input type="text" disabled value="<?php echo $model->email;?>" style="width:250px;"><br/>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Mật khẩu cũ: </div>
            <div class="value col-sm-7">
                <input type="password"  name="password" value="" placeholder="Nhập mật khẩu cũ" style="width: 300px"><br/>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Nhập mật khẩu mới: </div>
            <div class="value col-sm-7">
                <input type="password" name="passwordSave" value="" placeholder="Nhập mật khẩu mới" style="width: 300px"><br/>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Nhập lại mật khẩu mới: </div>
            <div class="value col-sm-7">
                <input type="password" name="repeatPassword" value="" placeholder="Nhập mật khẩu mới" style="width: 300px"><br/>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">&nbsp;</div>
            <div class="value col-sm-7">
                <input type="submit" name="save" class="btn btn-primary" value="Đổi mật khẩu"/>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <!--.account-->
</div>
