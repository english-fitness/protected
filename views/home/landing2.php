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
?>

<link rel="stylesheet" href="/media/home/style/landing-2.css" />
<link href='https://fonts.googleapis.com/css?family=Roboto:400,700,900,300' rel='stylesheet' type='text/css'>
<div id="main">
    <!--waypoints-->
    <div id="wp-1" class="waypoint" style="top:600px;"></div>
    <div id="wp-2" class="waypoint" style="top:1250px;"></div>
    <div id="wp-3" class="waypoint" style="top:1940px;"></div>
    <div id="wp-4" class="waypoint" style="top:2630px;"></div>
    <!--end waypoints-->
    <!--banner-->
    <div id="main-banner">
        <div id="body-header" class="clearfix">
            <div class="speakup-logo">
                <a style="display:block;height:54px" href="https://speakup.vn/news" target="_blank" title="Trang chủ">
                    <img style="height:54px" src="/media/home/img/logo.png" />
                </a>
            </div>
            <div id="quick-nav">
                <a href="/news" target="blank" class="quick-nav-link accent">Về Speakup</a>
                <a href="#" class="orange-btn sm form-trigger" style="height:20px">HỌC THỬ MIỄN PHÍ</a>
            </div>
        </div>
        <div id="body-banner">
            <div style="float:right; margin-top:65px; margin-right:50px;">
                <div class="fb-like" data-href="https://www.facebook.com/vnspeakup" data-width="450" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
            </div>
            <div style="clear:both"></div>
            <h2 class="banner-subtitle">
                <span>Chương trình miễn phí</span>
            </h2>
            <h2 class="banner-title">
                <span style="color:#FF4200"><b>HỌC TIẾNG ANH TRỰC TUYẾN</b></span>
                <br>
                <span style="color:#044C95; font-weight:300">VỚI GIÁO VIÊN NƯỚC NGOÀI</span>
            </h2>
            <div style="width:770px; margin:5px auto; text-align:right;">
                <span class="title-offer-tag">ĐẾN HẾT 30/10/2015</span>
            </div>
            <div class="text-center" style="padding-top:100px">
                <a href="#" class="orange-btn large teleporter" data-waypoint="wp-1">THAM GIA NGAY</a>
            </div>
        </div>
    </div>
    <!--end banner-->
    <!--content header-->
    <!--end content header-->
    <!--content-->
    <div id="content">
        <!--benefits-->
        <div id="benefit" class="body-content">
            <div id="benefit-bg" class="bg"></div>
            <div class="layer-2">
                <div id="benefit-title">
                    <div class="larger-title accent">
                        <p><span class="orange-darker">"Speak up"</span><span> là:</span></p>
                    </div>
                    <div class="smaller-title accent-bg">
                        <p>hình thức học tiếng Anh giao tiếp trực tuyến 1 thầy 1 trò của Hocmai.vn</p>
                    </div>
                    <div class="border-bottom">
                        <div class="benefit-piece benefit-img">
                            <img src="/media/home/img/landing-2/benefit-1.png">
                        </div>
                        <div class="benefit-piece benefit-img middle-piece">
                            <img src="/media/home/img/landing-2/benefit-2.png">
                        </div>
                        <div class="benefit-piece benefit-img middle-piece">
                            <img src="/media/home/img/landing-2/benefit-3.png">
                        </div>
                        <div class="benefit-piece benefit-img" style="margin-left:21px">
                            <img src="/media/home/img/landing-2/benefit-4.png">
                        </div>
                    </div>
                    <div class="benefit-txt-div accent">
                        <div class="benefit-txt">MỘT THẦY MỘT TRÒ</div>
                        <div class="benefit-txt middle-piece">GIẢNG VIÊN CHẤT LƯỢNG</div>
                        <div class="benefit-txt middle-piece">CÁ NHÂN HÓA GIÁO TRÌNH</div>
                        <div class="benefit-txt" style="margin-left:21px">MỌI LÚC, MỌI NƠI</div>
                    </div>
                </div>
                <div>
                </div>
                <div class="text-center" style="padding-top:50px">
                    <a href="#" class="orange-btn large teleporter" data-waypoint="wp-2">ĐĂNG KÝ NGAY</a>
                </div>
            </div>
        </div>
        <!--end benefits-->
        <!--introduction-->
        <div id="introduction" class="body-content">
            <div id="register-step-bg" class="bg"></div>
            <div class="layer-2">
                <div id="register-step" class="outer-content">
                    <div class="title text-center accent"><span>CÁC BƯỚC ĐĂNG KÝ</span></div>
                    <div class="inner-content accent-bg">
                        <div class="bottom-border" style="height:105px">
                            <div class="step-img step-piece">
                                <img src="/media/home/img/landing-2/step-1.png">
                            </div>
                            <div class="step-img step-piece middle-piece">
                                <img src="/media/home/img/landing-2/step-2.png">
                            </div>
                            <div class="step-img step-piece">
                                <img src="/media/home/img/landing-2/step-3.png">
                            </div>
                        </div>
                        <div style="clear:both"></div>
                        <div class="step-txt">
                            <div class=" step-piece">
                                <div class="step-txt-title">
                                    <span>Bước 1: <b>ĐĂNG KÝ</b></span>
                                </div>
                                <div class="step-txt-content">
                                    <p>* Đăng ký</p>
                                    <p>* Cập nhật đầy đủ thông tin để được hỗ trợ</p>
                                </div>
                            </div>
                            <div class=" step-piece middle-piece">
                                <div class="step-txt-title">
                                    <span>Bước 2: <b>TEST KỸ THUẬT</b></span>
                                </div>
                                <div class="step-txt-content">
                                    <p style="margin-bottom:0">* Đăng nhập hệ thống, kiểm tra chất lượng loa, mic</p>
                                    <p>* Kiểm tra khả năng tiếng Anh qua cuộc hội thoại đơn giản</p>
                                </div>
                            </div>
                            <div class=" step-piece">
                                <div class="step-txt-title">
                                    <span>Bước 3: <b>THAM GIA HỌC</b></span>
                                </div>
                                <div class="step-txt-content">
                                    <p>* Học thử với giáo viên nước ngoài</p>
                                    <p>* Nhận đánh giá trình độ</p>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                        <div class="step-note">
                            <p>* Sau buổi học thử học viên sẽ nhận được đánh giá trình độ từ giáo viên nước ngoài trước khi quyết định học chính thức</p>
                            <p>* Nộp học phí đặt chỗ trước khi học thử với giáo viên nước ngoài nhận ngay ưu đãi học phí khi đăng kí học chính thức; 
                            sẽ được hoàn trả nếu không tham gia học chính thức. Chi tiết tham khảo tại <u><a style="color:white" target="blank" href="/news/hoc-phi">Học phí và hình thức thanh toán</a></u>.</p>
                        </div>
                        <div class="text-center">
                    <a href="#" class="orange-btn large teleporter" data-waypoint="wp-3">CHỌN GIÁO VIÊN CHO MÌNH NGAY</a>
                </div>
            </div>
                </div>
            </div>
        </div>
        <!--end introduction-->
        <!--teachers-->
        <div id="teacher-detail" class="body-content">
            <div id="teacher-bg" class="bg"></div>
            <div class="layer-2">
                <h2 id="teacher-title">
                    <span style="color:#245ba7">MỘT GIÁO VIÊN NƯỚC NGOÀI</span>
                    <span style="color:#fa5a00">SẼ DẠY MỘT MÌNH BẠN</span>
                </h2>
                <div id="teacher" class="clearfix">
                    <div class="teacher-left">
                        <ul class="teacher-list">
                        </ul>
                    </div>
                    <div class="teacher-right clearfix">
                        <div class="teachers-container">
                        </div>
                        <div id="tuition" class="accent text-center">
                            <p class="accent tuition-title"><strong>Học phí</strong></p>
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
                            <div class="text-center" style="margin-top:20px; font-size:20px;">
                                <a href="#" class="orange-btn sm teleporter" data-waypoint="wp-4"><strong>HỌC THỬ MIỄN PHÍ</strong></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end teachers-->
        <!--registration form-->
        <div id="registration" class="body-content">
            <div id="registration-form-bg" class="bg"></div>
            <div class="layer-2">
                <div class="form-container" id="main-form">
                    <div class="close-button">
                        <a href='#'><img src="/media/images/icon/close-button.png"/></a>
                    </div>
                    <div class="inner-form">
                        <!--main form-->
                        <form id="main-registration-form" class="registration-form">
                            <?php if ($referrer != ""):?>
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
                <div class="text-center" style="margin-top:70px; margin-left:-230px">
                    <a href="#" class="orange-btn large teleporter" data-waypoint="testimonials"><strong>HỌC VIÊN NÓI GÌ VỀ SPEAK UP</strong></a>
                </div>
            </div>
        </div>
        <!--end registration form-->
        <!--testimonials-->
        <div id="testimonials" class="body-content">
            <div class="content-title testimonial-title">
                <p><img src="/media/home/img/landing-2/wing-left.png"> HỌC VIÊN NÓI VỀ <span style=" color:#fa5a00">SPEAK UP</span> <img src="/media/home/img/landing-2/wing-right.png"></p>
                <br><br>
                <p>★ ★ ★</p>
            </div>
            <div id="testimonial-container">
            </div>
        </div>
        <!--end testimonials-->

    </div>
    <!--end content-->
</div>
<div id="popup-registration-form">
    <form id="small-registration-form" class="registration-form">
        <?php if ($referrer != ""):?>
            <input type="hidden" name="referrer" value="<?php echo $referrer?>">
        <?php endif;?>
        <div id="inner-clone-form" class="main-form">
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
    </form>
</div>
