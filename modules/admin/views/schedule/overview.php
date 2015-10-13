<link rel="stylesheet" type="text/css" href="/media/css/calendar.css" />
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/moment.min.js'></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/utils.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/calendar/calendar.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/jquery/jquery.qtip.min.js"></script>
<link rel="stylesheet" type="text/css" href="/media/css/jquery/jquery.qtip.min.css" />
<style>
.no-text-select {
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}
.calendar-td{
	border: 1px solid;
	border-color: white;
	text-align: center;
	line-height: 35px;
}
.calendar-th{
	width: 150px;
	height:41px;
	border: 1px solid;
	border-color: white;
	text-align: center;
	background: buttonface;
}
.schedule{
	border-radius:0px !important;
	-moz-appearance:none !important;
	-webkit-appearance:none !important;
    width: 100%;
    height: 35px;
    outline: none;
    border: solid rgba(120,120,120,0.35) 1px;
    vertical-align: middle;
}
.schedule{
    cursor:default !important;
}
</style>

<div class="details-class container-fluid no-text-select" style="margin-bottom:150px">
	<div class="form-group">
		<label class="form-label">Tuần:</label>
		<div>
			<button id='prev-week' class='btn' style='float:left; height:36px'>Tuần trước</button>
			<select name='week_start' id='week_start' class="form-control" style="width:300px; float:left; margin: 0px 10px 10px 10px">
				<?php
					$week = date('W');
					$year = date('Y');
					$thisWeek = date('Y-m-d', strtotime('monday this week'));
					$dec28 = new DateTime('December 28th');
					$weeknumber = $dec28->format('W');
					for ($i = 1; $i <= $weeknumber; $i++){
						$w = $i >= 10 ? $i : "0" . $i;
						$highlight = ($i == $week) ? "selected style='background-color:rgba(50, 93, 167, 0.2)'" : "";
                        $thisWeekText = ($i == $week) ? " - Tuần này" : "";
						$weekstart = date('Y-m-d', strtotime($year . 'W' . $w));
						echo "<option value='" . $w . "' " . $highlight . ">" . $i . " (từ ngày " . $weekstart . ")" . $thisWeekText . "</option>";
					}
				?>
			</select>
			<button id='next-week' class='btn' style='float:left; height:36px'>Tuần tới</button>
		</div>
		<div class="fR" style="margin-right:10px; margin-top:-25px">
			<label for="view-select">View</label>
			<select id="view-select">
				<option value="available">Teacher Availability</option>
				<option value="booked">Scheduled Sessions</option>
			</select>
		</div>
	</div>
	<div>
		<table id="scheduleRegistration" class="table-calendar" style="clear:both;">
			<?php
				$startTime = array('09:00', '09:40' ,'10:20','11:00', '11:40', '12:20', '13:00', '13:40', '14:20', '15:00', '15:40', '16:20',
						'17:00', '17:40', '18:20', '19:00', '19:40', '20:20', '21:00', '21:40', '22:10');
				$endTime = array('09:30', '10:10', '10:50','11:30', '12:10', '12:50', '13:30', '14:10', '14:50', '15:30', '16:10', '16:50',
						'17:30', '18:10', '18:50', '19:30', '20:10', '20:50', '21:30', '22:10', '22:50');
				
				$timeslotCount = sizeof($startTime);
				
				$header = 	"<thead>
								<tr>
									<th class='calendar-th'>Thời gian</th>
									<th class='calendar-th wday' day='0'>Thứ hai<br></th>
									<th class='calendar-th wday' day='1'>Thứ ba<br></th>
									<th class='calendar-th wday' day='2'>Thứ tư<br></th>
									<th class='calendar-th wday' day='3'>Thứ năm<br></th>
									<th class='calendar-th wday' day='4'>Thứ sáu<br></th>
									<th class='calendar-th wday' day='5'>Thứ bảy<br></th>
									<th class='calendar-th wday' day='6'>Chủ nhật<br></th>
								</tr>
							</thead>";
				
				echo $header;
				for ($i = 0; $i < $timeslotCount; $i++){
					echo "<tr>";
					echo "<td class='calendar-td calendar-time'>" . $startTime[$i] . " - " . $endTime[$i] . "</td>";
					for ($d = 0; $d < 7; $d++){
						echo "<td class='calendar-td'><div id='" . ($timeslotCount*$d + $i) . "' class='schedule'></div></td>";
					}
					echo "</tr>";
				}
				echo $header;
			?>
		</table>
	</div>
