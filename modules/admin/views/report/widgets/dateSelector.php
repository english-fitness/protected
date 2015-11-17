<style>
	input, select{
		font-size: inherit;
	}
	label{
		margin: 0 10px 0;
		line-height: 36px;
	}
	.datepicker-input{
		font-size: 15px;
		height: 36px;
	}
</style>
<form id="report-form" type="get" class="fs15">
	<div class="clearfix container-fluid">
		<div id="reportParameter" class="row">
			<div id="reportSelector" class="fL">
				<select id="reportType" name="report" class="w200">
					<?php foreach(ReportBuilder::reportOptions() as $key=>$value):?>
					<option value="<?php echo $key?>"><?php echo $value?></option>
					<?php endforeach;?>
				</select>
			</div>
			<div id="param" class="w150 fL mL20">
				<select name="type" id="type">
					<option value="date">Ngày</option>
					<option value="week">Tuần</option>
					<option value="month">Tháng</option>
					<option value="range">Từ ngày</option>
				</select>
			</div>
			<div id="value" class="w500 fL mL20">
				<div id="dateSelector" class="selector w150">
					<input type="text" id="date" name="date" class="datepicker-input" value="<?php echo date('Y-m-d', strtotime('-1 day'))?>"/>
				</div>
				<div id="weekSelector" class="selector w280">
					<select id="week" name="week">
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
								echo "<option value='" . $i . "' " . $highlight . ">" . $i . " (từ " . $weekstart . ")" . $thisWeekText . "</option>";
							}
						?>
					</select>
				</div>
				<div id="monthSelector" class="selector">
					<div class="w150 fL">
						<select id="month" name="month">
							<?php
								$thisMonth = date('m');
								for ($i = 1; $i <= 12; $i++){
									$highlight = ($thisMonth == $i) ? "selected style='background-color:rgba(50, 93, 167, 0.2)'" : "";
									echo "<option value=" . $i . " " . $highlight . ">" . $i . "</option>";
								}
							?>
						</select>
					</div>
					<div class="vam mL10 w250 fL">
						<label for="year" class="fL">Năm</label>
						<select id="year" name="year" class="w150">
							<?php
								$minYear = '2015';
								$currentYear = date('Y');
								for ($i = $minYear; $i <= $currentYear; $i++){
									$highlight = ($currentYear == $i) ? "selected style='background-color:rgba(50, 93, 167, 0.2)'" : "";
									echo '<option value="' . $i . '" ' . $highlight . '>' . $i . '</option>';
								}
							?>
						</select>
					</div>
				</div>
				<div id="rangeSelector" class="selector">
					<div class="w150 fL">
						<input type="text" id="dateFrom" name="dateFrom" class="datepicker-input"/>
					</div>
					<div class="vam mL10">
						<label for="year" class="fL">Đến ngày</label>
						<input type="text" id="dateTo" name="dateTo" class="datepicker-input fL w150"/>
					</div>
				</div>
			</div>
		</div>
		<div id="extra-params">
			<div class="selector row mT10" data-report="session">
				<label class="fL mL0" for="subject">Khóa học</label>
				<?php
					echo CHtml::dropDownList(
						'subject',
						'',
						array('all'=>'Tất cả') + Subject::model()->generateSubjectFilters(),
						array(
							'class'=>'pull-left w350'
						)
					);
				?>
			</div>
			<div class="row mT10 selector dpn" data-report="userRegistration">
				<div class="fL">
					<label class="fL mL0" for="source" >Nguồn</label>
					<?php
						echo CHtml::dropDownList(
							'source',
							'',
							array('all'=>'Tất cả', "allOnline"=>"Tất cả - Online") + PreregisterUser::allowableSource(),
							array(
								'class'=>'pull-left w250'
							)
						)
					?>
				</div>
				<div class="fL mL20">
					<label class="fL mL0" for="source" >Người tư vấn</label>
					<?php
						echo CHtml::dropDownList(
							'saleUserId',
							'',
							array('all'=>'Tất cả') + Student::model()->getSalesUserOptions(false, "", false),
							array(
								'class'=>'pull-left w250'
							)
						)
					?>
				</div>
			</div>
		</div>
	</div>
</form>
<div class="mT10">
	<button class="btn btn-primary" id="report_btn">View</button>
	<?php if(isset($recordCount) && $recordCount > 0):?>
		<?php if($recordCount < $maxRecordNumber || !isset($maxRecordNumber)):?>
		<button class="btn btn-primary mL10" id="export-button">Export</button>
		<?php else:?>
		<div class="mT10">
			<p><i>Export to Excel file is not available due to massive number of records. Please try narrowing down the date range.</i></p>
		</div>
		<?php endif;?>
		<?php 
			$reportParams = $_GET;
			unset($reportParams['report']);
		?>
		<a class="btn btn-primary fR" id="edit-button" href="/admin/sessionMonitor?<?php echo http_build_query($reportParams)?>">Edit</a>
	<?php endif;?>
</div>
<script>
	<?php if(!empty($_GET)):?>
		var requestParams = <?php echo json_encode($_GET)?>;

		if (requestParams.report){
			document.getElementById('reportType').value = requestParams.report;
		}
	<?php endif;?>
	$('.datepicker-input').datepicker({
		dateFormat:'yy-mm-dd',
	});
	$(function(){
		if (typeof requestParams !== 'undefined'){
			setParams(requestParams);
		}
		$('#reportType').change(function(){
			var type = this.value;
			$('#extra-params').find('.selector').each(function(){
				var $this = $(this);
				if ($this.data('report') == type){
					$this.show().find('select').prop('disabled', false);
				} else {
					$this.hide().find('select').prop('disabled', true);
				}
			});
		}).change();
		$('#type').change(function(){
			setSelector(true);
		});
		setSelector(false);
		
		$('#report_btn').click(function(e){
			e.preventDefault();
            var formData = $('#report-form').serializeArray();
            var params = {};

            for (var i in formData){
                var data = formData[i];
                params[data['name']] = data['value'];
            }

            if (typeof requestParams !== 'undefined' && requestParamsEqual(requestParams, params)){
                return;
            }

            $('#report-form').submit();
		});
		$('#export-button').click(function(e){
			e.preventDefault();
			$('#report-form').attr('action', '/admin/report/export').submit().attr('action', '');
		});
	});
	
	function setSelector(hideExportButton){
		if(hideExportButton){
			$('#export-button').hide();
		}
		
		$('#reportParameter').find('.selector').hide().find('input, select').prop('disabled', true);

		$('#'+document.getElementById('type').value+'Selector').show().find('input, select').prop('disabled', false);

	}
	
	function setParams(params){
		$('#type').val(params.type);
		$('.selector').find('input, select').each(function(){
			if (params[this.id]){
				this.value = params[this.id];
			}
		});
	}
	
	function requestParamsEqual(param1, param2){
		for (var property in param1) {
			if (param1.hasOwnProperty(property) && param2.hasOwnProperty(property)) {
				if (param1[property] != param2[property]){
					return false;
				}
			} else {
				return false;
			}
		}
		
		return true
	}
</script>