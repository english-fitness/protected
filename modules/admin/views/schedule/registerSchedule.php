<link rel="stylesheet" type="text/css" href="/media/css/calendar.css" />
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
select.schedule{
	border-radius:0px !important;
	-moz-appearance:none !important;
	-webkit-appearance:none !important;
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
}
</style>

<?php
	$teacherModel = User::model()->findByPk($teacher);
?>
<div class="details-class">
	<form class="form-inline form-element-container" role="form" style="margin:0 auto; width:700px; padding:10px">
		<div class="form-group">
			<label class="form-label">Tìm giáo viên: </label>
			<input id="teacherSearchBox" type="text" class="form-control" placeholder="Nhập tên giáo viên để tìm kiếm" style="width:500px;">
			<input id="searchTeacherId" type="hidden" name="teacher">
			<input type="submit" value="Tìm" class="btn" style="margin-top: 0px">
		</div>
	</form>
	<div style="text-align:center;">
		<span style="margin:0 auto; font-size:15px"><b><?php echo $teacherModel->fullname() . " - " . $teacherModel->username?></b></span>
	</div>
	<div class="form-group">
		<label class="form-label">Week:</label>
		<div>
			<button id='prev-week' class='btn' style='float:left; height:36px'>Previous</button>
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
						$thisWeekText = ($i == $week) ? " - This week" : "";
						$weekstart = date('Y-m-d', strtotime($year . 'W' . $w));
						echo "<option value='" . $weekstart . "' " . $highlight . ">" . $i . " (from " . $weekstart . ")" . $thisWeekText . "</option>";
					}
				?>
			</select>
			<button id='next-week' class='btn' style='float:left; height:36px'>Next</button>
		</div>
	</div>
	<!--
	<div class="form-group" style='float:right'>
		<div style='margin-top:25px'>
			<button id='bulk-action-activate' class='btn' style='float:left; height:36px'>Multiple Select</button>
			<select id='bulk-select' class="form-control" style="width:120px; float:left; margin: 0px 10px 10px 10px; display:none">
				<option value='0'>N/a</option>
				<option value='1' style='background-color:yellow'>Available</option>
			</select>
			<button id='bulk-action-confirm' class='btn' style='float:left; height:36px; display:none'>OK</button>
		</div>
	</div>
	-->
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
									<th class='calendar-th'>Time</th>
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
						echo "<td class='calendar-td'>
								<div class='bulk-select' timeslot=" . ($timeslotCount*$d + $i) . ">
									<select id='" . ($timeslotCount*$d + $i) . "' class='schedule' disabled>
										<option value='0'>N/a</option>
										<option value='1' style='background-color:yellow'>Available</option>
									</select>
								</div>
							 </td>";
					}
					echo "</tr>";
				}
				echo $header;
			?>
		</table>
	</div>
	<div class="row" style='height:20px; padding-top:20px'>
		<p style='color: red; display:none' id='saving'>Processing...</p>
		<p style='color: green; display:none' id='saved'>Schedule saved</p>
	</div>
	<div class="row">
		<div class="col-md-5">
			
		</div>
		<div class="col-md-7">
			<input type="button" id="saveSchedule" value="Save Schedule" class="text-center gui btn btn-primary" />
		</div>
	</div>
