<?php
class ClsRegistration
{
    public function init()
    {
    	$data = Yii::app()->session['CourseRegistration'];
    	if(!isset($data)){
	        Yii::app()->session['CourseRegistration'] = array();
    	}
    }
    
    /**
     * Set Registration session key, values
     */
    public function setSession($sessions = array()){
    	$data = Yii::app()->session['CourseRegistration'];
    	if(is_array($sessions) && count($sessions)>0){
    		foreach($sessions as $key=>$value){
    			$data[$key] = $value;
    		}
    	}
    	Yii::app()->session['CourseRegistration'] = $data;
    }
    
    /**
     * Get Registration session
     */
    public function getSession($key){
    	$data = Yii::app()->session['CourseRegistration'];
    	if(isset($data[$key])){
    		return $data[$key];
    	}
    	return NULL;
    }
    
	/**
     * Active course registration step
     */
    public function activeStep($step, $bool=true)
    {
    	$data = Yii::app()->session['CourseRegistration'];
    	$data['activatedSteps'][$step] = $bool;
    	Yii::app()->session['CourseRegistration'] = $data;
    }
    
	/**
     * Active course registration step
     */
    public function isActivatedStep($step)
    {
    	$data = Yii::app()->session['CourseRegistration'];
    	if(isset($data['activatedSteps'][$step]) 
    		&& $data['activatedSteps'][$step])
    	{
    		return true;
    	}
    	return false;
    }
    
    /**
     * Check validate register course by step: step 1
     */
    public function checkValidateStepOne($values)
    {
    	$numberFields = array('class_id', 'subject_id', 'numberOfSession');
    	foreach($numberFields as $field){
    		if(isset($values[$field]) && !is_numeric($values[$field])){
    			return false;	
    		}
    	}
    	if(trim($values['title'])=='') return false;
    	return true;
    }
    
	/**
     * Check validate register course by step: step 2
     */
    public function checkValidateStepTwo($values)
    {
    	$startDate = $values['startDate'];//Start date
    	$numberFields = array('numberOfSession', 'total_of_student', 'numberSessionPerWeek');//Number fields
    	if(!($startDate>=date('Y-m-d') && $startDate<='2100-01-01')){
    		return false;
    	}
    	foreach($numberFields as $field){
    		if(isset($values[$field]) && !is_numeric($values[$field])){
    			return false;	
    		}
    	}
    	return $this->validateGenerateSession($values);
    }
    
    /**
     * Check validate session schedule times
     */
    public function validateGenerateSession($values)
    {
    	$tmpSchedules = array();//Init tmp schedult
    	foreach($values['dayOfWeek'] as $key=>$day){
    		$tmpSchedules[] = $day.'-'.$values['startHour'][$key];
    	}
    	$uniqueSchedules = array_unique($tmpSchedules);
    	if(count($uniqueSchedules)<count($tmpSchedules)){
    		return false;
    	}
    	if(isset($values['startDate'])){
	    	if(!($values['startDate']>=date('Y-m-d') && $values['startDate']<='2100-01-01')){
	    		return false;
	    	}
    	}
    	return true;
    }
    
    /**
     * Display days of Week
     * @return array days of week
     */    
    public function daysOfWeek()
    {
    	return array(
    		'Monday'=>'Thứ hai',
    		'Tuesday'=>'Thứ ba',
    		'Wednesday'=>'Thứ tư',
    		'Thursday'=>'Thứ năm',
    		'Friday'=>'Thứ sáu',
    		'Saturday'=>'Thứ bảy',
    		'Sunday'=>'Chủ nhật'
    	);
    }
    
	/**
     * Display days of Week
     */    
    public function numberSessionsPerWeek($limit=3)
    {
    	$sessionsPerWeek = array(""=>"Chọn...");
    	for($i=1; $i<=$limit; $i++){
    		$sessionsPerWeek[$i] = $i;
    	}
    	return $sessionsPerWeek;
    }
    
	/**
     * Display hours in day
     */    
    public function hoursInDay()
    {
    	$hoursInDay = array();
    	for($h=0; $h<=23; $h++){
    		$hour = ($h<10)? '0'.$h: $h;
    		$hoursInDay[$hour] = $hour;
    	}
    	return $hoursInDay;
    }
    
