<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/calendar.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.js'></script>

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
</style>

<?php
	$registration = new ClsRegistration();
	$hours = json_encode($registration->hoursInDay());
	$minutes = json_encode($registration->minutesInHour());
	// $timezone = 8;
?>

<div class="details-class">
	<form class="form-inline" role="form" style="margin:0 auto; width:700px">
		<div class="form-group">
			<label class="form-label">Tìm giáo viên: </label>
			<input id="teacherSearchBox" type="text" class="form-control" placeholder="Nhập tên giáo viên để tìm kiếm" style="width:500px;">
			<input id="searchTeacherId" type="hidden" name="teacher">
			<input type="submit" value="Tìm" class="btn" style="margin-top: 0px">
		</div>
	</form>
	<div style='margin:0 auto; text-align:center'>
		Trang <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<div style='margin:0 auto; text-align:center; width:871px;'>
		<button class='week-nav btn btn-primary' nav='prev'><</button>
		<button class='wday-select btn' day='0'>Thứ hai<br></button>
		<button class='wday-select btn' day='1'>Thứ ba<br></button>
		<button class='wday-select btn' day='2'>Thứ tư<br></button>
		<button class='wday-select btn' day='3'>Thứ năm<br></button>
		<button class='wday-select btn' day='4'>Thứ sáu<br></button>
		<button class='wday-select btn' day='5'>Thứ bảy<br></button>
		<button class='wday-select btn' day='6'>Chủ nhật<br></button>
		<button class='week-nav btn btn-primary' nav='next'>></button>
	</div>
	<div style='clear:both'></div>
	<?php if ($page > $pageCount)
			echo "<div>Không có dữ liệu</div>"?>
    <div id="calendar-1" style="width:1000px; margin:35px"></div>
	<div id="calendar-2" style="width:1000px; margin:35px"></div>
	<div id="calendar-3" style="width:1000px; margin:35px"></div>
	<div id="calendar-4" style="width:1000px; margin:35px"></div>
	<div style='margin:0 auto; text-align:center'>
		Trang <?php echo PaginationLinks::create($page, $pageCount)?>
	</div>
	<div style="position:fixed;top:500px;left:10px;width:160px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;">
		<span style="margin:3px"><b>Color legend</b></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:yellow;float:left;margin:3px"></div><span style="float:left">Available timeslot</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkgray;float:left;margin:3px"></div><span style="float:left">Closed</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:lime;float:left;margin:3px"></div><span style="float:left">Approved Session</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkgreen;float:left;margin:3px"></div><span style="float:left">Pending Session</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:turquoise;float:left;margin:3px"></div><span style="float:left">Ongoing Session</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:darkorange;float:left;margin:3px"></div><span style="float:left">Ended Session</span>
		<div style="clear:both"></div>
	</div>