</div>
<script>
	var modifying = false;
	var currentWeekSelection;
	
	$(document).ready(function(){
		setHeader()
		
		$('#saveSchedule').on('click', function(e){
			if (modifying){
				collectData();
			}
			e.preventDefault();
			return false;
		});
		
		$('.schedule').on('change', function(){
			if ($(this).val() == 1){
				$(this).css("background-color", "yellow");
			} else {
				$(this).css("background-color", "white");
			}
			modifying = true;
		});
		
		loadSchedule();
		currentWeekSelection = document.getElementById('week_start').value;
		
		document.getElementById('week_start').onchange = function(){
			if (modifying){
				$("<div>You are modifying this week's schedule, navigating away from it will cause all unsaved changes to lost. " +
					"<br>Are you sure you want to do this?</div>").dialog({
					title:"You forgot to save your schedule!",
					modal:true,
					resizable:false,
					width:650,
					buttons:{
						"Yes, proceed": function(){
							loadSchedule();
							currentWeekSelection = document.getElementById('week_start').value;
							modifying = false;
							$(this).dialog('close');
						},
						"No, take me back": function(){
							document.getElementById('week_start').value = currentWeekSelection;
							$(this).dialog('close');
						},
						"Save it for me": function(){
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
		bindSearchBoxEvent("teacherSearchBox", searchTeacher);
		// $('.bulk-select').click(function(){
			// console.log('ya');
			// var select = $(this);
			// if (!select.hasClass('bulk-selected')){
				// select.addClass('bulk-selected');
			// } else {
				// select.removeClass('bulk-selected');
			// }
		// });
		// $('#bulk-action-activate').click(function(e){
			// e.preventDefault();
			// if (!$(this).hasClass('btn-primary')){
				// $(this).html('Close');
				// $(this).addClass('btn-primary');
				// $('#bulk-select').show();
				// $('#bulk-action-confirm').zIndex(999999);
				
				// for (var i = 0; i < 147; i++){
					// var select = $('#'+i);
					// if (!select.hasClass('booked')){
						// select.prop('disabled', true);
						// $('.bulk-select[timeslot='+select.attr('id')+']').show();
					// }
				// }
			// } else {
				// $(this).html('Multiple Select');
				// $(this).removeClass('btn-primary');
				// $('#bulk-select').hide();
				// $('#bulk-action-confirm').zIndex(-999);
				
			// }
			// return false;
		// });
	});
	
	$("a").on('click', function(e){
		if (modifying){
			var link = this;
			e.preventDefault();
			$("<div>You are modifying this week's schedule, navigating away from it will cause all unsaved changes to lost. " +
				"<br>Are you sure you want to do this?</div>").dialog({
				title:"You forgot to save your schedule!",
				modal:true,
				resizable:false,
				width:650,
				buttons:{
					"Yes, proceed": function(){
						$(this).dialog('close');
						window.location.href = link.href;
					},
					"No, take me back": function(){
						$(this).dialog('close');
					},
					"Save it for me": function(){
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
			selection.disabled = true;
			selection.style.background = '';
			var options = selection.options;
			for (var j in options){
				if (options[j].value == 0){
					options[j].text = 'N/a';
				} else {
					options[j].text = 'Available';
				}
			}
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
		}
		
		var selected = document.getElementById('week_start').value.replace(/-/g, '/');
		var thisWeek = '<?php echo $thisWeek;?>'.replace(/-/g, '/');
		for (var i = 0; i < 147; i++){
			var selection = document.getElementById(i);
			if (slotNumbers.indexOf(''+i) > -1){
				selection.value = 1;
				selection.style.background = "yellow";
			} else {
				selection.value = 0;
			}
			if (selected >= thisWeek && bookedSessions.indexOf(i) == -1){
				selection.disabled = false;
			} else if (bookedSessions.indexOf(i) > -1){
				selection.options[selection.selectedIndex].text = "Booked";
				$(selection).addClass('booked');
			}
		}
	}
	
	function setHeader(){
		$('.wday').each(function(){
			var thisOne = $(this);
			var day = thisOne.attr('day');
			var html = thisOne.html();
			thisOne.html(html.substr(0, html.indexOf('<br>') + 4) + ' ' + addDay(document.getElementById('week_start').value, parseInt(day)));
		});
	}
	
	function addDay(date, amount){
		var denormalizedDate = date.replace(/-/g, '/')
		var result = new Date(denormalizedDate);
		result.setDate(result.getDate() + amount);
		var month = (result.getMonth() + 1 < 10) ? '0' + (result.getMonth() + 1) : result.getMonth() + 1;
		var day = (result.getDate() < 10) ? '0' + (result.getDate()) : result.getDate();
		
		//normalize date format
		return day + '-' + month + '-' + result.getFullYear();
	}
	
	//display loading message, copy from student js
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
	
	//searchBoxHandler
	function bindSearchBoxEvent(searchBoxId, searchFunction){
		$("#"+searchBoxId).keyup(function(){
			var keyword =  $(this).val();
			if(keyword.length<=3 && keyword.length>0) {
				searchFunction.call(undefined, keyword);
			}
		});
	}

	function searchBoxAutocomplete(searchBox, results, selectCallback){
		var searchBox = $('#'+searchBox)
		if (selectCallback){
			searchBox.autocomplete({
				source: formatSearchResult(results),
				height:'50',
				select:function(e, ui){
					selectCallback.call(undefined, ui.item.id);
				},
			});
		} else {
			searchBox.autocomplete({
				source: formatSearchResult(results),
				height:'50',
			});
		}
	}

	function formatSearchResult(result){
		var formattedData = [];
		result.forEach(function(value,key){
			formattedData[formattedData.length] = {
				'value': value.usernameAndFullName,
				'id': value.id,
			}
		});
		return formattedData;
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
</script>
<!--.class-->
