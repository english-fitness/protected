<html>
    <head>
        <meta charset="utf-8"/>
        <title>Speak up - Teacher Schedule</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/media/css/base/style.css" />
        <link rel="stylesheet" type="text/css" href="/themes/daykem/css/admin.css" />
        <link rel="stylesheet" type="text/css" href="/themes/daykem/bootstrap/css/bootstrap.min.css" />
        <link href="/themes/daykem/css/student.css" type="text/css" rel="stylesheet">
        <link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/media/css/calendar.css" />
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/calendar.js"></script>
        <script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
        <script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.js'></script>
    </head>
    <body>
        <style>
            .reservedSlot{
                -webkit-appearance:button;
                -moz-appearance:button;
            }
            .ui-autocomplete-input, .ui-menu, .ui-menu-item {
                z-index: 99999;
            }
            .fc-event{
                cursor:pointer;
            }
            .fc-time-grid-event .fc-time{
                display:none;
            }
            .fc-title{
                margin:auto 0 auto 0;
                vertical-align:middle;
                width: 100%;
                height:100%;
                text-align:center;
                overflow:hidden !important;
                text-overflow: ellipsis;
            }
            .fc-content{
                vertical-align:middle;
                height:100%;
            }
            .wday-select{
                outline:none !important;
                float:left;
                margin: 5px;
            }
            .fc-toolbar button{
                display:none;
            }
            .week-nav{
                width: 40px;
                height: 54px;
                float: left;
                margin: 5px;
                font-size: 20px;
                font-weight: bold;
            }
            .calendar-holder{
                margin:35px;
                position:relative;
            }
            .calendar-loading-indicator{
                display:none;
                width:100%;
                height:1170px;
                position:absolute;
                top:49px;
                left:0;
                background: url("/media/images/icon/large-loader-128.gif") no-repeat center center;
                background-color: rgba(250,250,250,0.7);
                z-index: 99999;
            }
        </style>


        <div class="details-class">
            <?php $this->renderPartial('widgets/colorLegend')?>
            <?php $this->renderPartial('widgets/searchBox')?>
            <div style='margin:0 auto; text-align:center'>
                Page <?php echo PaginationLinks::create($page, $pageCount)?>
            </div>
            <?php $this->renderPartial('widgets/wdaySelector', array('current_day'=>$current_day)) ?>
            <div style='clear:both'></div>
            <?php if ($page > $pageCount)
                    echo "<div>No data</div>"?>
            <div id="calendar-1" class="calendar-holder">
                <div class="calendar-loading-indicator"></div>
            </div>
            <div id="calendar-2" class="calendar-holder">
                <div class="calendar-loading-indicator"></div>
            </div>
            <div id="calendar-3" class="calendar-holder">
                <div class="calendar-loading-indicator"></div>
            </div>
            <div style='margin:0 auto; text-align:center'>
                Page <?php echo PaginationLinks::create($page, $pageCount)?>
            </div>
        </div>
        <script>
            var minTime = '09:00';
            var maxTime = '22:51';
            
            //end global variables
            
            var allTeachers = <?php echo $teachers?>;
            var teacherGroups = [];
            for (var i = 0; i < allTeachers.length; i+=4){
                var group = allTeachers.slice(i, i + 4);
                
                teacherGroups.push(group);
            }
            
            
            for (var i = 0; i < teacherGroups.length; i++){
                loadCalendar('calendar-'+(i+1), teacherGroups[i], currentDate);
            }
            
            <?php if (isset($timezone)){
                echo 'var convertedRange = convertRangeByTimezone(minTime, maxTime, 7, '. $timezone . ');
                     minTime = convertedRange.minTime;
                     maxTime = convertedRange.maxTime;';
            }?>
            
            //functions
            function loadCalendar (divId, teachers, date){
                if (!date){
                    var date = currentDate;
                    var weekStart = currentWeekStart;
                } else {
                    var weekStart = moment(date).startOf('isoWeek').format('YYYY-MM-DD');
                }
                $.ajax({
                    type:'get',
                    url:'<?php echo Yii::app()->baseUrl;?>/schedule/getSessions',
                    data:{
                        teachers:JSON.stringify(teachers),
                        week_start:weekStart,
                    },
                    success:function(response){
                        createCalendar(divId, response, teachers.length);
                        $('#'+divId).fullCalendar('gotoDate', date);
                    }
                });
            }

            function createCalendar(divId, data, size){
                var firstColumnWidth = 85;
                var calendarWidth = firstColumnWidth+(1000-firstColumnWidth)*(size/4);
                document.getElementById(divId).setAttribute("style","width:"+calendarWidth+"px; height:1300px;margin: 40px auto");
                var sessions = data.sessions;
                var availableSlots = data.availableSlots;
                
                var calendarDiv = $('#'+divId);
                
                calendarDiv.fullCalendar({
                    height: 'auto',
                    header: {
                        left: 'prev',
                        right:'next',
                        center: 'title',
                    },
                    viewRender: function(view,element) {
                        currentWday = (view.start._d.getDay() + 6) % 7;
                        
                        //change weekday selection
                        var currentSelect = $('.wday-select.selected').attr('day');
                        if (currentSelect != currentWday){
                            var selected = $('.wday-select.selected');
                            selected.removeClass('btn-primary selected');
                            $('.wday-select[day='+currentWday+']').addClass('btn-primary selected');
                        }
                        setPaginationLinkDate(addDay(currentWeekStart, currentWday));
                        
                        if (view.start.format('YYYY-MM-DD') == today){
                            $('#today-select').addClass('btn-primary');
                        } else {
                            $('#today-select').removeClass('btn-primary');
                        }
                    },
                    minTime: minTime,
                    maxTime: maxTime, //plus one minute so end time could be displayed
                    slotDuration: '00:40:00',
                    defaultView: 'resourceDay',
                    resources: data.teachers,
                    allDaySlot:false,
                    timezone:"local",
                    axisFormat: 'H:mm',
                    timeFormat: 'H:mm',
                    columnFormat: 'ddd D/M',
                    firstDay: 1,
                    events: sessions,
                });
                
                <?php
                    if (!isset($timezone)){
                        echo "$('#'+divId).fullCalendar('addEventSource', getAvailableTimeslot(availableSlots));";
                    } else {
                        echo "$('#'+divId).fullCalendar('addEventSource', getAvailableTimeslot(availableSlots, ".$timezone."));";
                    }
                ?>
            }
            
            $('.fc-title').css('cursor','default');
            
            $(document).keyup(function(e) {
                switch(e.which) {
                    case 37: // left
                    if (currentWday != 0){
                        $('.fc-prev-button').click();
                    }
                    break;

                    case 39: // right
                    if (currentWday != 6){
                        $('.fc-next-button').click();
                    }
                    break;

                    default: return;
                }
            });
            
            function reloadCalendar(calendarDiv, teachers, date){
                var calendar = $('#'+calendarDiv);
                var loadingIndicator = calendar.children('.calendar-loading-indicator');
                loadingIndicator.show();
                calendar.fullCalendar( 'removeEvents');
                calendar.fullCalendar('refetchEvents');
                var weekStart = moment(date).startOf('isoWeek').format('YYYY-MM-DD');
                $.ajax({
                    url:'/schedule/getSessions',
                    type:'get',
                    data:{
                        teachers: JSON.stringify(teachers),
                        week_start: weekStart,
                    },
                    success: function(response){
                        var newEvents = response.sessions;
                        var newSlots = response.availableSlots;
                        var teacher = response.teachers;
                        calendar.fullCalendar('addEventSource', newEvents);
                        calendar.fullCalendar('addEventSource', getAvailableTimeslot(newSlots));
                        calendar.fullCalendar('refetchEvents');
                        loadingIndicator.hide();
                        calendar.fullCalendar('gotoDate', moment(date).format('YYYY-MM-DD'));
                    }
                });
            }
        </script>
    </body>
</html>