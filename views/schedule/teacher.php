<html>
	<head>
		<meta charset="utf-8"/>
		<title>Speak up - Teacher Schedule</title>
	</head>
	<?php
		$teacherModel = User::model()->findByPk($teacher);
	?>
	<body>
		<style>
		    .header{
		        padding:15px;
		        display:table-cell;
		        vertical-align:middle;
		        background-color:#245ba7;
		        width:100%;
		        height:60px;
		        position:absolute;
		        top:0;
		        left:0;
		        font-size:25px;
		        font-weight:bold;
		    }
			.fc-button{
				display: block !important;
			}
		</style>


		<div class="header text-center">
		    <img src="/media/images/logo/logo-white-bordered-500.png" style="float:left;height:45px;margin:-10px 160px">
		    <div style="width:400px; margin:0 auto">
		        <p style="color:white">Teacher Schedule</p>
		    </div>
		</div>
		<div class="details-class" style="padding:100px 0 50px">
		    <?php $this->renderPartial('widgets/colorLegend')?>
		    <div style="border-bottom:dashed 1px rgba(0,0,0,0.1); width:700px; margin-left:170px; margin-bottom:30px">
		        <?php $this->renderPartial('widgets/searchBox')?>
		        <div style="margin-left:110px">
		            <a href="<?php echo Yii::app()->baseUrl?>/schedule">All teachers</a>
		        </div>
		    </div>
			<div style="text-align:center;">
				<span style="margin:0 auto; font-size:15px"><b><?php echo $teacherModel->fullname() . " - " . $teacherModel->username?></b></span>
			</div>
		    <div style="margin-left:170px">
				<label class="form-label" for="month-selection">Month: </label>
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
		    <div id="calendar" style="width:1000px; margin:35px auto"></div>
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
					loadCalendar();
					$('#month-selection').on('change', function(){
						var thisMonth = moment(parseInt($(this).val()) + 1, 'MM');
						start = moment.utc(thisMonth).startOf('month');
						end = moment.utc(thisMonth).endOf('month');
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
						url:'<?php echo Yii::app()->baseUrl;?>/schedule/getSessions',
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
						url:'<?php echo Yii::app()->baseUrl?>/schedule/getSessions',
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
			</script>
		<?php endif;?>
	</body>
</html>