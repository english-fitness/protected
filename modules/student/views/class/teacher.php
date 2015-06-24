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
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">Lịch học</p></div>
<?php $this->renderPartial('myCourseTab'); ?>
<div class="details-class">
	<form class="form-inline form-element-container" role="form" style="margin-left:35px; padding-bottom:0px">
		<div class="form-group">
			<label class="form-label">Tìm giáo viên: </label>
			<input id="teacherSearchBox" type="text" class="form-control" placeholder="Nhập tên giáo viên để tìm kiếm" style="width:500px;">
			<input id="teacherId" type="hidden" name="teacher">
			<input type="submit" value="Tìm" class="btn" style="margin-top: 0px">
		</div>
		<div>
			<a href="<?php echo Yii::app()->baseUrl?>/student/class/calendar">Tất cả giáo viên</a>
		</div>
	</form>
	<form class="form-inline form-element-container" role="form" style="margin-left:35px; padding-top:0px">
		<div class="form-group">
			<label class="form-label" for="month-selection">Chọn tháng: </label>
			<select id="month-selection" class="form-control" style="width:150px">
				<?php
					$thisMonth = date('m');
					for ($i = 1; $i <= 12; $i++){
						$highlight = ($thisMonth == $i) ? "selected style='background-color:rgba(50, 93, 167, 0.2)'" : "";
						echo "<option value=" . $i . " " . $highlight . ">" . date('F', strtotime('2000-'.$i.'-01')) . "</option>";
					}
				?>
			</select>
		</div>
	</form>
	<div style="text-align:center;">
		<span style="margin:0 auto; font-size:15px"><b><?php echo $teacherModel->fullname()?></b></span>
		<br>
		<img src="<?php echo Yii::app()->user->getProfilePicture($teacher)?>" style='margin:3px;width:225px;height:225px'></img>
	</div>
    <div id="calendar" style="width:1000px; margin:35px"></div>
	<div style="position:fixed;top:460px;left:10px;width:250px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;">
		<span style="margin:3px"><b>Color legend</b></span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:yellow;float:left;margin:3px"></div><span style="float:left">Available timeslot</span>
		<div style="clear:both"></div>
		<div style="width:25px;height:15px;background-color:dodgerblue;float:left;margin:3px"></div><span style="float:left">Booked timeslot</span>
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
		<div style="width:25px;height:15px;background-color:red;float:left;margin:3px"></div><span style="float:left">Canceled Session</span>
		<div style="clear:both"></div>
	</div>
</div>

