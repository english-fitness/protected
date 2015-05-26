<?php
class ClsCourse
{
	/**
	 * Payment status for Preregister Course, Course & session
	 */
	public static function paymentStatuses()
	{
		return array(0=>'Chưa thanh toán', 1=>'Đã thanh toán', 2=>'Hoàn lại tiền');
	}
	
	/**
	 * Payment type for Preregister Course, Course & session
	 */
	public static function paymentTypes()
	{
		return array(0=>'Miễn phí', 1=>'Có tính phí');
	}
	
	/**
	 * Analytic & Create schedule sessions
	 */
	public static function generateSchedules($values)
	{
		$markStartDate = date('Y-m-d', strtotime($values['startDate'])-86400);//Previous start date
		$tmpSessionSchedules = array();//Init array session schedules
		$maxWeekIndex = ceil($values['numberOfSession']/count($values['dayOfWeek']))+1;//Max Week index
		$courseId = isset($values['course_id'])? $values['course_id']: "";//Course Id
		$teacherId = isset($values['teacher_id'])? $values['teacher_id']: "";//Teacher Id
		$weekMarkStartDate = $markStartDate;//Mark start date of week
		//Generate max temp sessions schedules		
		for($i=1; $i<=$maxWeekIndex; $i++){
			foreach($values['dayOfWeek'] as $key=>$day){
				$nextDay = date('Y-m-d', strtotime('next '.$day, strtotime($markStartDate)));
				if($nextDay>$weekMarkStartDate) $weekMarkStartDate = $nextDay;
				$planStart = $nextDay.' '.$values['startHour'][$key].':'.$values['startMin'][$key].':00';
				$tmpSessionSchedules[$planStart] = array(
					'course_id'=>$courseId,
					'teacher_id'=>$teacherId,
					'plan_start'=>date('Y-m-d H:i:s', strtotime($planStart)),
					'status'=>Session::STATUS_PENDING,
                    'plan_duration'=>Session::DEFAULT_DURATION,
				);
				//Set plan duration if set
				if(isset($values['plan_duration'])){
					$tmpSessionSchedules[$planStart]['plan_duration'] = $values['plan_duration'];
				}
			}
			$markStartDate = $weekMarkStartDate;//Set mark start date
		}
		ksort($tmpSessionSchedules);//Sort session schedule by key
		$generateSessionSchedules = array();//Last generate session schedule
		$sessionIndex = 1;//Reset subject
		foreach($tmpSessionSchedules as $key=>$session){
			if($key>=date('Y-m-d H:i:s') && $sessionIndex<=$values['numberOfSession']){
				$session['subject'] = 'Session '.$sessionIndex;
				$generateSessionSchedules[$sessionIndex] = $session;
				$sessionIndex++;
			}elseif($sessionIndex>$values['numberOfSession']){
				break;
			}
		}
		return $generateSessionSchedules;
	}
	
	/**
     * Get all courses of Student
     */
    public function getAllCoursesOfStudent($studentId)
    {
        $criteria = new CDbCriteria;
        $criteria->select = 't.*';
        $criteria->join = "INNER JOIN tbl_course_student ON t.id= tbl_course_student.course_id";
        $criteria->condition = "(student_id = $studentId) AND (deleted_flag=0)";
        $criteria->order = 'created_date DESC';
        $studentCourses = Course::model()->findAll($criteria);
        return $studentCourses;
    }
    
