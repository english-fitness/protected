<div class="rendered-form">
    <label>Tiêu đề</label>
    <input type="text" id="mail-subject" name="Mail[subject]" class="mB10" value="Thông báo lịch học chính thức cùng Speakup.vn">
    <label>Tên học sinh</label><span id="name-notice" class="errorMessage"></span>
    <input type="text" name="Mail[name]" class="mail-input mB10" data-attribute="name" value="<?php echo $student->fullname()?>">
    <label>Tài khoản </label>
    <input type="text" class="mB10" data-attribute="username" disabled value="<?php echo $student->username?>">
    <label>Mật khẩu &nbsp;</label><span id="password-notice" class="errorMessage"></span>
    <input type="text" name="Mail[password]" class="mail-input mB10 attention-required" data-attribute="password" value="speakup.vn" readonly>
    <label>Ngày bắt đầu&nbsp;</label><span id="date-notice" class="errorMessage"></span>
    <input type="text" name="Mail[date]" class="mail-input-auto mB10 datepicker" data-attribute="date" readonly="readonly">
    <label>Các ngày trong tuần&nbsp;</label><span id="wday-notice" class="errorMessage"></span>
    <input type="text" name="Mail[wday]" class="mail-input mB10" data-attribute="wday">
    <label>Giờ học&nbsp;</label><span id="time-notice" class="errorMessage"></span>
    <input type="text" name="Mail[time]" class="mail-input mB10" data-attribute="time">
    <p><b>Lưu ý: </b>Kiểm tra kĩ thông tin trước khi gửi</p>
</div>
<script type="text/javascript">
    $('#mail-form').submit(function(e){
        if(requesting){
            return false;
        }
        $('#name-notice').html("");
        $('#password-notice').html("");
        $('#date-notice').html("");
        $('#wday-notice').html("");
        $('#time-notice').html("");
        var data = getFormData($(this));
        if (!data['Mail[template]'] || !data['Mail[email]'] || !data['Mail[password]'] || !data['Mail[name]'] || !data['Mail[wday]'] || !data['Mail[date]'] || !data['Mail[time]']){
            if (!data['Mail[name]']){
                $('#name-notice').html("Tên học sinh không được trống");
            }
            if (!data['Mail[password]']){
                $('#password-notice').html("Mật khẩu không được trống");
            }
            if (!data['Mail[date]']){
                $('#date-notice').html("Ngày học không được trống");
            }
            if (!data['Mail[wday]']){
                $('#wday-notice').html("Các buổi trong tuần không được trống");
            }
            if (!data['Mail[time]']){
                $('#time-notice').html("Giờ học không được trống");
            }
        } else {
            sendMail(data);
        }

        return false;
    });
</script>