<?php if(isset($teacher)):?>
	<script>
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
			loadCalendar("calendar", [<?php echo $teacher?>]);
			$('#month-selection').on('change', function(){
				var thisMonth = moment($(this).val(), 'MM');
				start = moment(thisMonth).startOf('month');
				end = moment(thisMonth).endOf('month');
				reloadCalendar();
				var calendar = $('#calendar');
				calendar.fullCalendar('gotoDate', start.format('YYYY-MM-DD'));
				$(this).blur();
				clampView(calendar.fullCalendar('getView'));
			});
		});
		
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
		
		function loadCalendar(divId, teacher){
			loading.created();
			$.ajax({
				type:'get',
				url:'<?php echo Yii::app()->baseUrl;?>/student/class/getSessions',
				data:{
					teachers:JSON.stringify(teacher),
					view:'month',
					month:document.getElementById('month-selection').value,
				},
				success:function(response){
					createCalendar(divId, response);
					loading.removed();
				}
			});
		}
		
		function createCalendar(divId, data){
			$('#calendar').fullCalendar({
				height: "auto",
				header: {
					left: 'prev,next',
					center: 'title',
					right: 'prev,next'
				},
				viewRender: function(view,element) {
					clampView(view);
				},
				eventClick: function(event, jsEvent, view){
					if (event.className == 'reservedSlot'){
						var values = {
							actionUrl: '<?php echo Yii::app()->baseUrl?>/student/class/bookSession',
							teacher: event.teacher,
							start:event.start.format('YYYY-MM-DD HH:mm:ss'),
						}
						$("<div>Bạn có muốn đặt lịch học trong khung giờ này?</div>").dialog({
							title:"Đặt lịch học",
							modal:true,
							resizable:false,
							buttons:{
								"Đồng ý": function(){
									bookSession(values, bookSuccess, bookError);
									$(this).dialog('close');
								},
								"Hủy": function(){
									$(this).dialog('close');
								}
							}
						});
					} else if (event.className == 'unbookable'){
						data = {
							id:event.id,
							start:event.start,
							title:event.subject,
						};
						displayDetail(data);
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
				echo "$('#'+divId).fullCalendar('addEventSource', getAvailableTimeslot(data.availableSlots));";
			} else {
				echo "$('#'+divId).fullCalendar('addEventSource', getAvailableTimeslot(data.availableSlots, ".$timezone."));";
			}
		?>
			
			$('.fc-title').css('cursor','default');
			
			bindSearchBoxEvent("teacherSearchBox", searchTeacher);
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
			loading.created();
			$('#calendar').fullCalendar( 'removeEvents');
			$('#calendar').fullCalendar('refetchEvents');
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/student/class/getSessions',
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
					loading.removed();
				}
			});
		}
		
		function bookSuccess(){
			$("<div>Bạn đã đặt lịch học thành công, buổi học của bạn cần được xác nhận trước khi bạn có thể vào lớp</div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"Đóng": function(){
						//reload calendar
						reloadCalendar();
						$(this).dialog('close');
					},
				}
			});
		}
		
		function bookError(response){
			if (response.canRebook){
				$("<div>Bạn đã đăng ký lịch học với một giáo viên khác trong cùng khung giờ. " +
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
			} else if (response.canRebook === false){
				$("<div>Bạn có một buổi học đã được xác nhận với một giáo viên khác trong cùng khung giờ. " +
				"Bạn không thể đăng ký một buổi học khác trong khung giờ này</div>").dialog({
					modal:true,
					resizable:false,
					buttons:{
						"Đóng": function(){
							$(this).dialog('close');
						},
					}
				});
			} else{
				$("<div>Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau</div>").dialog({
					modal:true,
					resizable:false,
					buttons:{
						"Đóng": function(){
							$(this).dialog('close');
						},
					}
				});
			}
		}
		
		function changeTeacher(data){
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/student/class/changeTeacher',
				type:'post',
				data:{
					session: data.existingSession,
					teacher: data.teacher
				},
				success:function(response){
					if (response.success){
						$("<div>Bạn đã đặt lịch học thành công, buổi học của bạn cần được xác nhận trước khi bạn có thể vào lớp</div>").dialog({
							modal:true,
							resizable:false,
							buttons:{
								"Đóng": function(){
									//reload calendar
									reloadCalendar();
									$(this).dialog('close');
								},
							}
						});
					} else {
						$("<div>Có lỗi xảy ra khi đặt lịch học, vui lòng thử lại sau</div>").dialog({
							modal:true,
							resizable:false,
							buttons:{
								"Đóng": function(){
									$(this).dialog('close');
								},
							}
						});
					}
				}
			});
		}
		
		function displayDetail(data){
			var detail = "<div>Buổi học: " + data.title + "</div>";
			detail += "<div>Ngày học: " + data.start.format('DD-MM-YYYY') + "</div>";
			detail += "<div>Giờ học: " + data.start.format('HH:mm') + "</div>";
			detail = "<div>" + detail + "</div>";
			$(detail).dialog({
				title:"Chi tiết buổi học",
				modal:true,
				resizable:false,
				buttons:{
					"Hủy buổi học":function(){
						unbookSchedule(data.id);
						$(this).dialog('close');
					},
					"Đóng":function(){
						$(this).dialog('close');
					}
				}
			});
		}
		
		function unbookSchedule(sessionId){
			$("<div>Bạn có muốn hủy buổi học này?</div>").dialog({
				title:"Hủy buổi học",
				modal:true,
				resizable:false,
				buttons:{
					"Có": function(){
						$.ajax({
							url:'<?php echo Yii::app()->baseUrl?>/student/class/unbookSession',
							type:'post',
							data:{
								session: sessionId,
							},
							success:function(){
								reloadCalendar();
							}
						});
						$(this).dialog('close');
					},
					"Không": function(){
						$(this).dialog('close');
					}
				}
			});
		}
		
		function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/student/class/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				searchBoxAutocomplete('teacherSearchBox', data, function(id){$('#teacherId').val(id);});
			}
		});
	}
	</script>
<?php endif;?>