	/**
     * Display minutes in hour
     */    
    public function minutesInHour()
    {
    	$minutesInHour = array();
    	for($m=0; $m<=11; $m++){
    		$min = ($m>0)?$m*5: '00';
    		$minutesInHour[$min] = $min;
    	}
    	return $minutesInHour;
    }
    
	/**
     * Total sessions in course options
     */    
    public function totalSessionOptions($hasTrial=true)
    {
    	$totalTrainingSession = PreregisterCourse::TOTAL_TRAINING_SESSION;
    	$options = array(
            $totalTrainingSession => 'Học thử ('.$totalTrainingSession.' buổi)',
            10 => '10 buổi',
    		20 => '20 buổi',
    		30 => '30 buổi',
    		50 => '50 buổi',
    	);
    	if(!$hasTrial) unset($options[$totalTrainingSession]);
    	return $options;
    }
    
	/**
	 * Preset total session option
	 */
	public function totalSessionPresetOptions()
	{
		return array(
    		4 => '4 buổi',
    		10 => '10 buổi',
    		20 => '20 buổi',
    	);
	}

    /**
     * Time frame
     */
    public function timeFrames($duration=90){
    	$startHours = array('09:00', '14:00', '16:00', '19:00', '21:00');
    	$timeFrames = array();//Time frame
    	foreach($startHours as $startHour){
    		$endHour = date('H:i', strtotime(date('Y-m-d').' '.$startHour)+$duration*60);
    		$keyFrame = $startHour.' - '.$endHour;
    		$timeFrames[$keyFrame] = $keyFrame;
    	}
        return $timeFrames;
    }
    
	/**
     * Class type/Total of Student
     */
    public function totalStudentOptions($limit=6, $shortcut=false, $nstudent=null){
    	$totalStudentOptions = array();
    	for($i=1; $i<=$limit; $i++){
    		$value = "Lớp 1-$i (1 giáo viên, $i học sinh)";
    		if($shortcut) $value = "1-$i";//Display shortcut title
    		$totalStudentOptions[$i] = $value;
    	}
    	if($nstudent && !isset($totalStudentOptions[$nstudent])){//Add nstudent to options
    		$totalStudentOptions[$nstudent] = "Lớp 1-$nstudent (1 giáo viên, $nstudent học sinh)";
    	}
        return $totalStudentOptions;
    }
    
	/**
     * Class type/Total of Student as group
     */
    public function totalStudentAsGroupOptions(){
    	$model = new CoursePackageOptions();
        return $model->getClassNumbers();
    }
    
	/**
     * Class type/Total for preset course
     */
    public function totalStudentPresetOptions(){
    	return array(
    		5 => "Lớp 1-5 (1 giáo viên, 4-6 học sinh)",
    		10 => "Lớp 1-10 (1 giáo viên, 8-12 học sinh)",
    		20 => "Lớp 1-20 (1 giáo viên, 15-25 học sinh)",
    	);
    }
    
    /**
     * Generate suggest day & hour in register course
     */
    public function suggestSessionDayInWeek($nPerWeek=1)
    {
    	if($nPerWeek==1) return array('Monday');
    	if($nPerWeek==2) return array('Monday', 'Wednesday');
    	if($nPerWeek==3) return array('Monday', 'Wednesday', 'Friday');
    	if($nPerWeek==4) return array('Monday', 'Tuesday', 'Thursday', 'Friday');
    	if($nPerWeek==5) return array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday');
    	if($nPerWeek==6) return array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
    	if($nPerWeek==7) return array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    	return array();
    }
    
	/**
     * Check validate modify(drag/drop & edit) datetime in calendar
     * Validate when register course & edit sessions of Course
     */
    public function validateCalendarSchedules($schedules, $currId, $editTime)
    {
    	$arrKeys = array_keys($schedules);//Array keys of schedules
    	$editCurrKey = array_search($currId, $arrKeys);
    	if($schedules[$currId]['plan_start']<=date('Y-m-d H:i:s')){
    		return false;
    	}else{
    		$totalSession = count($schedules);//Total session
    		$prevSessionTime = date('Y-m-d H:i:s');//Init prev session time
    		if($editCurrKey>0){
    			$prevKey = $arrKeys[$editCurrKey-1];
    			$prevSessionEndTime = strtotime($schedules[$prevKey]['plan_start']) + $schedules[$prevKey]['plan_duration']*60;
    			$tmpPrevSessionTime = date('Y-m-d H:i:s', $prevSessionEndTime);
    			if($tmpPrevSessionTime>$prevSessionTime){//If prev time > current time
    				$prevSessionTime = $tmpPrevSessionTime;
    			}
    		}
    		$nextSessionTime = ($editCurrKey<($totalSession-1))? $schedules[$arrKeys[$editCurrKey+1]]['plan_start']: '2100-01-01 12:00:00';
    		if($editTime>=$prevSessionTime && $editTime<=$nextSessionTime){
    			return true;
    		}
    		return false;
    	}
    }