    /**
     * Create course from preset course
     */
    public function createActualCourseFromPreset($presetId)
    {
    	$presetCourse = PresetCourse::model()->findByPk($presetId);
    	$registration = new ClsRegistration();//Registration class
    	if(isset($presetCourse->id)){
			$course = new Course();
			//Set course Attributes
			$course->attributes = array(
				'subject_id' => $presetCourse->subject_id,
				'type' => Course::TYPE_COURSE_PRESET,
				'title' => $presetCourse->title,
				'total_of_student' => $presetCourse->max_student,
				'payment_type' => 1,//Co tinh phi
				'payment_status' => 0,//Chua thanh toan
				'teacher_id' => $presetCourse->teacher_id,
				'created_user_id' => Yii::app()->user->id,
				'status' => Course::STATUS_PENDING,
			);
			//Set session Attributes
			$sessionSchedules = $presetCourse->getSuggestSchedules();//Suggest schedules
			$sessionValues = array(
				'numberOfSession' => $presetCourse->total_of_session,
				'startDate' => $presetCourse->start_date,//Start date
				'dayOfWeek' => $sessionSchedules['dayOfWeek'],//Day of week
				'startHour' => $sessionSchedules['startHour'],//Start hour
				'startMin' => $sessionSchedules['startMin'],//Start min
				'teacher_id' => $presetCourse->teacher_id,
				'plan_duration' => Session::DEFAULT_PRESET_DURATION,
			);
			$checkValidTime = $registration->validateGenerateSession($sessionValues);
			if(isset($sessionValues['dayOfWeek']) && $checkValidTime){
				if($course->save()){
					$sessionValues['course_id'] = $course->id;//Set session course id				
					Session::model()->saveSessionSchedules($sessionValues);//Save sessions
					//Assign students to Course & Sessions
					$assignedStudentIds = $presetCourse->getAssignedStudents();//Get student from student
					if(count($assignedStudentIds)>0){
						$course->assignStudentsToCourseSession($assignedStudentIds);
					}
					//Assign teacher to sessions of Course
					if($course->teacher_id>0){
						$course->assignTeacherToCourseSession();//Assign teacher to course
					}
					$course->resetStatusSessions();//Reset status of sessions
					//Save actual course id to preregister course
					$presetCourse->updatePreCoursesOfPreset($course->id);//Update preregister course of preset
					//Update assign actual course to preset course
					$presetCourse->course_id = $course->id;//Course id
					$presetCourse->save();//Update preset course
					return $course;//Return course
				}
			}else{
				return false;
			}
		}
		return false;
    }
 
   	/**
	 * Generate price rule values for preset course
	 */
    public function generatePriceRules($priceRules=NULL)
    {
    	$stepPriceRules = array(
    		'step1'=> array('from_date'=>'', 'to_date'=>'', 'bank_price'=>'', 'mobicard_price'=>'', 'description'=>''),
    		'step2'=> array('from_date'=>'', 'to_date'=>'', 'bank_price'=>'', 'mobicard_price'=>'', 'description'=>''),
    		'step3'=> array('from_date'=>'', 'to_date'=>'', 'bank_price'=>'', 'mobicard_price'=>'', 'description'=>''),
    	);
    	//Update price rule from price rules
    	if(is_array($priceRules) && count($priceRules)>0){
    		foreach($stepPriceRules as $step=>$rules){
    			if(isset($priceRules[$step])){
    				foreach($rules as $key=>$value){
    					if(isset($priceRules[$step][$key]) && trim($priceRules[$step][$key])!=""){
    						$stepPriceRules[$step][$key] = $priceRules[$step][$key];//Update price rule key
    					}
    				}
    			}
    		}
    	}
    	return $stepPriceRules;
    }
    
    /**
     * Check & calculate final price from preset course
     */
    public function calculateStepFinalPrice($priceRules=NULL)
    {
    	if(is_array($priceRules) && count($priceRules)>0){
	    	$stepPriceRules = $this->generatePriceRules($priceRules);
	    	$currDate = date('Y-m-d');//Current date
	    	$stepPrices = false;//Step result price
	    	foreach($stepPriceRules as $step=>$rules){
	    		if(Common::validateDateFormat($rules['from_date']) && $rules['from_date']<=$currDate
	    		 && Common::validateDateFormat($rules['to_date']) && $rules['to_date']>=$currDate)
	    		{
	    			$stepPrices = $rules;
	    		}
	    	}
	    	return $stepPrices;
    	}
    	return false;
    }
}
?>