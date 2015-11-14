<?php
if (isset($_REQUEST['r']) && !empty($_REQUEST['r'])){
    $referrer = $_REQUEST['r'];
} else {
    $referrer = 'fb';
}

$baseAssetsUrl = $this->baseAssetsUrl;
?>

<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,900,300' rel='stylesheet' type='text/css'>
<div id="floating-btn">
	<div class="orange-btn teleporter" data-waypoint="registration-form">
		<p>ĐĂNG KÝ NGAY</p>
	</div>
</div>
<div id="registration-form" class="waypoint" style="top:3100px"></div>
<div id="main">
	<!--top bar-->
	<div id="top-bar">
		<div id="su-logo">
			<a href="/news" target="_blank"><img src="<?php echo $baseAssetsUrl;?>/images/logo/logo-white-bordered-200.png"></a>
		</div>
		<div id="quick-nav-links">
			<a href="/news" target="_blank" class="quick-nav-link" style="border-right:2px solid white">Về Speakup</a>
			<a href="/news/dang-ky" target="_blank" class="quick-nav-link">Đăng ký</a>
		</div>
	</div>
	<!--banner-->
	<div id="banner">
		<div id="banner-content">
			<p class="banner-header">GIAO TIẾP NÂNG CAO TRÌNH ĐỘ</p>
			<p class="banner-header larger">TIẾNG ANH CÙNG <span style="color:#FF6000">SPEAK UP</span></p>
			<div id="banner-txt">
				<div class="banner-txt-bullet">
					<p>Học trực tuyến, 1 giáo viên bản xứ - 1 học viên</p>
				</div>
				<div class="banner-txt-bullet">
					<p>Giáo trình, lộ trình đào tạo riêng biệt và duy nhất cho trình độ của từng học viên</p>
				</div>
				<div class="banner-txt-bullet">
					<p>Buổi học theo chuẩn quốc tế, mỗi buổi 30 phút, luôn tạo cảm hứng cho học viên</p>
				</div>
				<div class="banner-txt-bullet">
					<p>Nền tảng được xây dựng riêng hỗ trợ tối đa tương tác, trao đổi kiến thức giữa giáo viên - học viên</p>
				</div>
				<div class="text-center" style="margin-top:50px;">
					<a href="#" class="orange-btn large teleporter" data-waypoint="steps">ĐĂNG KÝ NGAY</a>
				</div>
			</div>
		</div>
	</div>
	<!--steps-->
	<div id="steps">
		<div id="steps-header">
			<p>ĐĂNG KÝ VÀ CHIA SẺ</p>
		</div>
		<div id="steps-content">
			<div class="step">
				<p class="step-txt">Đăng ký để trở thành học viên</p>
			</div>
			<div class="step-arrow"></div>
			<div class="step">
				<p class="step-txt">Nhận ưu đãi 5 buổi học chính thức</p>
			</div>
			<div class="step-arrow"></div>
			<div class="step">
				<p class="step-txt">Share với bạn bè mã học viên</p>
			</div>
			<div class="step-arrow"></div>
			<div class="step">
				<p class="step-txt">Giảm ngay đến 20% học phí</p>
			</div>
		</div>
		<div id="steps-catchy-line" class="accent">
			<p>SHARE NHIỀU, HỌC NHIỀU, NGẠI GÌ ĐÂU</p>
		</div>
		<div class="text-center">
			<a href="#" class="orange-btn large teleporter" data-waypoint="teachers">Speak up là gì</a>
		</div>
	</div>
	<!--teachers-->
	<div id="teachers">
		<div id="teachers-header" class="accent text-center">
			<p>
				PHƯƠNG THỨC MỚI LÀM CHỦ TIẾNG ANH<br>
				MỘT GIÁO VIÊN NƯỚC NGOÀI - MỘT HỌC VIÊN DỰA TRÊN NỀN TẢNG ĐẶC BIỆT<br>
				HỖ TRỢ TỐI ĐA VIỆC GIẢNG DẠY VÀ TƯƠNG TÁC TRONG BUỔI HỌC
			</p>
		</div>
		<div id="teachers-container">
			<div class="teacher-list"></div>
			<div id="tv">
				<div id="tv-screen">
					
				</div>
				<div id="tv-btn">
					<a href="#" class="btn prev"></a>
					<a href="#" class="btn next"></a>
				</div>
			</div>
			<div id="teachers-teleporter" class="orange-btn large text-center">
				<a href="#" class="teleporter" data-waypoint="benefits">HỌC THỬ MIỄN PHÍ</a>
			</div>
			<div style="clear:both"></div>
		</div>
	</div>
	<!--benefits-->
	<div id="benefits">
		<div id="benefits-header" class="accent text-center">
			<p>SPEAK UP LÀ CHƯƠNG TRÌNH DẠY TIẾNG ANH GIAO TIẾP ONLINE THỜI GIAN THỰC 1-1 DO HỌC MÃI PHÁT TRIỂN</p>
		</div>
		<div id="benefits-content" class="orange">
			<div class="benefit-block left first"></div>
			<div class="benefit-block right">
				<p><span class="bolder">Học phí thấp và tiết kiệm hơn</span> so với cách học truyền thống</p>
			</div>
			<div class="benefit-block left">
				<p>Thời gian học linh động <span class="bolder">với 16 khung giờ học/ngày</span> giúp bạn thỏa mãi lựa chọn</p>
			</div>
			<div class="benefit-block right">
				<p>Hình thức <span class="bolder">học trực tuyến</span> giúp bạn học ở <span class="bolder">bất cứ nơi đâu</span></p>
			</div>
			<div class="benefit-block left">
				<p>Nội dung bài học được xây dựng riêng phù hợp với <span class="bolder">trình độ và năng lực</span> của từng học viên</p>
			</div>
			<div class="benefit-block right last">
				<p><span class="bolder">Tương tác trên nền tảng chuyên biệt</span> được phát triển riêng để tối ưu hóa tương tác giữa học viên và giáo viên</p>
			</div>
		</div>
	</div>
	<!--registration-->
	<div id="registration">
        <div class="form-container" id="main-form">
            <div class="close-button">
                <a href='#'><img src="<?php echo $baseAssetsUrl;?>/images/icon/close-button.png"/></a>
            </div>
            <div class="inner-form">
                <!--main form-->
                <form id="main-registration-form" class="registration-form">
                    <?php if ($referrer):?>
                        <input type="hidden" name="referrer" value="<?php echo $referrer?>">
                    <?php endif;?>
                    <div class="main-form">
                        <div class="title">ĐĂNG KÝ TRẢI NGHIỆM MIỄN PHÍ</div>
                        <div class="form-input">
                            <label>
                                Họ tên <span class="orange-darker">*</span>
                                <span class="fullname invalid-notice">
                                    <small><i></i></small>
                                </span>
                            </label>
                            <input class="form-attr" data-attr="fullname" type="text" name="PreregisterUser[fullname]" placeholder="Họ tên"/>
                        </div>
                        <div class="form-input">
                            <label>
                                Số điện thoại <span class="orange-darker">*</span>
                                <span class="phone invalid-notice">
                                    <small><i></i></small>
                                </span>
                            </label>
                            <input class="form-attr" data-attr="phone" type="text" name="PreregisterUser[phone]" placeholder="Số điện thoại"/>
                        </div>
                        <div class="form-input">
                            <label>
                                Email
                                <span class="email invalid-notice">
                                    <small><i></i></small>
                                </span>
                            </label>
                            <input class="form-attr" data-attr="email" type="text" name="PreregisterUser[email]" placeholder="Email"/>
                        </div>
                        <div class="form-input text-center" style="padding-top:30px">
                            <a href="#" class="orange-btn large submit-btn" data-form="main-registration-form" data-validator="validate">ĐĂNG KÝ</a>
                        </div>
                        <div class="form-input">
                            <p id="privacy-info">
                                <small>
                                    <i>
                                        Thông tin của bạn được bảo mật và chỉ được sử dụng trong tư vấn liên quan đến chương trình tiếng anh
                                        trực tuyến tại speakup.vn
                                    </i>
                                </small>
                            </p>
                        </div>
                    </div>
                    <!--end main form-->
                    <!--optional form-->
                    <div class="optional-form">
                        <div class="title">Thông tin thêm (không bắt buộc)</div>
                        <div class="form-input">
                            <label>Thời gian học</label>
                            <div>
                                <span class="wday"></span>
                                <input type="hidden" name="PreregisterUser[wday]" />
                            </div>
                            <div class="timepicker-input">
                                <div style="display:table-cell;position:relative">
                                    <label>Từ</label>
                                    <input type="text" class="time_from form-attr" name="PreregisterUser[timerange_from]" />
                                </div>
                                <div style="display:table-cell;position:relative">
                                    <label>Đến</label>
                                    <input type="text" class="time_to form-attr" name="PreregisterUser[timerange_to]" />
                                </div>
                            </div>
                            <p style="font-size:15px"><small><i>Biết được thời gian học của bạn sẽ giúp chúng tôi sắp xếp lịch học cho bạn nhanh hơn</i></small></p>
                        </div>
                        <div class="form-input">
                            <label>Mã khuyến mại</label>
                            <input type="text" class="form-attr" name="PreregisterUser[promotion_code]" placeholder="Mã khuyến mại"/>
                            <p style="font-size:15px"><small><i>Nếu bạn có mã khuyến mại, bạn có thể nhập vào đây để nhận ưu đãi từ speakup.vn</i></small></p>
                        </div>
                    </div>
                    <!--end optional form-->
                </form>
            </div>
        </div>
        <div class="text-center" style="margin-top:70px">
            <a href="#" class="orange-btn large teleporter" data-waypoint="testimonials"><strong>HỌC VIÊN NÓI VỀ SPEAK UP</strong></a>
        </div>
    </div>
	<!--end registration-->
	<!--testimonials-->
    <div id="testimonials">
        <div id="testimonial-container">
        </div>
    </div>
    <!--end testimonials-->
</div>