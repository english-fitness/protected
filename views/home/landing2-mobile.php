<?php
$referrer = "";
if (isset($_REQUEST['ref'])){
    $knownReferrer = array(
        'facebook'=>'Online - Facebook',
        'hocmai'=>'Offline - Hocmai',
    );
    if (isset($knownReferrer[$_REQUEST['ref']])){
        $referrer = $knownReferrer[$_REQUEST['ref']];
    }
}

if ($referrer == ""){
    $referrer = "Online - Facebook";
}

$baseAssetsUrl = $this->baseAssetsUrl;
?>

<div class="main-page">
	<div id="header-bg" class="bg"></div>
	<div id="header">
		<div class="layer-2">
			<div class="su-logo">
				<img src="<?php echo $baseAssetsUrl;?>/home/img/logo.png" style="width:400px">
			</div>
			<div class="header-nav-bar">
				<div class="header-nav">
					<a href="/news" target="blank" class="quick-nav-link accent">Về Speakup</a>
				</div>
				<div class="header-nav">
	                <a href="#" class="orange-btn sm teleporter" data-waypoint="reg-wp" style="height:20px">HỌC THỬ MIỄN PHÍ</a>
                </div>
                <div class="header-nav" style="margin-top:3px">
                	<div class="fb-like" data-href="https://www.facebook.com/vnspeakup" data-width="450" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
                </div>
			</div>
		</div>
	</div>
	<div id="introduction">
		<div class="layer-2">
			<div>
				<h2 class="banner-subtitle">
	                <span>Tặng 2011 suất học miễn phí</span>
	            </h2>
	            <h2 class="banner-title">
	                <span class="orange-darker"><b>HỌC TIẾNG ANH TRỰC TUYẾN</b></span>
	                <br>
	                <span style="font-weight:400">VỚI GIÁO VIÊN NƯỚC NGOÀI</span>
	            </h2>
	            <div style="margin:50px auto;">
	                <span class="title-offer-tag">ĐẾN HẾT <?php echo $params['promotionEnd']?></span>
	            </div>
	            <div class="text-center" style="padding-top:100px">
	                <a href="#" class="orange-btn large teleporter" data-waypoint="reg-wp">THAM GIA NGAY</a>
	            </div>
			</div>
		</div>
	</div>
	<div id="benefit">
		<div class="layer-2">
			<div id="benefit-title">
                <div class="larger-title accent">
                    <p><span class="orange-darker">"Speak up"</span><span> là:</span></p>
                </div>
                <div class="smaller-title accent-bg">
                    <p>hình thức học tiếng Anh giao tiếp trực tuyến 1 thầy 1 trò của Hocmai.vn</p>
                </div>
            </div>
            <div id="benefit-container">
            	<div class="benefit-col">
            		<img src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/benefit-1.png">
            		<div class="benefit-txt">
            			<p>MỘT THẦY MỘT TRÒ</p>
            		</div>
            	</div>
            	<div class="benefit-col">
            		<img src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/benefit-2.png">
            		<div class="benefit-txt">
            			<p>GIẢNG VIÊN CHẤT LƯỢNG</p>
            		</div>
            	</div>
            	<div class="benefit-col">
            		<img src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/benefit-3.png">
            		<div class="benefit-txt">
            			<p>CÁ NHÂN HÓA GIÁO TRÌNH</p>
            		</div>
            	</div>
            	<div class="benefit-col">
            		<img src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/benefit-4.png">
            		<div class="benefit-txt">
            			<p>MỌI LÚC MỌI NƠI</p>
            		</div>
            	</div>
            </div>
            <div style="clear:both"></div>
            <div class="text-center" style="padding-top:50px">
                <a href="#" class="orange-btn large teleporter" data-waypoint="reg-wp">ĐĂNG KÝ NGAY</a>
            </div>
		</div>
	</div>
	<div id="registration-step" class="layer-2">
		<div id="registration-intro">
			<span>CHỈ CẦN 3 BƯỚC ĐỂ SPEAK UP NGAY</span>
		</div>
		<div id="registration-container" class="accent-bg">
			<div class="registration-step">
				<img src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/step1-lg.png">
				<p class="step-txt-title">Bước 1: <b>ĐĂNG KÝ</b></p>
				<div class="step-txt-content">
                    <p>* Đăng ký</p>
                    <p>* Cập nhật đầy đủ thông tin để được hỗ trợ</p>
				</div>
			</div>
			<div class="registration-step">
				<img src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/step2-lg.png">
				<p class="step-txt-title">Bước 2: <b>TEST KỸ THUẬT</b></p>
				<div class="step-txt-content">
					<p>* Đăng nhập hệ thống, kiểm tra chất lượng loa, mic</p>
                    <p>* Kiểm tra khả năng tiếng Anh qua cuộc hội thoại đơn giản</p>
				</div>
			</div>
			<div class="registration-step">
				<img src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/step3-lg.png">
				<p class="step-txt-title">Bước 3: <b>THAM GIA HỌC</b></p>
				<div class="step-txt-content">
					<p>* Học thử với giáo viên nước ngoài</p>
                    <p>* Nhận đánh giá trình độ</p>
				</div>
			</div>
			<div class="step-note">
                <p>* Sau buổi học thử học viên sẽ nhận được đánh giá trình độ từ giáo viên nước ngoài trước khi quyết định học chính thức</p>
                <p>* Nộp học phí đặt chỗ trước khi học thử với giáo viên nước ngoài nhận ngay ưu đãi học phí khi đăng kí học chính thức; 
                sẽ được hoàn trả nếu không tham gia học chính thức. Chi tiết tham khảo tại <u><a style="color:white" target="blank" href="/news/hoc-phi">Học phí và hình thức thanh toán</a></u>.</p>
            </div>
            <div class="button-section">
            	<a class="orange-btn med teleporter" data-waypoint="reg-wp">Chọn giáo viên cho mình ngay</a>
            </div>
		</div>
	</div>
	<div id="teacher">
		<div id="teacher-bg" class="bg"></div>
		<div id="teacher-container" class="layer-2">
			<div id="teacher-title">
				<p>
                    <span style="color:#245ba7">MỘT GIÁO VIÊN NƯỚC NGOÀI</span><br>
                    <span style="color:#fa5a00">SẼ DẠY MỘT MÌNH BẠN</span>
                </p>
			</div>
			<div id="teacher-slider">
				<div class="slide left"></div>
				<div id="portrait-holder">
				</div>
				<div class="slide right"></div>
			</div>
			<div id="teacher-container">
			</div>
		</div>
	</div>
	<div id="tuition">
		<div id="tuition-bg" class="bg"></div>
		<div id="tuition-content" class="layer-2">
			<div id="tuition-title" class="accent">
				<p>Học phí</p>
			</div>
			<div class="tuition-container">
				<table class="tuition-tbl">
                    <tr>
                        <th>Gói 1</th>
                    </tr>
                    <tr>
                        <td>10 buổi</td>
                    </tr>
                    <tr>
                        <td>950.000 đ/khóa</td>
                    </tr>
                </table>
                <table class="tuition-tbl">
                    <tr>
                        <th>Gói 2</th>
                    </tr>
                    <tr>
                        <td>15 buổi</td>
                    </tr>
                    <tr>
                        <td>1.350.000 đ/khóa</td>
                    </tr>
                </table>
                <table class="tuition-tbl">
                    <tr>
                        <th colspan="2">Gói 3</th>
                    </tr>
                    <tr>
                        <td>20 buổi</td>
                        <td>25 buổi</td>
                    </tr>
                    <tr>
                        <td>1.700.000 đ</td>
                        <td>2.125.000 đ</td>
                    </tr>
                </table>
			</div>
			<div class="button-section">
            	<a class="orange-btn large teleporter" data-waypoint="reg-wp">ĐĂNG KÝ NGAY</a>
            </div>
		</div>
	</div>
	<div id="registration" class="accent-bg layer-2">
		<div id="reg-wp" class="waypoint"></div>
		<form id="registration-form" role="form">
			<?php if ($referrer != ""):?>
	            <input type="hidden" name="referrer" value="<?php echo $referrer?>">
	        <?php endif;?>
			<div class="form-group">
				<label class="control-label" for="fullname">
					Họ tên *:
					<span class="fullname invalid-notice">
	                    <small><i></i></small>
	                </span>
				</label>
		    	<input id="fullname" class="form-attr form-control" data-attr="fullname" type="text" name="PreregisterUser[fullname]" placeholder="Họ tên"/>
			</div>
			<div class="form-group">
				<label for="phone">
					Điện thoại *:
					<span class="phone invalid-notice">
	                    <small><i></i></small>
	                </span>
				</label>
		    	<input id="phone" class="form-attr form-control" data-attr="phone" type="text" name="PreregisterUser[phone]" placeholder="Số điện thoại"/>
			</div>
			<div class="form-group">
				<label for="email">
					Email:
					<span class="email invalid-notice">
	                    <small><i></i></small>
	                </span>
				</label>
		    	<input id="email" class="form-attr form-control" data-attr="email" type="text" name="PreregisterUser[email]" placeholder="Email"/>
			</div>
			<div class="text-center" style="margin-top:50px;">
		    	<a class="submit-btn orange-btn large" data-form="registration-form" data-validator="validate">ĐĂNG KÝ</a>
			</div>
		</form>
	</div>
	<div id="testimonials">
		<div id="testimonials-bg" class="bg"></div>
		<div id="testimonials-content" class="layer-2">
			<div class="testimonials-title">
				<p>
					<img class="wing" src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/wing-left.png">
					<span>HỌC VIÊN NÓI VỀ</span>
					<img class="wing" src="<?php echo $baseAssetsUrl;?>/home/img/landing-2/wing-right.png">
					<br><span style=" color:#fa5a00">SPEAK UP</span>
					<br><p>★ ★ ★</p>
				</p>
            </div>
            <div id="testimonial-container">
            </div>
        </div>
		</div>
	</div>
</div>