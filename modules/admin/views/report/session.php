<style>
    select{
        display:inherit !important;
        font-size:15px;
    }
    input{
        display:inherit !important;
        width:150px;
        font-size:15px;
    }
    label{
        margin-right:10px;
    }
</style>
<div class="col col-lg-6" style="margin-bottom:40px; margin-left:-15px">
        <h2 class="page-title mT10">Báo cáo số buổi học</h2>
    </div>
<div id="reportParameter" class="clearfix"">
    <div id="param" style="width:150px; float:left">
        <select name="type" id="type">
            <option value="1">Ngày</option>
            <option value="2">Tuần</option>
            <option value="3">Tháng</option>
            <option value="4">Từ ngày</option>
        </select>
    </div>
    <div id="value" style="width:500px; float:left; margin-left:20px;">
        <div id="dateSelector" class="selector dpn" style="width:100px">
            <input type="text" id="date" name="date" class="datepicker-input" value="<?php echo date('Y-m-d', strtotime('-1 day'))?>"/>
        </div>
        <div id="weekSelector" class="selector dpn"" style="width:280px">
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
        <div id="monthSelector" class="selector dpn">
            <div style="display:inline;">
                <select id="month" name="month" style="width:150px">
                    <?php
                        $thisMonth = date('m');
                        for ($i = 1; $i <= 12; $i++){
                            $highlight = ($thisMonth == $i) ? "selected style='background-color:rgba(50, 93, 167, 0.2)'" : "";
                            echo "<option value=" . $i . " " . $highlight . ">" . $i . "</option>";
                        }
                    ?>
                </select>
            </div>
            <div style="display:inline-block; vertical-align:middle; margin-left:10px; width:200px">
                <label for="year">Năm</label>
                <select id="year" name="year" style="width:150px">
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
        <div id="dateRangeSelector" class="selector dpn">
            <div style="display:inline">
                <input type="text" id="date_from" name="date_from" class="datepicker-input"/>
            </div>
            <div style="display:inline-block; vertical-align:middle; margin-left:10px">
                <label for="year">Đến ngày</label>
                <input type="text" id="date_to" name="date_to" class="datepicker-input"/>
            </div>
        </div>
    </div>
</div>
<div style="margin-top:10px">
    <button class="btn btn-primary" id="report_btn">View</button>
    <?php if(isset($sessions) && $sessions->totalItemCount > 0):?>
        <?php if($sessions->totalItemCount < 500):?>
        <button class="btn btn-primary" style="margin-left:10px" id="export-button">Export</button>
        <?php else:?>
        <div style="margin-top:10px">
            <p><i>Export to Excel file is not available due to massive number of records.</i></p>
        </div>
        <?php endif;?>
    <?php endif;?>
</div>
<?php if(isset($sessions)):?>
<div id="reportData" style="margin-top:40px">
    <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'dataGridView',
            'dataProvider'=>$sessions,
            'enableHistory'=>true,
            'pager' => array('class'=>'CustomLinkPager'),
            'columns'=>array(
                array(
                    'header'=>'Session ID',
                    'value'=>'$data["session_id"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:80px'),
                ),
                array(
                    'header'=>'Session Date',
                    'value'=>'$data["session_date"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Session Time (Hanoi)',
                    'value'=>'$data["session_time_hn"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Session Time (PH)',
                    'value'=>'$data["session_time_ph"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Tutor name',
                    'value'=>'$data["session_tutor"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Student name',
                    'value'=>'$data["session_student"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Lesson Type',
                    'value'=>'$data["session_type"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Status',
                    'value'=>'$data["session_status"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Tool',
                    'value'=>'$data["session_tool"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Remarks',
                    'value'=>'$data["session_remarks"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:300px'),
                ),
            ),
        ));
    ?>
</div>
<?php endif;?>
<script>
    <?php if(isset($requestParams)):?>
        var requestParams = <?php echo json_encode($requestParams)?>;
    <?php endif;?>
    $('.datepicker-input').datepicker({
        dateFormat:'yy-mm-dd',
    });
    $(function(){
        if (typeof requestParams !== 'undefined'){
            setParams(requestParams);
        }
        $('#type').change(function(){
            setSelector(true);
        });
        setSelector(false);
        
        
        $('#report_btn').click(function(){
            var params = createRequestParams();
            
            if (typeof requestParams !== 'undefined' && requestParamsEqual(requestParams, params)){
                return;
            }
            
            window.location.href = "/admin/report/session?" + $.param(params);
        });
        $('#export-button').click(function(){
            var params = createRequestParams();
            
            params.report = 'session';
            
            window.location.href = "/admin/report/export?" + $.param(params);
        });
    });
    
    function setSelector(hideExportButton){
        if(hideExportButton){
            $('#export-button').hide();
        }
        
        switch (document.getElementById('type').value){
            case '1':
                $('.selector').hide();
                $('#dateSelector').show();
                break;
            case '2':
                $('.selector').hide();
                $('#weekSelector').show();
                break;
            case '3':
                $('.selector').hide();
                $('#monthSelector').show();
                break;
            case '4':
                $('.selector').hide();
                $('#dateRangeSelector').show();
                break;
            default:
                break;
        }
    }
    
    function setParams(params){
        switch (params.type){
            case 'date':
                $('#type').val(1);
                $('#date').val(params.date);
                break;
            case 'week':
                $('#type').val(2);
                $('#week').val(params.week);
                break;
            case 'month':
                $('#type').val(3);
                $('#month').val(params.month);
                $('#year').val(params.year);
                break;
            case 'range':
                $('#type').val(4);
                $('#date_from').val(params.dateFrom);
                $('#date_to').val(params.dateTo);
                break;
            default:
                break;
        }
    }
    
    function createRequestParams(){
        var type = $('#type').val();
        switch (type){
            case '1':
                var params = {
                    type:"date",
                    date:$('#date').val(),
                }
                break;
            case '2':
                var params = {
                    type:"week",
                    week:$('#week').val(),
                }
                break;
            case '3':
                var params = {
                    type:"month",
                    month:$('#month').val(),
                    year:$('#year').val(),
                }
                break;
            case '4':
                var params = {
                    type:"range",
                    dateFrom:$('#date_from').val(),
                    dateTo:$('#date_to').val(),
                }
                break;
            default:
                break;
        }
        
        return params;
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