    /**
     * Register course for student when they complete all steps
     */
    public function registerCourse()
    {
    	$modelCourse = new Course;
    	$courseValues = $this->getSession('course');
    	$studentId = Yii::app()->user->getId();    	
    	$preferTeacherValues = $this->getSession('preferTeachers');
    	$modelCourse->attributes = array(
    		'created_user_id' => $studentId,
    		'subject_id' => $courseValues['subject_id'],
    		'title' => $courseValues['title'],
    		'content' => $courseValues['content'],
    		'total_of_student' => $courseValues['total_of_student'],
    		'status' => Course::STATUS_PENDING,
    	);
    	$sessionSchedules = $this->getSession('schedules');
    	if($modelCourse->save()){
    		//Register sessions for Course
    		$this->registerSessionSchedules($modelCourse->id, $sessionSchedules);
    		//Assign students to Course & Sessions					
			$modelCourse->assignStudentsToCourseSession(array($studentId));
			//Save couse prefer teachers
			$this->registerPreferTeachers($modelCourse->id, $preferTeacherValues);
    	}
    }

    /* course request */
    public function saveCourseRequest() {

        $preregisterCourse = new PreregisterCourse();
        $courseData = $this->getPreregisterCourseValues();
        if($courseData==NULL) return NULL;
        //Get total of session value
        $attributes =  array(
            'note' => $courseData['content'],
            'total_of_session'=> $courseData['numberOfSession'],
            'start_date' =>$courseData['startDate'],
            'student_id' => Yii::app()->user->id,
            'subject_id' => $courseData['subject_id'],
            'title' => $courseData['title'],
            'total_of_student' => $courseData['total_of_student'],
            'session_per_week' => $courseData['calendar'],
            'status' => PreregisterCourse::STATUS_PENDING,
    		'course_type' => Course::TYPE_COURSE_NORMAL,
    		'payment_type' => PreregisterCourse::PAYMENT_TYPE_NOT_FREE,
    		'payment_status' => PreregisterCourse::PAYMENT_STATUS_PENDING,
    		'created_user_id' => Yii::app()->user->id,
            'final_price'=>$courseData['final_price'],
            'mobicard_final_price'=>$courseData['mobicard_final_price']
        );

        //Save payment note with saleoff price
    	if(isset($courseData['priceValues'])){
        	$attributes['payment_note'] = $this->renderPaymentNote($courseData['priceValues']);
        }
        $preregisterCourse->attributes = $attributes;
        $preregisterCourse->save();
        return $preregisterCourse;
    }

     /**
      * Get preregister data course values
      */
    public function getPreregisterCourseValues() {
        $session = $this->getSession("session");
        $courseValues = NULL;
        if(!$session) return $courseValues;
        $courseValues  = $this->getSession("course");
        $courseValues['startDate'] = $session['startDate'];
        foreach($session['dayOfWeek'] as $key=>$item) {
            $courseValues['calendar'][$item] = $session["startHour"][$key];
        }
        $courseValues['calendar'] = json_encode($courseValues['calendar']);
        return $courseValues;
    }
    /**
	 * Register schedule sessions to db
	 */
	public function registerSessionSchedules($courseId, $sessionSchedules) {
		$sessionIndex = 1;//Init session index subject
		foreach($sessionSchedules as $key=>$sessionValues){
			$session = new Session;
			$session->attributes = $sessionValues;
			$session->course_id = $courseId;
			$session->status = Session::STATUS_PENDING;
			$session->save();
			$sessionIndex++;				
		}
	}
	
