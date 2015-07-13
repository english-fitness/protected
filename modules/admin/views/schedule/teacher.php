<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/admin/calendar.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.js'></script>

<style>
.reservedSlot{
	cursor:pointer;
	-webkit-appearance:button;
	-moz-appearance:button;
}
.ui-autocomplete-input, .ui-menu, .ui-menu-item {
	z-index: 99999;
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
	$teacherModel = User::model()->findByPk($teacher);
?>

<div class="details-class">
	<form class="form-inline form-element-container" role="form" style="margin-left:150px">
		<div class="form-group">
			<label class="form-label">Tìm giáo viên: </label>
			<input id="teacherSearchBox" type="text" class="form-control" placeholder="Nhập tên giáo viên để tìm kiếm" style="width:500px;">
			<input id="searchTeacherId" type="hidden" name="teacher">
			<input type="submit" value="Tìm" class="btn" style="margin-top: 0px">
		</div>
		<div>
			<a href="<?php echo Yii::app()->baseUrl?>/admin/schedule/view">Tất cả giáo viên</a>
		</div>
		<div style="position:fixed;bottom:10px;left:10px;width:160px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;">
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
		<div style="position:fixed;top:330px;left:10px;width:160px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;text-align:justify; display:none"
		 id="changingSchedule">
			<p><b>Changing schedule</b></p>
			<p>Click on an available slot to change schedule. Click the button bellow to cancel</p>
			<button id="cancelChangeSchedule" class="btn" style="margin-left: 35px">Cancel</button>
		</div>
	</form>
	<div style="margin-left:150px">
		<label class="form-label" for="month-selection">Chọn tháng: </label>
		<select id="month-selection" style="width:500px">
			<?php
				$thisMonth = date('m');
				for ($i = 1; $i <= 12; $i++){
					$highlight = ($thisMonth == $i) ? "selected style='background-color:rgba(50, 93, 167, 0.2)'" : "";
					echo "<option value=" . $i . " " . $highlight . ">" . date('F', strtotime('2000-'.$i.'-01')) . "</option>";
				}
			?>
		</select>
	</div>
	<div style="text-align:center;">
		<span style="margin:0 auto; font-size:15px"><b><?php echo $teacherModel->fullname() . " - " . $teacherModel->username?></b></span>
	</div>
    <div id="calendar" style="width:1000px; margin:35px auto"></div>
</div>

<?php if(isset($teacher)):?>
	<script>
		var changingSchedule = false;
	
		var start = new Date('<?php echo date('Y-m-01')?>');
		var end = new Date('<?php echo date('Y-m-t')?>');
		
		var minTime = '09:00';
		var maxTime = '22:51';
		
		<?php if (isset($timezone)){
			echo 	'var convertedRange = convertRangeByTimezone(minTime, maxTime, 7, '. $timezone . ');
					minTime = convertedRange.minTime;
					maxTime = convertedRange.maxTime;';
		}?>
		
		$(document).ready(function(){
			bindSearchBoxEvent("teacherSearchBox", searchTeacher);
			loadCalendar();
			$('#month-selection').on('change', function(){
				var thisMonth = moment($(this).val(), 'MM');
				start = moment(thisMonth).startOf('month');
				end = moment(thisMonth).endOf('month');
				reloadCalendar();
				$('#calendar').fullCalendar('gotoDate', start.format('YYYY-MM-DD'));
				$(this).blur();
				clampView($('#calendar').fullCalendar('getView'));
			});
			$('#cancelChangeSchedule').click(function(){
				toggleChangeSchedule(false);
				return false;
			});
		});
		
		function loadCalendar(){
			$.ajax({
				type:'get',
				url:'<?php echo Yii::app()->baseUrl;?>/admin/schedule/getSessions',
				data:{
					teachers:JSON.stringify([<?php echo $teacher?>]),
					view:'month',
					month:document.getElementById('month-selection').value,
				},
				success:function(response){
					createCalendar(response);
				}
			});
		}
		
		function createCalendar(data){
			$('#calendar').fullCalendar('removeEvents');
			$('#calendar').fullCalendar('refetchEvents');
			$('#calendar').fullCalendar({
				height: "auto",
				header: {
					left: 'prev,next',
					center: 'title',
					right: 'prev,next'
				},
				viewRender: function(view,element) {
					//clamp view to current month
					clampView(view);
				},
				eventClick: function(event, jsEvent, view){
					if (changingSchedule){
						if (event.className == 'reservedSlot'){
							values = {
								teacher:event.teacher,
								start:event.start.format('YYYY-MM-DD HH:mm:ss'),
							}
							changeSchedule(pendingChangeSession, values);
						}
					}else {
						if (event.className == 'reservedSlot'){
							var preset = {
								action: '<?php echo Yii::app()->baseUrl;?>/admin/schedule/calendarCreateSession',
								teacher: event.teacher,
								start: event.start,
							}
							CalendarSessionHandler.newSession(preset, createSessionSuccess, createSessionError);
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
							CalendarSessionHandler.editSession(data, updateSessionSuccess, updateSessionError);
							bindSearchBoxEvent("ajaxSearchStudent", searchStudent);
						}
					}
				},
				minTime: minTime,
				maxTime: maxTime,
				slotDuration: '00:40:00',
				defaultView: 'agendaWeek',
				allDaySlot:false,
				axisFormat: 'H:mm',
				timeFormat: 'H:mm',
				columnFormat: 'ddd D/M',
				firstDay: 1,
				events:data.sessions,
			});
			
			<?php
			if (!isset($timezone)){
				echo "$('#calendar').fullCalendar('addEventSource', getAvailableTimeslot(data.availableSlots));";
			} else {
				echo "$('#calendar').fullCalendar('addEventSource', getAvailableTimeslot(data.availableSlots, ".$timezone."));";
			}
			?>
			
			$('.fc-title').css('cursor','default');
		}
		
		function clampView(view){
			if ( end <= view.end) {
				$("#calendar .fc-next-button").addClass('fc-state-disabled');
				return false;
			}
			else {
				$("#calendar .fc-next-button").removeClass('fc-state-disabled');
			}
			
			if ( view.start <= start) {
				$("#calendar .fc-prev-button").addClass('fc-state-disabled');
				return false;
			}
			else {
				$("#calendar .fc-prev-button").removeClass('fc-state-disabled');
			}
		}
		
		$('body').css('overflow-x', 'hidden');
		
		$(document).keyup(function(e) {
			switch(e.which) {
				case 37: // left
				$('.fc-left').children('.fc-button-group').children('.fc-prev-button').click();
				break;

				case 39: // right
				$('.fc-left').children('.fc-button-group').children('.fc-next-button').click();
				break;

				default: return;
			}
		});
		
		function reloadCalendar(){
			$('#calendar').fullCalendar( 'removeEvents');
			$('#calendar').fullCalendar('refetchEvents');
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/getSessions',
				type:'post',
				data:{
					teachers:JSON.stringify([<?php echo $teacher?>]),
					view:'month',
					month:document.getElementById('month-selection').value,
				},
				success: function(response){
					var newEvents = response.sessions;
					var newSlots = response.availableSlots;
					$('#calendar').fullCalendar('addEventSource', newEvents);
					$('#calendar').fullCalendar('addEventSource', getAvailableTimeslot(newSlots));
					$('#calendar').fullCalendar('refetchEvents');
				}
			});
		}
		
		function ajaxLoadCourse(studentId){
			document.getElementById('hiddenStudentId').value = studentId;
			$.ajax({
				url:"<?php echo Yii::app()->baseurl; ?>/admin/schedule/ajaxLoadCourse/student/"+studentId,
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
					$("#courseSelect").change();
				}
			});
		}
		
		function autoFillSessionTitle(courseId){
			$.ajax({
				url:"<?php echo Yii::app()->baseUrl?>/admin/schedule/countSession",
				type:"get",
				data:{
					course: courseId,
				},
				success:function(response){
					document.getElementById("sessionTitle").value = "Session " + (parseInt(response.sessionCount) + 1);
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
			reloadCalendar();
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
			reloadCalendar();
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
						reloadCalendar();
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
						reloadCalendar();
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
								reloadCalendar();
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
									reloadCalendar();
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
									reloadCalendar();
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
		
		function toggleChangeSchedule(changing, session){
			changingSchedule = changing;
			if (changing){
				pendingChangeSession = session;
				$('#changingSchedule').show();
			} else {
				pendingChangeSession = null;
				$('#changingSchedule').hide();
			}
		}
		
		function changeSchedule(session, values){
			console.log(session);
			console.log(values);
			$('<div>Bạn có muốn thay đổi lịch học sang khung giờ này?<br>- '+values.teacher+'<br>- '+values.start+'</div>').dialog({
				title:"Đổi lịch học",
				modal:true,
				resizable:false,
				buttons:{
					"Đồng ý": function(){
						$.ajax({
							url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/changeSchedule',
							data:{
								sessionId: session,
								teacher:values.teacher,
								start:values.start,
							},
							type:"post",
							success:function(response){
								if (response.success){
									reloadCalendar();
								} else {
									displayConfirmDialog("Đổi lịch học", "Đã có lỗi xảy ra, vui lòng thử lại sau", "Đóng");
								}
							}
						});
						$(this).dialog('close');
						toggleChangeSchedule(false);
					},
					"Hủy": function(){
						$(this).dialog('close');
					}
				}
			});
		}
		
		function searchTeacherToView(keyword){
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/ajaxSearchTeacher/keyword/' + keyword,
				type:'get',
				success:function(response){
					var data = response.result;
					searchBoxAutocomplete('teacherSearchBox', data, function(id){$('#searchTeacherId').val(id);});
				}
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
	</script>
<?php endif;?>