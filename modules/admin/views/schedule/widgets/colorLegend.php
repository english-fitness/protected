<style>
.day-nav{
    float:left;
    width:10px;
    padding: 6px 14px 6px 6px;
    outline:none !important;
}
#secondary-datepicker{
    float:left;
    width:105px;
    margin:0 5px;
    height:34px;
    min-height:34px !important;
}
.left-floating-widget{
    position:fixed;
    left:10px;
    width:160px;
}
.widget-box{
    padding:3px;
    border:solid 1px #ddd;
    box-sizing:border-box;
    background-color:white;
    box-shadow:1px 1px 1px #ddd;
    border-radius:3px;
}
</style>
<div class="fs14">
    <div class="left-floating-widget widget-box" style="bottom:10px;">
    	<span style="margin:3px"><b>Chú thích</b></span>
    	<div style="clear:both"></div>
    	<div style="width:25px;height:15px;background-color:yellow;float:left;margin:3px"></div><span style="float:left">Khung giờ trống</span>
    	<div style="clear:both"></div>
    	<div style="width:25px;height:15px;background-color:darkgray;float:left;margin:3px"></div><span style="float:left">Đã hết hạn</span>
    	<div style="clear:both"></div>
    	<div style="width:25px;height:15px;background-color:lime;float:left;margin:3px"></div><span style="float:left">Đã xác nhận</span>
    	<div style="clear:both"></div>
    	<div style="width:25px;height:15px;background-color:darkgreen;float:left;margin:3px"></div><span style="float:left">Đang chờ</span>
    	<div style="clear:both"></div>
    	<div style="width:25px;height:15px;background-color:turquoise;float:left;margin:3px"></div><span style="float:left">Đang diễn ra</span>
    	<div style="clear:both"></div>
    	<div style="width:25px;height:15px;background-color:darkorange;float:left;margin:3px"></div><span style="float:left">Đã kết thúc</span>
    	<div style="clear:both"></div>
    </div>
    <div id="floating-datepicker" class="left-floating-widget" style="bottom:165px;display:none">
        <button class='today-select btn' style="margin-right:5px; outline:none;width:160px;padding:3px">Hôm nay (<?php echo date('d-m-Y')?>)</button>
        <div style="margin-top:5px">
            <button class='day-nav btn btn-primary' nav='prev' title='Ngày trước'><</button>
            <input type="text" id="secondary-datepicker" readonly value="18-09-2015">
            <button class='day-nav btn btn-primary' nav='next' title='Ngày tiếp theo'>></button>
        </div>
    </div>
    <div class="left-floating-widget widget-box" style="bottom:245px; padding:10px; text-align:justify; display:none"
    	 id="changingSchedule">
    	<p><b>Thay đổi lịch học</b></p>
    	<p>Chọn một khung giờ trống để đổi lịch học. Click nút "Cancel" để hủy thay đổi</p>
    	<button id="cancelChangeSchedule" class="btn" style="margin-left: 35px">Cancel</button>
    </div>
</div>
<script>
$(function(){
	$('#cancelChangeSchedule').click(function(){
		toggleChangeSchedule(false);
	});
});
$('#secondary-datepicker').datepicker({
    dateFormat:'dd-mm-yy',
    firstDay:1,
});
$('#secondary-datepicker').change(function(){
    goToDate(moment(this.value, "DD-MM-YYYY").format("YYYY-MM-DD"));
});
$('.day-nav').click(function(){
    var dp = $('#secondary-datepicker');
    var selectedDate = moment(dp.val(), "DD-MM-YYYY");
    switch(this.getAttribute('nav')){
        case 'next':
            var toDate = selectedDate.add(1, 'days');
            goToDate(toDate.format("YYYY-MM-DD"));
            break;
        case 'prev':
            var toDate = selectedDate.add(-1, 'days');
            goToDate(toDate.format("YYYY-MM-DD"));
            break;
        default:
            break;
    }
});
var floatingDatepickerShowing = false;
$(document).on("calendarLoaded", function(e){
    if (e.calendar = "calendar-1"){
        var firstCalendarTop = $("#calendar-1 > .fc-view-container").offset().top;
        $(window).scroll(function(){
            var currentPos = window.pageYOffset;
            if (currentPos >= firstCalendarTop && !floatingDatepickerShowing){
                $('#floating-datepicker').show(300);
                floatingDatepickerShowing = true;
            } else if (currentPos < firstCalendarTop && floatingDatepickerShowing && !$("#changingSchedule").is(":visible")){
                $('#floating-datepicker').hide(300);
                floatingDatepickerShowing = false;
            }
        });
        $(window).scroll();
    }
});
</script>