	/**
	 * Register prefer Teachers to  db
	 */
	public function registerPreferTeachers($courseId, $preferTeacherValues){
		//Save couse prefer teachers
		if(count($preferTeacherValues)>0){
			foreach($preferTeacherValues as $key=>$teacherId){
				if($key<=4){
					$modelPreferTeacher = new CoursePreferredTeacher();
					$modelPreferTeacher->course_id = $courseId;
					$modelPreferTeacher->teacher_id = $teacherId;
					$modelPreferTeacher->priority = $key+1;
					$modelPreferTeacher->save();
				}
			}
		}
	}
	
	/**
	 * Create preregister course from preset course
	 */
	public function createPreCourseFromPresetCourse($presetCourse)
	{
		$preregisterCourse = new PreregisterCourse();
		$preregisterCourse->attributes = array(
			'student_id' => Yii::app()->user->id,
			'subject_id' => $presetCourse->subject_id,
			'title' => $presetCourse->title,
			'total_of_student' => $presetCourse->max_student,
			'start_date' => $presetCourse->start_date,
			'total_of_session' => $presetCourse->total_of_session,
			'session_per_week' => $presetCourse->session_per_week,
			'status' => PreregisterCourse::STATUS_PENDING,
			'teacher_id' => $presetCourse->teacher_id,
			'preset_course_id' => $presetCourse->id,
			'course_type' => Course::TYPE_COURSE_PRESET,
			'final_price' => $presetCourse->final_price_per_student(),
			'payment_type' => PreregisterCourse::PAYMENT_TYPE_NOT_FREE,
			'payment_status' => PreregisterCourse::PAYMENT_STATUS_PENDING,
			'created_user_id' => Yii::app()->user->id,
		);
		$preregisterCourse->save();//Save preregister course
		return $preregisterCourse;//Return preregister course
	}
	
	/**
	 * Convert session perweek schedule to json str
	 */
	public function convertSessionSchedules($dayOfWeeks, $timeFrames)
	{
		$sessionPerWeeks = array();
		foreach($dayOfWeeks as $key=>$day){
			$sessionPerWeeks[$day] = $timeFrames[$key];
		}
		return json_encode($sessionPerWeeks);
	}
	
	/**
	 * Generate price table by calculate price function
	 */
	public function generatePriceTable($totalOfStudent=1, $hasTrial=false, $user=null)
	{
		$priceCalculator = new PriceCalculator($totalOfStudent);
		$totalSessionOptions = $this->totalSessionOptions($hasTrial);
		$priceTableValues = array();//Init price table value
		$stepLabels = array('Học phí toàn khóa học'); $index = 1;
		foreach($totalSessionOptions as $nSession=>$label){
			$params = array('total_of_student'=>$totalOfStudent, 'total_of_session'=>$nSession);
			$priceVar = $priceCalculator->calculate($params, $user);//Price values
			$priceTableValues[$nSession][] = $priceVar['base_price']*$nSession;
			if(isset($priceVar['steps'])){
				foreach($priceVar['steps'] as $key=>$value){
					if($index==1) $stepLabels[] = $value['description'];
					$priceTableValues[$nSession][] = $value['next_price']*$nSession;
				}
			}
			$index++;
		}
		return array('stepLabels'=>$stepLabels, 'stepPrices'=>$priceTableValues);
	}
    
	/**
	 * Render payment note from price Values of Course session
	 */
	public static function renderPaymentNote($priceValues)
	{
		 $baseTotalPriceStr = number_format($priceValues['base_price']*$priceValues['total_of_session']);
		 $basePrice = number_format($priceValues['base_price']);//Base Price
		 $paymentNote = "<p>Học phí toàn khóa học: <b>".$baseTotalPriceStr."</b> (học phí/1 buổi: <b>".$basePrice."</b>)</p>";
		 $paymentNote .= "<p>Thông tin về mức học phí khuyến mãi và các điều kiện được hưởng (nếu có):</p>";
    	 if(isset($priceValues['steps'])){
    		 foreach($priceValues['steps'] as $key=>$value){
    		 	$nextTotalPriceStr = number_format($value['next_price']*$priceValues['total_of_session']);
    		 	$paymentNote .= "<p>Học phí khuyến mãi: <b>".$nextTotalPriceStr."</b> (Ghi chú: ".$value['description'].")<p>";
    		 }
    	 }
    	 return $paymentNote;
	}
}
?>