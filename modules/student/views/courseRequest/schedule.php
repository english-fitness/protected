<?php $this->renderPartial('step',array("titlePage"=>$titlePage)); ?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/registration.js"></script>
<script type="text/javascript">    
	//Back step
    function backStep(){
        window.location = daykemBaseUrl + '/student/courseRequest/index';
    }
</script>
<?php
$registration = new ClsRegistration();//Init register session
$session = $registration->getSession('session');//Get session
$course = $registration->getSession("course");
?>
    <form id="formStep2" action="<?php echo $_SERVER['REQUEST_URI']?>" method="post"  class="form-horizontal">
        <div class="form_notice" id="validMessage">
            <?php if(!$checkValid):?>
                <div class="alert alert-danger">Vui lòng kiểm tra lại những trường dữ liệu bắt buộc* (số buổi/toàn khóa, số buổi/tuần, ngày bắt đầu, thời gian học)!</div>
            <?php endif;?>
        </div>
        
        <div class="row">
            <label class="col-sm-2 control-label">Chọn kiểu lớp:<span class="required">*</span> </label>
            <div class=" col-sm-7">
                <?php $totalOfStudent = isset($course['total_of_student'])? $course['total_of_student']: 1;?>
                <?php echo CHtml::dropDownList('Course[total_of_student]', $totalOfStudent, $registration->totalStudentAsGroupOptions(), array('id'=>'totalOfStudent', 'onchange'=>'generatePriceTable();','class'=>'form-control'));?>
            </div>
        </div>
        <div class="row">
           <label class="col-sm-2 control-label">Chọn số buổi/toàn khóa:<span class="required">*</span></label>
            <div id="loadPriceTable" class=" col-sm-8">
        		<?php echo $this->renderPartial('widget/priceTable', array('totalOfStudent'=>$totalOfStudent, 'hasTrial'=>$hasTrial, 'user'=>$user));?>
        	</div>
        </div>
        <div class="row">
            <label class="col-sm-2 control-label">Số buổi/tuần:<span class="required">*</span></label>
            <div class=" col-sm-2">
                <?php $numberSelected = isset($session['numberSessionPerWeek'])? $session['numberSessionPerWeek']: "";?>
                <?php echo CHtml::dropDownList('Session[numberSessionPerWeek]', $numberSelected, $registration->numberSessionsPerWeek(), array('id'=>'numberSessionPerWeek', 'onchange'=>'generateSchedule();',"class"=>"form-control"));?>
            </div>
            <div class=" col-sm-8">
                <label class="col-sm-2 control-label">Ngày bắt đầu dự kiến:<span class="required">*</span> &nbsp;</label>
                <div class=" col-sm-10">
                    <?php $startDate = isset($session['startDate'])? $session['startDate']: date('Y-m-d');?>
                    <input style="width: 100px; background-color:#fff;" class="datepicker form-control" type="text" id="displayStartDate" value="<?php echo date('d/m/Y', strtotime($startDate)); ?>" readonly="readonly">
                    <input type="hidden" name="Session[startDate]" id="startDate" value="<?php echo $startDate; ?>">
                    <label class="hint">(Định dạng dd/mm/yyyy & không nhỏ hơn ngày hiện tại)</label>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 control-label">Thời gian học phù hợp:<span class="required">*</span></label>
            <div class="col-lg-8 " id="selectedSchedule">
                <?php
                if(isset($session['dayOfWeek'])):
                    foreach($session['dayOfWeek'] as $key=>$dayOfWeek):
                        $index = $key+1;
                        ?>
                        <div class="date_register row">
                            <div class="col-xs-5">
                                <label  class="col-sm-3 control-label">Buổi&nbsp;<?php echo $index;?>:&nbsp;</label>
                                <div class="col-sm-9">
                                    <?php echo CHtml::dropDownList('Session[dayOfWeek][]', $dayOfWeek, $registration->daysOfWeek(), array("class"=>"form-control"));?>
                                </div>
                            </div>
                            <div class="col-xs-7">
                                <label  class="col-sm-4 control-label">Khung giờ:</label>
                                <div class="col-sm-8">
                                    <?php echo CHtml::dropDownList('Session[startHour][]', $session['startHour'][$key], $registration->timeFrames(), array("class"=>"form-control"));?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; 
                    else:?>
                    <p>Hãy chọn số buổi/tuần và đặt lịch học chi tiết!</p>
                    <?php endif;?>
                    <label class="hint">Lưu ý: Lịch học chi tiết trong tuần không được trùng cả ngày và khung giờ học</label>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 control-label">&nbsp;</label>
            <div class="col-sm-7 value mT15">
				<button class="btn btn-default prev-step w100 mR5" name="prevStep" type="button" onclick="backStep();">Quay lại</button>
				<button id="btnNextInStep2" class="btn btn-primary next-step w100" name="nextStep" type="button">Tiếp tục</button>                
            </div>
        </div>
    </form>
