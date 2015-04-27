<div id="forgotPassword" style="display: none">
    <form method="post" action="<?php echo Yii::app()->baseurl;?>/site/forgotPassword" class="myForm">
        <div class="row-form">
            <label class="hint">Vui lòng nhập đúng email của bạn. Hệ thống sẽ tự động gửi mã xác nhận về địa chỉ trên.</label>
            <div class="label col-sm-4">Email của bạn: </div>
            <div class="value col-sm-6"><input type="email" name="email" value="" placeholder="Vui lòng nhập Email"/></div>
            </div>
        <div class="row-form">
            <div class="col-sm-3 label">&nbsp; </div>
            <div class="col-sm-8 value">
                <button type="submit"  name="save">Lấy lại mật khẩu</button>
            </div>
        </div>
    </form>
</div>