</div>
<script>
	//global variables
	var options = {
		hours: <?php echo $hours;?>,
		minutes: <?php echo $minutes;?>
	}
	
	var minTime = '09:00';
	var maxTime = '22:51';
	
	var currentWday;
	//end global variables
	
	var allTeachers = <?php echo $teachers?>;
	var teacherGroups = [];
	for (var i = 0; i < allTeachers.length; i+=4){
		var group = allTeachers.slice(i, i + 4);
		
		teacherGroups.push(group);
	}
	
	for (var i = 0; i < teacherGroups.length; i++){
		loadCalendar('calendar-'+(i+1), teacherGroups[i]);
	}
	
	<?php if (isset($timezone)){
		echo 	'var convertedRange = convertRangeByTimezone(minTime, maxTime, 7, '. $timezone . ');
				minTime = convertedRange.minTime;
				maxTime = convertedRange.maxTime;';
	}?>
	
	$(document).ready(function(){
		currentWeekStart = '<?php echo date('Y/m/d', strtotime('monday this week'))?>';
		setHeader();
		$('.wday-select').click(function(){		
			var targetDate = addDay(currentWeekStart, $(this).attr('day'));
			
			for (var i = 1; i <= 4; i++){
				var calendar = $('#calendar-'+i);
				calendar.fullCalendar('gotoDate', targetDate);
			}
		});
		$('.week-nav').click(function(){
			var value = $(this).attr('nav');
			if (value == 'prev'){
				currentWeekStart = addDay(currentWeekStart, -7);
			} else if (value == 'next'){
				currentWeekStart = addDay(currentWeekStart, 7);
			}
			for (var i = 0; i < teacherGroups.length; i++){
				reloadCalendar('calendar-'+(i+1), teacherGroups[i], currentWeekStart);
				$('#calendar-'+(i+1)).fullCalendar('gotoDate', currentWeekStart);
			}
		});
		bindSearchBoxEvent("teacherSearchBox", searchTeacher);
	});
	
	//functions
	function loadCalendar (divId, teachers){
		$.ajax({
			type:'get',
			url:'<?php echo Yii::app()->baseUrl;?>/admin/schedule/getSessions',
			data:{
				teachers:JSON.stringify(teachers),
			},
			success:function(response){
				createCalendar(divId, response, teachers.length);
			}
		});
	}

	function createCalendar(divId, data, size){
		document.getElementById(divId).setAttribute("style","width:"+1000*(size/4)+"px; height:1300px;margin: 40px auto");
		var sessions = data.sessions;
		var availableSlots = data.availableSlots;
		
		var calendarDiv = $('#'+divId);
		
		calendarDiv.fullCalendar({
			height: 'auto',
			header: {
				left: 'prev today',
				right:'next',
				center: 'title',
			},
			viewRender: function(view,element) {
				currentWday = (view.start._d.getDay() + 6) % 7;
				
				//change weekday selection
				var currentSelect = $('.wday-select.btn-primary').attr('day');
				if (currentSelect != currentWday){
					$('.wday-select.btn-primary').removeClass('btn-primary');
					$('.wday-select[day='+currentWday+']').addClass('btn-primary');
				}
				
			},
			eventClick: function(event, jsEvent, view){
				if (event.className == 'reservedSlot'){
					var preset = {
						action: '<?php echo Yii::app()->baseUrl;?>/admin/schedule/calendarCreateSession',
						teacher: event.teacher,
						start: event.start,
					}
					CalendarSessionHandler.newSession(preset, createSessionSuccess, createSessionError, options);
					bindSearchBoxEvent("ajaxSearchStudent", searchStudent);
				} else if (['approvedSession', 'ongoingSession', 'pendingSession'].indexOf(event.className[0]) > -1){
					var data = {
						action: '<?php echo Yii::app()->baseUrl;?>/admin/schedule/calendarUpdateSession',
						sessionId: event.id,
						teacher: event.teacher,
						start: event.start,
						teacherName: event.teacherName,
						subject: event.subject,
						course_id: event.course_id,
						student: event.student,
						className: event.className,
						end: event.end,
					}
					CalendarSessionHandler.editSession(data, updateSessionSuccess, updateSessionError, options);
					bindSearchBoxEvent("ajaxSearchStudent", searchStudent);
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
	
	$('body').css('overflow-x', 'hidden');
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
	
	function reloadCalendar(calendarDiv, teachers, weekStart){
		var calendar = $('#'+calendarDiv);
		calendar.fullCalendar( 'removeEvents');
		calendar.fullCalendar('refetchEvents');
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/getSessions',
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
				if (weekStart){
					calendar.fullCalendar('gotoDate', addDay(currentWeekStart, currentWday));
					setHeader();
				}
			}
		});
	}
	
	function ajaxLoadCourse(studentId){
		document.getElementById('hiddenStudentId').value = studentId;
		$.ajax({
			url:"<?php echo Yii::app()->baseurl; ?>/admin/course/ajaxLoadCourse/student/"+studentId,
			type:"get",
			success: function(courses){
				//clearing old options
				document.getElementById("courseSelect").innerHTML = "";
				//setting new options
				var options = "";
				for (var i in courses){
					options += "<option value=" + courses[i].id + ">" + ((courses[i].title != "") ? courses[i].title : courses[i].id) + "</option>";
				}
				document.getElementById("courseSelect").innerHTML = options;
			}
		});
	}
	
	function searchStudent(keyword){
		$.ajax({
			url:"<?php echo Yii::app()->baseurl; ?>/admin/course/AjaxLoadStudent/keyword/"+keyword,
			type:"get",
			success: function(response) {
				var data = response[0];
				searchBoxAutocomplete('ajaxSearchStudent', data, ajaxLoadCourse);
			}
		});
	}

	function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				searchBoxAutocomplete('teacherSearchBox', data, function(id){$('#searchTeacherId').val(id);});
			}
		});
	}
	
	function createSessionSuccess(){
		reloadAll();
	}
	
	function createSessionError(response){
		if (response.existingSession){
			$("<div>Học sinh này đã đặt lịch học với một giáo viên khác trong cùng khung giờ. " +
			"Bạn có thể thay đổi giáo viên cho buổi học</div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"Thay đổi giáo viên": function(){
						changeTeacher(response)
						$(this).dialog('close');
					},
					"Hủy": function(){
						$(this).dialog('close');
					}
				}
			});
		} else {
			displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
		}
	}
	
	function updateSessionSuccess(){
		reloadAll();
	}
	
	function updateSessionError(response){
		if (response.existingSession){
			$("<div>Học sinh này đã đặt lịch học với một giáo viên khác trong cùng khung giờ. " +
			"Bạn có thể thay đổi giáo viên cho buổi học</div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"Thay đổi giáo viên": function(){
						duplicateSessionUpdate(response)
						$(this).dialog('close');
					},
					"Hủy": function(){
						$(this).dialog('close');
					}
				}
			});
		} else {
			displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
		}
	}
	
	function duplicateSessionUpdate(data){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/calendarUpdateSession',
			type:'post',
			data:{
				duplicateSession: true,
				existingSession: data.existingSession,
				currentSession: data.currentSession,
				courseId: data.currentCourse,
				subject: data.currentSubject,
				studentId: data.currentStudent,
			},
			success:function(response){
				if (response.success){
					reloadAll();
				} else {
					displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
				}
			}
		});
	}
	
	function changeTeacher(data){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/calendarUpdateSession',
			type:'post',
			data:{
				changeTeacher: true,
				session: data.existingSession,
				teacher: data.teacher
			},
			success:function(response){
				if (response.success){
					reloadAll();
				} else {
					displayConfirmDialog("Đặt lịch học", "Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau", "Đóng");
				}
			}
		});
	}
	
	function deleteSession(sessionId){
		$('<div>Bạn có muốn xóa buổi học này?</div>').dialog({
			modal:true,
			resizable:false,
			buttons: {
				"Xóa": function(){
					$.ajax({
						url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/calendarDeleteSession',
						type:'post',
						data:{
							id:sessionId,
						},
						success:function(){
							reloadAll();
						}
					});
					$(this).dialog('close');
				},
				"Hủy": function(){
					$(this).dialog('close');
				}
			}
		});
	}
	
	function approveSession(id){
		$('<div>Bạn có muốn xác nhận buổi học này?</div>').dialog({
			modal:true,
			resizable:false,
			buttons: {
				"Xác nhận": function(){
					$.ajax({
						url:'<?php echo Yii::app()->baseUrl?>/admin/session/ajaxApprove',
						type:'post',
						data:{
							session_id: id,
						},
						success:function(response){
							if (!response.success){
								displayConfirmDialog('Xác nhận buổi học', 'Đã có lỗi xảy ra, vui lòng thử lại sau', 'Đóng');
							} else {
								if (response.pendingCourse){
									$("<div>Buổi học của khóa học này vẫn chưa được xác nhận"+
									". Các buổi học của khóa học này sẽ không được nhìn thấy.<br>"+
									"Bạn có muốn xác nhận khóa học này?</div>").dialog({
										title:'Xác nhận khóa học',
										width:500,
										modal:true,
										resizable:false,
										buttons:{
											"Xác nhận":function(){
												$.ajax({
													url:'<?php echo Yii::app()->baseUrl?>/admin/course/ajaxApprove',
													type:'post',
													data:{
														course_id: response.pendingCourse,
													},
													success: function(response){
														if (!response.success){
															displayConfirmDialog('Xác nhận khóa học', 'Đã có lỗi xảy ra, vui lòng thử lại sau', 'Đóng');
														}
													}
												})
												$(this).dialog('close');
											},
											"Đóng":function(){
												$(this).dialog('close');
											},
										}
									});
								}
								reloadAll();
							}
						}
					});
					$(this).dialog('close');
				},
				"Hủy": function(){
					$(this).dialog('close');
				}
			}
		});
	}
	
	function endSession(id, endTime){
		var now = moment();
		var end = moment(endTime);
		
		if (end.diff(now) > 0){
			displayConfirmDialog("Kết thúc buổi học", "Buổi học vẫn chưa hết thời gian", "Đóng");
		} else {
			$('<div>Bạn có muốn kết thúc buổi học này?</div>').dialog({
				title:"Kết thúc buổi học",
				modal:true,
				resizable:false,
				buttons: {
					"Kết thúc": function(){
						$.ajax({
							url:'<?php echo Yii::app()->baseUrl?>/api/session/end',
							type:'get',
							data:{
								sessionId: id,
								forceEnd: true,
							},
							success:function(){
								reloadAll();
							}
						});
						$(this).dialog('close');
					},
					"Hủy": function(){
						$(this).dialog('close');
					}
				}
			});
		}
	}
	
	function setHeader(){
		$('.wday-select').each(function(){
			var thisOne = $(this);
			var day = thisOne.attr('day');
			var html = thisOne.html();
			thisOne.html(html.substr(0, html.indexOf('<br>') + 4) + ' ' + addDay(currentWeekStart, parseInt(day)));
		});
	}
	
	function displayConfirmDialog(title, confirmText, confirmButton){
		var buttons = {};
		buttons[confirmButton] = function(){
			$(this).dialog('close');
		};
		$("<div>"+confirmText+"</div>").dialog({
			title:title,
			modal:true,
			resizable:false,
			buttons:buttons,
		});
	}
	
	function reloadAll(){
		for (var i = 0; i < teacherGroups.length; i++){
			reloadCalendar('calendar-'+(i+1), teacherGroups[i], currentWeekStart);
		}
	}
</script>
