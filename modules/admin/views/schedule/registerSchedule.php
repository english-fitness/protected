<link rel="stylesheet" type="text/css" href="/media/css/calendar.css" />
<script src='<?php echo Yii::app()->baseUrl; ?>/media/js/moment.min.js'></script>
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/utils.js"></script>
<style>
.calendar-td{
	border: 1px solid;
	border-color: white;
	text-align: center;
}
.calendar-th{
	width: 150px;
	height:41px;
	border: 1px solid;
	border-color: white;
	text-align: center;
	background: buttonface;
}
button.schedule{
	border-radius:0px !important;
	-moz-appearance:none !important;
	-webkit-appearance:none !important;
    width: 100%;
    height: 35px;
    outline: none;
    border: solid rgba(120,120,120,0.35) 1px;
}
.bulk-select{
	cursor:pointer !important;
	z-index:-999;
}
.bulk-selected{
	border-color:rgba(82, 168, 236, 0.8);
	border-width:2px;
}
.booked{
	background-color:lime !important;
    cursor:not-allowed !important;
}
</style>

<?php
	$teacherModel = User::model()->findByPk($teacher);
?>
<div class="details-class">
    <?php $this->renderPartial('widgets/searchBox')?>
	<div style="text-align:center;">
		<span style="margin:0 auto; font-size:15px"><b><?php echo $teacherModel->fullname() . " - " . $teacherModel->username?></b></span>
	</div>
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
						echo "<option value='" . $weekstart . "' " . $highlight . ">" . $i . " (từ ngày " . $weekstart . ")" . $thisWeekText . "</option>";
					}
				?>
			</select>
			<button id='next-week' class='btn' style='float:left; height:36px'>Tuần tới</button>
		</div>
	</div>
	<div>
		<table id="scheduleRegistration" class="table-calendar" style="clear:both">
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
						echo "<td class='calendar-td'><button id='" . ($timeslotCount*$d + $i) . "' class='schedule' disabled></button></td>";
					}
					echo "</tr>";
				}
				echo $header;
			?>
		</table>
	</div>
	<div class="row" style='height:20px; padding-top:20px'>
		<p style='color: red; display:none' id='saving'>Đang lưu lịch dạy...</p>
		<p style='color: green; display:none' id='saved'>Đã lưu lịch dạy</p>
	</div>
	<div class="row">
		<div class="col-md-5">
			
		</div>
		<div class="col-md-7">
			<input type="button" id="saveSchedule" value="Lưu lịch dạy" class="text-center gui btn btn-primary" />
		</div>
	</div>
