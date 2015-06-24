<?php

class CourseRegistrationController extends Controller
{
    public  function init()
    {
        //$this->setStep("index");
    }
    //Select class & subject
    public function actionIndex()
    {
		$this->redirect('/student/courseRequest/index');
		$this->subPageTitle = 'Đăng ký khóa học: Chọn môn';
        $registration = new ClsRegistration();
        $registration->activeStep('index');
        $checkValid = true;//Validate course value step1
        if(isset($_POST['Course'])){
        	$checkValidStep1 = $registration->checkValidateStepOne($_POST['Course']);
        	$registration->setSession(array('course'=>$_POST['Course']));
        	if($checkValidStep1){
	        	$registration->activeStep('schedule');
	            $this->redirect(array("schedule"));
        	}else{
        		$checkValid = false;
        		$registration->activeStep('schedule', false);//InActivated step
        		$registration->activeStep('review', false);//InActivated step
            	$registration->activeStep('preferTeacher', false);//InActivated step
        	}
        }
        $this->render("index",array("registration"=>$registration, 'checkValid'=>$checkValid));
    }
    
	//Set schedules
    public function actionSchedule()
    {
		$this->subPageTitle = 'Đăng ký khóa học: Đặt lịch học';
        $registration = new ClsRegistration();
        //Check is activated step
        $isActivatedStep = $registration->isActivatedStep('schedule');
        if(!$isActivatedStep) $this->redirect(array('index'));
        $checkValid = true;//Validate schedule time
        //Check post & next step
        if(isset($_POST['Session'])){
        	$courseValues = $registration->getSession('course');
        	$sessionValues = $_POST['Session'];
        	$sessionValues['numberOfSession'] = $courseValues['numberOfSession'];
            $registration->setSession(array('session'=>$sessionValues));
            //Check validate session schedule time
            $checkValidTime = $registration->checkValidateStepTwo($sessionValues);
            if($checkValidTime){
            	$registration->activeStep('review');//Activated step
            	$schedules = ClsCourse::generateSchedules($sessionValues);
            	$registration->setSession(array('schedules'=>$schedules));
            	$this->redirect(array("review"));
            }else{
            	$registration->activeStep('review', false);//InActivated step
            	$registration->activeStep('preferTeacher', false);//InActivated step
            	$checkValid = false;//Validate schedule time
            }
        }
        $this->render("schedule", array('checkValid'=>$checkValid));

    }

    //Review course Registration
    public function actionReview()
    {
		$this->subPageTitle = 'Đăng ký khóa học: Xem lịch học';
        $registration = new ClsRegistration();
        //Check is activated step
        $isActivatedStep = $registration->isActivatedStep('review');
        if(!$isActivatedStep) $this->redirect(array('index'));
        //Check post & next step
        if(isset($_POST['nextStep'])){
        	$registration->activeStep('preferTeacher');//Activated step
            $this->redirect(array("preferTeacher"));
        }
        $this->render("review");
    }

    //Prefer Teachers
    public function actionPreferTeacher()
    {
		$this->subPageTitle = 'Đăng ký khóa học: Chọn giáo viên ưu tiên';
        $registration = new ClsRegistration();
        //Check is activated step
        $isActivatedStep = $registration->isActivatedStep('preferTeacher');
        if(!$isActivatedStep) $this->redirect(array('index'));
        
		$course = $registration->getSession('course');
		$availableTeachers = Teacher::model()->availableTeachers($course['subject_id']);
        //Check & next step
        if(isset($_POST['nextStep'])){
        	$registration->activeStep('preferTeacher');//Activated step
        	$preferTeachers = isset($_POST['preferTeachers'])? $_POST['preferTeachers']: array();
            $registration->setSession(array('preferTeachers'=>$preferTeachers));            
            $this->redirect(array("finish"));
        }
        $this->render("preferTeacher", array('availableTeachers'=>$availableTeachers));
    }
    
