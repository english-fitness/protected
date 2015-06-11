<?php
class CourseRequestController extends Controller{

    public $titlePage = "Đăng ký khóa học theo yêu cầu";

    //Select class & subject
    public function actionIndex()
    {
        $this->subPageTitle = $this->titlePage.': Chọn môn';
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
            }
        }
        $this->render("index",array("titlePage"=>$this->titlePage,
            "registration"=>$registration,
            'checkValid'=>$checkValid,
        ));
    }

    //Set schedules
    public function actionSchedule()
    {

        $this->subPageTitle = $this->titlePage.': Đặt lịch học';
        $registration = new ClsRegistration();
        //Check is activated step
        $isActivatedStep = $registration->isActivatedStep('schedule');
        if(!$isActivatedStep) $this->redirect(array('index'));
        $checkValid = true;//Validate schedule time

        //Check post & next step
        if(isset($_POST['Session']) && isset($_POST['Course'])){
            $sessionValues = $_POST['Session'];
            $registration->setSession(array('session'=>$sessionValues));//Register session values
            //Add extra course values to session
            $courseSession = $registration->getSession("course");
            $courseValues = array_merge($courseSession, $_POST['Course']);//Merge course session
            $registration->setSession(array('course'=>$courseValues));//Reset register course session
            //Check validate session schedule time
            $checkValidTime = $registration->checkValidateStepTwo(array_merge($courseValues, $sessionValues));
            if(isset($courseValues['numberOfSession']) && $checkValidTime){
                $registration->activeStep('review');//Activated step
                $this->redirect(array("review"));
            }else{
                $registration->activeStep('review', false);//InActivated step
                $checkValid = false;//Validate schedule time
            }
        }
        $totalOptions = $registration->totalSessionOptions();
        $studentId = Yii::app()->user->id;
        $user = User::model()->findByPk($studentId);
        $existedTrialCourse = Student::model()->checkExistedTrialCourse($studentId);
        $hasTrial = true;//Has trial course in price table
        if(($existedTrialCourse || $user->status>=User::STATUS_TRAINING_SESSION)
            && isset($totalOptions[PreregisterCourse::TOTAL_TRAINING_SESSION]))
        {
            unset($totalOptions[PreregisterCourse::TOTAL_TRAINING_SESSION]);//Unset option trial course
            $hasTrial = false;//Not display trial course
        }
        $this->render("schedule", array(
            "titlePage"=>$this->titlePage,
            'checkValid'=>$checkValid,
            'totalOptions'=>$totalOptions,
            'hasTrial'=>$hasTrial,
            'user'=>$user,
        ));
    }

    //Complete register Course
    public function actionReview()
    {
        $this->subPageTitle = $this->titlePage.': Xem lại';
        $registration = new ClsRegistration();
        //Check is activated step
        $isActivatedStep = $registration->isActivatedStep('review');
        if(!$isActivatedStep) $this->redirect(array('index'));
        //Check post & next step
        //Review render params
        $reviewRenderParams = array(
            "titlePage"=>$this->subPageTitle,//Title page
            "registration"=>$registration,//Registration class,
            "cartCodeError"=>null
        );
        $courseSession = $registration->getSession("course");
        if(isset($_POST['nextStep'])){
            $this->redirect(array("finish"));
        }
        //Check & calculate price values

        $user = Yii::app()->user->getData();
        //Only calculate normal course with more than 3 sessions
        if(isset($courseSession['numberOfSession'])){
            $package = CoursePackage::model()->findByPk($courseSession['numberOfSession']);
            /**
             * var $mobicardFinalPrice CoursePackageOptions;
             */
            $packageOptions = $package->getOption(Yii::app()->user->data->getStatusNewOrOld(),$courseSession['total_of_student']);
            $mobicardFinalPrice = $packageOptions->getOptionMeta('mobicard_final_price');
            $courseSession['created_date'] = date('Y-m-d H:i:s');//Current date time
            $courseSession['total_of_session'] = $courseSession['numberOfSession'];
            //Add total price to course session values
            $courseSession['final_price'] = isset($packageOptions->sales)?$packageOptions->sales:0;
            $courseSession['mobicard_final_price'] = $mobicardFinalPrice;
            $registration->setSession(array('course'=>$courseSession));//Re set session of course
            $reviewRenderParams['priceValues'] =$courseSession;//Add price value to render
            $reviewRenderParams['package'] = $package;
            $reviewRenderParams['option'] = $packageOptions;
        }
        //Render review html file
        $this->render("review", $reviewRenderParams);
    }

    //Complete register Course
    public function actionFinish()
    {
        //Insert Courser;
        $this->subPageTitle = $this->titlePage.': Hoàn thành';
        $registration = new ClsRegistration();//Registration class
        $preCourse = $registration->saveCourseRequest();
        if($preCourse!=NULL){
            //Update status for student when they registered course
            $user = Yii::app()->user->getData();
            if($user->status<User::STATUS_REGISTERED_COURSE){
                $user->status = User::STATUS_REGISTERED_COURSE;
                $user->save();//Update status
            }
            unset(Yii::app()->session['CourseRegistration']);
            $this->render("finish", array(
                "titlePage"=>$this->titlePage,
                "preCourse"=>$preCourse,
            ));
            Settings::shareFacebook(Settings::SHARE_COURSE_REQUEST,$preCourse);
        }else{
            $this->redirect(array("/student/courseRequest/list"));
        }
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

    /*
     * action list request open class */
    public function actionList()
    {
        $this->subPageTitle = 'Danh sách khóa học đã đăng ký';
        $uid = Yii::app()->user->id;
        $titlePage = "Danh sách khóa học đã đăng ký";
        $preregisterCourse = PreregisterCourse::model()->getPreCoursesForStudent($uid);
        $this->render("list",array("preregisterCourse"=>$preregisterCourse,"titlePage"=>$titlePage));
    }

    /*
     * action view request open class by id */
    public function actionView($id)
    {
        $this->subPageTitle = 'Chi tiết khóa học đã đăng ký';
        $uid = Yii::app()->user->id;
        $user = User::model()->findByPk($uid);
        $attributes = array('condition'=>"deleted_flag=0 and student_id=$uid and id = $id");
        $preregisterCourse = PreregisterCourse::model()->find($attributes);
        if(!isset($preregisterCourse->id)) $this->redirect(array("list"));
        $calendar = json_decode($preregisterCourse->session_per_week);
        $viewRenderParams = array(
            "calendar" => $calendar,
            "preregisterCourse" => $preregisterCourse,
            "errorMessage" => "",//Error message
        );
        $this->render("view", $viewRenderParams);
    }

    /*action remove*/
    public function actionDelete($id)
    {
        if(!$id) $this->redirect(array("list"));
        $uid = Yii::app()->user->id;
        $condition = array('condition'=>"student_id=$uid and id = $id");
        $preregisterCourse = PreregisterCourse::model()->find($condition);
        if(!$preregisterCourse) $this->redirect(array("list"));
        $preregisterCourse->deleted_flag = 1;//Deleted flag = 1
        $preregisterCourse->save();
        $this->redirect(array("list"));
    }

    /**
     * Ajax load generate price table to select
     */
    public function actionAjaxGetPriceTable()
    {
        $registration = new ClsRegistration();
        $totalOptions = $registration->totalSessionOptions();
        $nStudent = $_REQUEST['totalOfStudent'];
        $studentId = Yii::app()->user->id;
        $user = Yii::app()->user->getData();
        $existedTrialCourse = Student::model()->checkExistedTrialCourse($studentId);
        $hasTrial = true;//Has trial course in price table
        if(($existedTrialCourse || $user->status>=User::STATUS_TRAINING_SESSION)
            && isset($totalOptions[PreregisterCourse::TOTAL_TRAINING_SESSION]))
        {
            unset($totalOptions[PreregisterCourse::TOTAL_TRAINING_SESSION]);//Unset option trial course
            $hasTrial = false;//Not display trial course
        }
        echo $this->renderPartial('widget/priceTable', array(
            'totalOfStudent'=>$nStudent,
            'hasTrial'=>$hasTrial,
            'user'=>$user,
        ));
    }

}

?>