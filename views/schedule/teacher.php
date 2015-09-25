<html>
<head>
<meta charset="utf-8"/>
</head>
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
    <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/calendar.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/utils.js"></script>
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
</head>
<?php
	$teacherModel = User::model()->findByPk($teacher);
?>
<body>
<div class="details-class">
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