</div>
<script>
    var options = [
        {
            text:'N/a',
            color:'white',
        },
        {
            text:'Available',
            color:'yellow',
        }
    ];
	var modifying = false;
	var currentWeekSelection;
    
    function array_rotate(array, index){
        if (index == array.length - 1){
            return 0;
        } else {
            return parseInt(index) + 1;
        }
    }
	
	$(document).ready(function(){
		setHeader()
		
		$('#saveSchedule').on('click', function(e){
			if (modifying){
				collectData();
			}
			e.preventDefault();
			return false;
		});
		
		$('.schedule').on('click', function(){
            nextValue = array_rotate(options, this.value);
            nextOption = options[nextValue];
            this.value = nextValue;
            this.innerHTML = nextOption.text;
            $(this).css("background-color", nextOption.color);
			modifying = true;
		});
		
		loadSchedule();
		currentWeekSelection = document.getElementById('week_start').value;
		
		document.getElementById('week_start').onchange = function(){
			if (modifying){
				$("<div>Bạn đang sửa lịch dạy của tuần này. Chuyển sang trang khác sẽ làm mất toàn bộ thay đổi hiện tại. " +
				"<br>Bạn có muốn tiếp tục không?</div>").dialog({
					title:"Lịch dạy chưa được lưu",
					modal:true,
					resizable:false,
					width:650,
					buttons:{
						"Có, tiếp tục": function(){
							loadSchedule();
							currentWeekSelection = document.getElementById('week_start').value;
							modifying = false;
							$(this).dialog('close');
						},
						"Không, quay lại": function(){
							document.getElementById('week_start').value = currentWeekSelection;
							$(this).dialog('close');
						},
						"Lưu lại và tiếp tục": function(){
							collectData(currentWeekSelection);
							loadSchedule();
							currentWeekSelection = document.getElementById('week_start').value;
							modifying = false;
							$(this).dialog('close');
						},
					},
					close: function(){
						document.getElementById('week_start').value = currentWeekSelection;
					}
				});
			} else {
				loadSchedule();
				currentWeekSelection = document.getElementById('week_start').value;
				modifying = false;
			}
		}
		
		document.getElementById('prev-week').onclick = function(e){
			e.preventDefault();
			var weekSelection = document.getElementById('week_start');
			weekSelection.value = weekSelection.options[weekSelection.selectedIndex - 1].value;
			weekSelection.onchange();
			return false;
		};
		document.getElementById('next-week').onclick = function(e){
			e.preventDefault();
			var weekSelection = document.getElementById('week_start');
			weekSelection.value = weekSelection.options[weekSelection.selectedIndex + 1].value;
			weekSelection.onchange();
			return false;
		};
	});
	
	$("a").on('click', function(e){
		if (modifying){
			var link = this;
			e.preventDefault();
			$("<div>Bạn đang sửa lịch dạy của tuần này. Chuyển sang trang khác sẽ làm mất toàn bộ thay đổi hiện tại. " +
				"<br>Bạn có muốn tiếp tục không?</div>").dialog({
				title:"Lịch dạy chưa được lưu",
				modal:true,
				resizable:false,
				width:650,
				buttons:{
					"Có, tiếp tục": function(){
						$(this).dialog('close');
						window.location.href = link.href;
					},
					"Không, quay lại": function(){
						$(this).dialog('close');
					},
					"Lưu lại và tiếp tục": function(){
						collectData(currentWeekSelection);
						$(this).dialog('close');
						window.location.href = link.href;
					},
				},
			});
		}
	});
	
	function collectData(weekStart){
		if (weekStart === undefined){
			var weekStart = document.getElementById('week_start').value;
		}
		var timeslots = '';
		for (var i = 0; i < 147; i++){
			var timeslot = document.getElementById(i);
			if (timeslot.value == 1){
				if (timeslots === ''){
					timeslots = timeslot.id;
				} else {
					timeslots += ', ' + timeslot.id;
				}
			}
		}
        
		$('#saved').hide();
		$('#saving').show();
		$.ajax({
			url:'<?php Yii::app()->baseUrl?>/admin/schedule/saveSchedule',
			type: 'post',
			data: {
				teacher:<?php echo $teacher?>,
				week_start: weekStart,
				timeslots:timeslots,
			},
			success:function(response){
				$('#saving').hide();
				if (response.success){
					$('#saved').show();
					modifying = false;
				} else {
					$('<div>Đã có lỗi xảy ra khi lưu lịch dạy, vui lòng thử lại sau</div>').dialog({
						title:'Lưu lịch dạy',
						modal:true,
						resizable:false,
						buttons:{
							'Đóng':function(){
								$(this).dialog('close');
							}
						}
					});
				}
			}
		});
	}
	
	function loadSchedule(){
		loading.created();
		for (var i = 0; i < 147; i++){
			var selection = document.getElementById(i);
			selection.style.background = '';
            selection.disabled = true;
            $(selection).removeClass('booked');
		}
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/getTeacherSchedule',
			data:{
				teacher:<?php echo $teacher?>,
				week_start: document.getElementById('week_start').value,
			},
			success: function(response){
				populateSchedule(response);
				setHeader();
				loading.removed();
				$('#saved').hide();
			}
		});
	}
	
	function populateSchedule(data){
		var slotNumbers;
		if (data.bookedSessions){
			var bookedSessions = data.bookedSessions;
		} else {
			var bookedSessions = [];
		}
		if (!data.timeslots){
			slotNumbers = [];
		} else {
			slotNumbers = data.timeslots.split(/[,;]\s*/);
            console.log(slotNumbers);
		}
		
		var selected = document.getElementById('week_start').value.replace(/-/g, '/');
		var thisWeek = '<?php echo $thisWeek;?>'.replace(/-/g, '/');
		for (var i = 0; i < 147; i++){
			var selection = document.getElementById(i);
            if (selected >= thisWeek){
                selection.disabled = false;
                selection.style.background = "white";
            }
			if (slotNumbers.indexOf(""+i) > -1){
				selection.value = 1;
				selection.style.background = "yellow";
                selection.innerHTML = "Available";
			} else {
				selection.value = 0;
                selection.innerHTML = "N/a";
			}
			if (bookedSessions.indexOf(i) > -1){
                selection.disabled = true;
                selection.innerHTML = "Booked";
				$(selection).addClass('booked');
			}
		}
	}
	
	function setHeader(){
		$('.wday').each(function(){
			var thisOne = $(this);
			var day = thisOne.attr('day');
			var html = thisOne.html();
            var thisDay=moment(document.getElementById('week_start').value).add(day, "days").format("DD-MM-YYYY");
			thisOne.html(html.substr(0, html.indexOf('<br>') + 4) + ' ' + thisDay);
		});
	}
	
	//display loading message, copy from student.js
	var loading =[];
	loading.created = function(message)
	{
		if (!message){
			var message = "Đang tải..."
		}
		var courseLoading = $(".loading").length;
		if(courseLoading==0)
		{
			$("body").append('<div class="loading">' + message + '</div>');
		}
	}
	loading.removed = function()
	{
		$(".loading").remove();
	}
	
</script>
<!--.class-->
