<style>
    .wday-selector{
        margin:0 auto;
        text-align:center;
        width:871px;
        height:65px;
    }
    .date-navigator{
        margin:0 auto;
        text-align:center;
        width:250px;
        margin-top:10px;
    }
    .date-navigator .ui-datepicker-trigger{
        border:none;
        width:34px;
        height:34px;
        background-color:#428bca;
        border-radius:5px;
        outline:none;
    }
    .date-navigator .ui-datepicker-trigger img{
        height:30px;
    }
</style>
<div class="wday-selector">
    <button class='week-nav btn btn-primary' nav='prev' title='Tuần trước'><</button>
    <button class='wday-select btn' day='0'>Thứ hai<br></button>
    <button class='wday-select btn' day='1'>Thứ ba<br></button>
    <button class='wday-select btn' day='2'>Thứ tư<br></button>
    <button class='wday-select btn' day='3'>Thứ năm<br></button>
    <button class='wday-select btn' day='4'>Thứ sáu<br></button>
    <button class='wday-select btn' day='5'>Thứ bảy<br></button>
    <button class='wday-select btn' day='6'>Chủ Nhật<br></button>
    <button class='week-nav btn btn-primary' nav='next' title='Tuần tiếp theo'>></button>
</div>
<div class="date-navigator">
    <button class='btn today-select' style="margin-right:5px; outline:none">Hôm nay (<?php echo date('d-m-Y')?>)</button>
    <input type="hidden" class="mini-datepicker"></input>
</div>
<script>
    var currentDateObj = moment('<?php echo $current_day?>');
    var currentDate;
    var currentWeekStart;
    var currentWday;
    var today = moment().format('YYYY-MM-DD');

    //initialize global variables
    currentDate = currentDateObj.format('YYYY-MM-DD');
    currentWeekStart = moment(currentDateObj).startOf('isoWeek').format('YYYY-MM-DD');
    currentWday = currentDateObj.isoWeekday() - 1;

    $(document).ready(function(){
		setHeader();
		$('.wday-select').click(function(){
			var targetDate = moment(currentWeekStart).add($(this).attr('day'), "days").format("YYYY-MM-DD");

			for (var i = 1; i <= 4; i++){
				var calendar = $('#calendar-'+i);
				calendar.fullCalendar('gotoDate', targetDate);
			}
		});
		$('.week-nav').click(function(){
			var value = $(this).attr('nav');
			if (value == 'prev'){
				currentWeekStart = moment(currentWeekStart).add(-7, "days").format("YYYY-MM-DD");
			} else if (value == 'next'){
				currentWeekStart = moment(currentWeekStart).add(7, "days").format("YYYY-MM-DD");
			}
			for (var i = 0; i < teacherGroups.length; i++){
				reloadCalendar('calendar-'+(i+1), teacherGroups[i], currentWeekStart);
			}
            setHeader();
		});
        $('.today-select').click(function(){
            goToDate(today);

        });
        $('.mini-datepicker').datepicker({
            showOn:'button',
            buttonImage:'/media/images/icon/calendar.png',
            buttonText:'Chọn ngày',
            buttomImageOnly:true,
            dateFormat:'yy-mm-dd',
            firstDay:1,
        });
        $('.mini-datepicker').change(function(){
            goToDate(this.value);
        });
	});

    function setHeader(){
		$('.wday-select').each(function(){
			var thisOne = $(this);
			var day = thisOne.attr('day');
			var html = thisOne.html();
            var date = moment(currentWeekStart).add(day, "days").format('DD-MM-YYYY')
			thisOne.html(html.substr(0, html.indexOf('<br>') + 4) + ' ' + date);
		});
	}

    function setPaginationLinkDate(date){
        var paginationLink = document.getElementsByClassName('page-navigation');
        if (date != today){
            for (var i = 0; i < paginationLink.length; i++){
                var link = paginationLink[i];
                var href = link.getAttribute('href');
                var datePos = href.indexOf('&date=');
                if (datePos > -1){
                    href = href.substr(0, datePos);
                }
                link.setAttribute('href', href + '&date=' + date);
            }
        } else {
            for (var i = 0; i < paginationLink.length; i++){
                var link = paginationLink[i];
                var href = link.getAttribute('href');
                var datePos = href.indexOf('&date=');
                if (datePos > -1){
                    href = href.substr(0, datePos);
                    link.setAttribute('href', href);
                }
            }
        }

    }

    function goToDate(date){
        var mustReload = false;

        var currentWeek = moment(currentWeekStart).isoWeek();
        var targetWeek = moment(date).isoWeek();
        if (currentWeek != targetWeek){
            mustReload = true;
        }

        if (mustReload){
            currentWeekStart = moment(date).startOf('isoWeek').format('YYYY-MM-DD');
            for (var i = 0; i < teacherGroups.length; i++){
                reloadCalendar('calendar-'+(i+1), teacherGroups[i], date);
            }
            setHeader();
        } else {
            for (var i = 0; i < teacherGroups.length; i++){
                $('#calendar-'+(i+1)).fullCalendar('gotoDate', date);
            }
        }
    }
</script>
