<link href="/media/js/calendar/fullcalendar.css" rel="stylesheet">
<link href="/media/js/calendar/fullcalendar.print.css" rel="stylesheet" media="print">
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/student.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/util.js"></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/moment.js'></script>
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/fullcalendar.min.js'></script>

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
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;"><?php echo Yii::t('lang', 'schedule')?></p></div>
<?php $this->renderPartial('student.views.class.myCourseTab'); ?>
<div class="details-class">
	<form class="form-inline form-element-container" role="form" style="margin-left:35px; padding-bottom:0px">
		<div class="form-group">
			<label class="form-label"><?php echo Yii::t('lang', 'search_teacher')?>: </label>
			<input id="teacherSearchBox" type="text" class="form-control" placeholder="<?php echo Yii::t('lang', 'student_search_teacher_placeholder')?>" style="width:500px;">
			<input id="teacherId" type="hidden" name="teacher">
			<input type="submit" value="<?php echo Yii::t('lang', 'search')?>" class="btn" style="margin-top: 0px">
		</div>
		<div>
			<a href="<?php echo Yii::app()->baseUrl?>/student/schedule/calendar"><?php echo Yii::t('lang', 'all_teachers')?></a>
		</div>
	</form>
	<form class="form-inline form-element-container" role="form" style="margin-left:35px; padding-top:0px">
		<div class="form-group">
			<label class="form-label" for="month-selection"><?php echo Yii::t('lang', 'month_selection')?>: </label>
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
		<?php echo $teacherModel->getProfilePictureHtml(array('style'=>'margin:3px;width:180px;height:180px'));?>
	</div>
    <div id="calendar" style="width:1000px; margin:35px"></div>
	<?php $this->renderPartial('colorLegendWidget');?>
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
			SearchBox.bindSearchEvent("teacherSearchBox", searchTeacher);
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
				url:'<?php echo Yii::app()->baseUrl;?>/student/schedule/getSessions',
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
							actionUrl: '<?php echo Yii::app()->baseUrl?>/student/schedule/bookSession',
							teacher: event.teacher,
							start:event.start.format('YYYY-MM-DD HH:mm:ss'),
						}
						$("<div><?php echo Yii::t('lang', 'student_confirm_book_session')?></div>").dialog({
							title:"<?php echo Yii::t('lang', 'student_book_session')?>",
							modal:true,
							resizable:false,
							buttons:{
								"<?php echo Yii::t('lang', 'button_accept')?>": function(){
									bookSession(values, bookSuccess, bookError);
									$(this).dialog('close');
								},
								"<?php echo Yii::t('lang', 'button_cancel')?>": function(){
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
			
			SearchBox.bindSearchEvent("teacherSearchBox", searchTeacher);
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
				url:'<?php echo Yii::app()->baseUrl?>/student/schedule/getSessions',
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
			$("<div><?php echo Yii::t('lang', 'student_book_success')?></div>").dialog({
				modal:true,
				resizable:false,
				buttons:{
					"<?php echo Yii::t('lang', 'button_close')?>": function(){
						//reload calendar
						reloadCalendar();
						$(this).dialog('close');
					},
				}
			});
		}
		
		function bookError(response){
			if (response.canRebook){
				$("<div><?php echo Yii::t('lang', 'student_book_duplicate_pending')?></div>").dialog({
					modal:true,
					resizable:false,
					buttons:{
						"<?php echo Yii::t('lang', 'student_change_teacher')?>": function(){
							changeTeacher(response)
							$(this).dialog('close');
						},
						"<?php echo Yii::t('lang', 'button_cancel')?>": function(){
							$(this).dialog('close');
						}
					}
				});
			} else if (response.canRebook === false){
				$("<div><?php echo Yii::t('lang', 'student_book_duplicate_approved')?></div>").dialog({
					modal:true,
					resizable:false,
					buttons:{
						"<?php echo Yii::t('lang', 'button_close')?>": function(){
							$(this).dialog('close');
						},
					}
				});
			} else if(response.reason == "no_active_course"){
				$("<div><?php echo Yii::t('lang', 'no_active_course')?></div>").dialog({
					modal:true,
					resizable:false,
					buttons:{
						"<?php echo Yii::t('lang', 'button_close')?>": function(){
							$(this).dialog('close');
						},
					}
				});
			} else{
				$("<div><?php echo Yii::t('lang', 'book_error')?></div>").dialog({
					modal:true,
					resizable:false,
					buttons:{
						"<?php echo Yii::t('lang', 'button_close')?>": function(){
							$(this).dialog('close');
						},
					}
				});
			}
		}
		
		function changeTeacher(data){
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/student/schedule/changeTeacher',
				type:'post',
				data:{
					session: data.existingSession,
					teacher: data.teacher
				},
				success:function(response){
					if (response.success){
						$("<div><?php echo Yii::t('lang', 'student_book_success')?></div>").dialog({
							modal:true,
							resizable:false,
							buttons:{
								"<?php echo Yii::t('lang', 'button_close')?>": function(){
									//reload calendar
									reloadCalendar();
									$(this).dialog('close');
								},
							}
						});
					} else {
						$("<div><?php echo Yii::t('lang', 'book_error')?></div>").dialog({
							modal:true,
							resizable:false,
							buttons:{
								"<?php echo Yii::t('lang', 'button_close')?>": function(){
									$(this).dialog('close');
								},
							}
						});
					}
				}
			});
		}
		
		function displayDetail(data){
			var detail = "<div><?php echo Yii::t('lang', 'session')?>: " + data.title + "</div>";
			detail += "<div><?php echo Yii::t('lang', 'session_date')?>: " + data.start.format('DD-MM-YYYY') + "</div>";
			detail += "<div><?php echo Yii::t('lang', 'session_time')?>: " + data.start.format('HH:mm') + "</div>";
			detail = "<div>" + detail + "</div>";
			$(detail).dialog({
				title:"<?php echo Yii::t('lang', 'session_detail')?>",
				modal:true,
				resizable:false,
				buttons:{
					"<?php echo Yii::t('lang', 'cancel_session')?>":function(){
						unbookSchedule(data.id);
						$(this).dialog('close');
					},
					"<?php echo Yii::t('lang', 'button_close')?>":function(){
						$(this).dialog('close');
					}
				}
			});
		}
		
		function unbookSchedule(sessionId){
			$("<div><?php echo Yii::t('lang', 'student_unbook_confirm')?></div>").dialog({
				title:"Hủy buổi học",
				modal:true,
				resizable:false,
				buttons:{
					"<?php echo Yii::t('lang', 'button_yes')?>": function(){
						$.ajax({
							url:'<?php echo Yii::app()->baseUrl?>/student/schedule/unbookSession',
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
					"<?php echo Yii::t('lang', 'button_no')?>": function(){
						$(this).dialog('close');
					}
				}
			});
		}
		
		function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/student/schedule/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				SearchBox.autocomplete('teacherSearchBox', data, function(id){$('#teacherId').val(id);});
			}
		});
	}
	</script>
<?php endif;?>