    //Complete register Course 
    public function actionFinish()
    {
        //Insert Courser;
		$this->subPageTitle = 'Đăng ký khóa học: Hoàn thành';
        $registration = new ClsRegistration();
        $registration->registerCourse();//Register course
        unset(Yii::app()->session['CourseRegistration']);
        $this->render("finish");
    }
    
    //Ajax session list
    public function actionAjaxSessionList()
    {
        $registration = new ClsRegistration();
        $sessionList = $registration->getSession("schedules");
        $sessionDay = array();
        if($sessionList)
        {
            foreach($sessionList as $key=>$item)
            {
                $sessionDay[] = array(
                    'id'=>$key,
                    'title' => $item['subject'],
                    'start' => $item['plan_start'],
                    'end'=> date("Y-m-d H:i:s",strtotime($item['plan_start']) + $item['plan_duration']*60),
                    'allDay'=> false,
                );
            }
        }

        echo json_encode($sessionDay);
    }
    //Ajax session edit
    public function actionAjaxSessionEdit()
    {
        $registration = new ClsRegistration();
        $schedules = $registration->getSession("schedules");
        $sessionKey = $_POST['id'];//Current edit session Id
        $schedules[$sessionKey]['subject']=$_POST['title'];
        $editTime = $_POST['start'];//Edit time of current session
        $checkValidate = $registration->validateCalendarSchedules($schedules, $sessionKey, $editTime);
        $success = false;
        if($checkValidate){
			$schedules[$sessionKey]['plan_start']= $editTime;
	        $registration->setSession(array('schedules'=>$schedules));
	        $success = true;
        }
        $this->renderJSON(array('success'=>$success, 'start'=>$schedules[$sessionKey]['plan_start']));
    }
    //Ajax session edit
    public function actionAjaxSessionEditDay()
    {
        $registration = new ClsRegistration();
        $schedules = $registration->getSession("schedules");
        $sessionKey = $_POST['id'];
        $editTime =  $_POST['start'];
        $checkValidate = $registration->validateCalendarSchedules($schedules, $sessionKey, $editTime);
        $success = false;
        if($checkValidate){
	        $schedules[$sessionKey]['plan_start'] = $editTime;
	        $registration->setSession(array('schedules'=>$schedules));
         	$success = true;
        }
        $this->renderJSON(array('success'=>$success,
            'start'=>$schedules[$sessionKey]['plan_start'],
            'end'=>date("Y-m-d H:i:s",strtotime($schedules[$sessionKey]['plan_start']) + $schedules[$sessionKey]['plan_duration']*60),
        ));
    }
    
	/**
	 * Ajax load subject by class id
	 */
	public function actionAjaxLoadSubject()
	{
		$classId = $_REQUEST['class_id'];
		$subjects = array();//Init subjects
		if($classId!=""){
			$subjects = Subject::model()->findAllByAttributes(array('class_id'=>$classId, 'allow_to_teach'=>1));
		}
		echo $this->renderPartial('widget/classSubject', array('subjects'=>$subjects));
	}
	
	/**
	 * Ajax load generate schedule suggestion
	 */
	public function actionAjaxSuggestSchedules()
	{
		$nPerWeek = $_REQUEST['nPerWeek'];
		$registration = new ClsRegistration();
		$suggestedDays = $registration->suggestSessionDayInWeek($nPerWeek);
		echo $this->renderPartial('widget/scheduleSuggestion', array('suggestedDays'=>$suggestedDays));
	}
	
	/**
	 * Ajax load suggestion title by subject Id
	 */
	public function actionAjaxLoadSuggestion()
	{
		$subjectId = $_REQUEST['subject_id'];
		$suggestions = SubjectSuggestion::model()->getSuggestionBySubject($subjectId);
		$this->renderJSON(array('success'=>true, 'suggestions'=>$suggestions));
	}
	
}