</div>
<script type="text/javascript">
	var schedule = {};
	var weekstart;
	var teacherName = {};
	var studentName = {};

	function getAllTeacherSchedule(week){
		$.ajax({
			url:"/admin/schedule/getWeekSchedule?w="+week,
			type:"get",
			success:function(response){
				var teacherSchedule = [];
				for (var i in response.schedule){
					teacherSchedule[i] = JSON.parse("[" + response.schedule[i] + "]");
				}

				var bookedSlots = [];
				var booked = response.booked;
				for (var slot in booked){
					var timeslot = booked[slot];
					var slotMoment = moment(timeslot.plan_start);
					var slotDay = slotMoment.isoWeekday() - 1;
					var slotTime = slotMoment.format("HH:mm");
					if (!bookedSlots[timeslot.teacher_id]){
						slots = [{
							slot: getTimeslot(slotDay, slotTime),
							teacher: timeslot.teacher_id,
							student: timeslot.students,
						}];
						bookedSlots[timeslot.teacher_id] = slots;
					} else {
						bookedSlots[timeslot.teacher_id].push({
							slot: getTimeslot(slotDay, slotTime),
							teacher: timeslot.teacher_id,
							student: timeslot.students,
						});
					}
				}

				createWeekSchedule(teacherSchedule, bookedSlots);
			}
		});
	}

	function createWeekSchedule(teacherSchedule, bookedSlots){
		schedule = {};
		var presentingTeacher = [];
		var presentingStudent = [];

		for (var teacher in teacherSchedule){
			if (presentingTeacher.indexOf(teacher) == -1){
				presentingTeacher.push(teacher);
			}
			var available = teacherSchedule[teacher];
			for (var i in available){
				slot = schedule[available[i]];
				if (!slot){
					schedule[available[i]] = {
						available: 1,
						totalAvailable: 1,
						booked: 0,
						availableTeacher: [teacher],
						bookedTeacher: [],
					};
				} else {
					slot.available += 1;
					slot.totalAvailable += 1;
					slot.availableTeacher.push(teacher);
				}
			}

		}

		var rawStudent = [];
		for (var teacher in bookedSlots){
			if (presentingTeacher.indexOf(teacher) == -1){
				presentingTeacher.push(teacher);
			}
			var booked = bookedSlots[teacher];
			if (availableTeacher = teacherSchedule[teacher]){
				for (var i in booked){
					slot = schedule[booked[i].slot];
					rawStudent = rawStudent.concat(booked[i].student);
					//if a slot is present in available teacher, it should have been added to schedule before
					//so it's safe to assume that schedule've already had value for this timeslot
					if (availableTeacher.indexOf(booked[i].slot) > -1){
						slot.available -= 1;
						slot.booked += 1;
						slot.bookedTeacher.push(booked[i]);
					} else {
						if (slot){
							slot.booked += 1;
							slot.bookedTeacher.push(booked[i]);
						} else {
							schedule[booked[i].slot] = {
								available: 0,
								totalAvailable: 0,
								booked: 1,
								bookedTeacher: [booked[i]],
							}
						}
					}
				}
			} else {
				for (var i in booked){
					slot = schedule[booked[i].slot];
					rawStudent = rawStudent.concat(booked[i].student);
					if (slot){
						slot.booked += 1;
						slot.bookedTeacher.push(booked[i]);
					} else {
						schedule[booked[i].slot] = {
							available: 0,
							totalAvailable: 0,
							booked: 1,
							bookedTeacher: [booked[i]],
						}
					}
				}
			}
		}

		$.each(rawStudent, function(i, el){
		    if($.inArray(el, presentingStudent) === -1) presentingStudent.push(el);
		});

		getUserName(presentingTeacher, function(teachers){
			teacherName = teachers;
		});
		getUserName(presentingStudent, function(students){
			studentName = students;
		});

		displayWeekSchedule(schedule, document.getElementById("view-select").value);
	}

	function displayWeekSchedule(schedule, view){
		if (!view){
			view = "available"
		}

		$('.has-tooltip').qtip('destroy', true);
		$(".schedule").css("background-color", "white").removeClass("has-tooltip").html("");

		if (view == "available"){
			for (var slot in schedule){
				var thisSlot = schedule[slot];
				var rate = thisSlot.available/thisSlot.totalAvailable;
				if (thisSlot.totalAvailable > 0){
					if (thisSlot.available <= 0){
						$("#"+slot).css("background-color", "darkorange").addClass("has-tooltip").html("No teacher available");
					} else if (rate > 0.5 && rate != 1) {
						var thisSlot = schedule[slot];
						$("#"+slot).css("background-color", "greenyellow").addClass("has-tooltip").html(thisSlot.available + "/"
							+ thisSlot.totalAvailable + " teacher(s) available");
					} else if (rate > 0.5) {
						var thisSlot = schedule[slot];
						$("#"+slot).css("background-color", "lime").addClass("has-tooltip").html(thisSlot.available + "/"
							+ thisSlot.totalAvailable + " teacher(s) available");
					} else {
						var thisSlot = schedule[slot];
						$("#"+slot).css("background-color", "gold").addClass("has-tooltip").html(thisSlot.available + "/"
							+ thisSlot.totalAvailable + " teacher(s) available");
					}
				}
			}
		} else if (view == "booked"){
			for (var slot in schedule){
				var thisSlot = schedule[slot];

				if (thisSlot.booked > 0){
					$("#"+slot).css("background-color", "lime").addClass("has-tooltip").html(thisSlot.booked + " session(s) scheduled");
				}
			}
		}

		$(".has-tooltip").each(function(){
			var slotNumber = this.id;
			$(this).qtip({
				overwrite: false,
				content: {
					title: getTimeslotText(slotNumber, weekstart),
		            text: function(){
			            	var slot = schedule[slotNumber];
			            	var text="";
			            	var scheduledTeacher = slot.bookedTeacher;
			            	if (view != "booked"){
			            	var allTeacher = slot.availableTeacher;
			            	for (var i in allTeacher){
			            		if (!inArrayByProp(scheduledTeacher, 'teacher', allTeacher[i])){
				            		text += "- " + teacherName[allTeacher[i]] + "<br>";
				            	}
			            	}

			            	if (text != ""){
			            		text = "<b>Available:</b><br>" + text;
			            	}
			            }

		            	if (scheduledTeacher.length > 0){
			            	text += "<b>Scheduled:</b><br>"
			            	for (var i in scheduledTeacher){
			            		text += "- " + teacherName[scheduledTeacher[i].teacher] + ": " + studentName[scheduledTeacher[i].student] + "<br>"
			            	}
			            }

		            	return text;
		            },
		        },
		        position: {
			        target: 'mouse',
			        adjust: { mouse: false }
			    },
			    show: 'mousedown',
			    hide: 'mousedown',
			    events: {
			        show: function(event, api) {
			            $('.qtip').qtip('hide');
			        }
			    }
			})
		});
	}

	function getUserName(users, callback){
		$.ajax({
			url:"/user/getName",
			type:"get",
			data:{
				id:users,
			},
			success:function(response){
				callback(response.users);
			}
		});
	}

	$(".page-content-container").click(function(){
		$('.qtip').qtip('hide');
	});

	$("#week_start").change(function(){
		setHeader();
		//temporary use these two lines to show that the view is reloaded, will find another way
		$('.has-tooltip').qtip('destroy', true);
		$(".schedule").css("background-color", "white").removeClass("has-tooltip").html("");
		getAllTeacherSchedule(this.value);
		weekstart = this.value;
	});

	document.getElementById('prev-week').onclick = function(e){
		e.preventDefault();
		var weekSelection = document.getElementById('week_start');
		weekSelection.value = weekSelection.options[weekSelection.selectedIndex - 1].value;
		$(weekSelection).change();
		return false;
	};
	document.getElementById('next-week').onclick = function(e){
		e.preventDefault();
		var weekSelection = document.getElementById('week_start');
		weekSelection.value = weekSelection.options[weekSelection.selectedIndex + 1].value;
		$(weekSelection).change();
		return false;
	};

	$("#view-select").change(function(){
		displayWeekSchedule(schedule, this.value);
	})

	$(function(){
		setHeader();
		$("#week_start").change();
	});

	function setHeader(){
		$('.wday').each(function(){
			var thisOne = $(this);
			var day = thisOne.attr('day');
			var html = thisOne.html();
			var week = document.getElementById('week_start').value;
            var thisDay=moment().day("Monday").week(week).add(day, "days").format("DD-MM-YYYY");
			thisOne.html(html.substr(0, html.indexOf('<br>') + 4) + ' ' + thisDay);
		});
	}

	function inArrayByProp(array, prop, value){
		for (var i in array){
			if (array[i][prop] == value)
				return true;
		}
	}
</script>
