<?php

class ClassController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        );
    }
    
    public function actionIndex()
    {
		$this->subPageTitle = 'Danh sách khóa học';
        $ClsCourse = new ClsCourse();
        $studentId = Yii::app()->user->id;
        $studentCourses = $ClsCourse->getAllCoursesOfStudent($studentId);
        $this->render('index',array("studentCourses"=>$studentCourses));
    }

    //Course information & list session
    public function actionCourse($id)
    {
		$this->subPageTitle = 'Thông tin khóa học';
        $userID = Yii::app()->user->id;
        $course  = CourseStudent::model()->checkPermission($userID,$id);
        if($course ==false){
            $this->redirect(array('index'));
        }
        $sessions = $course->getSessions();
        $this->render('course',array(
            "sessions"=>$sessions,
            "course"=>$course,
        ));
    }

    //View course detail
    public function actionCourseProfile($id)
    {
        $this->subPageTitle = 'Thông tin khóa học';
        $userID = Yii::app()->user->id;
        $course = CourseStudent::model()->checkPermission($userID,$id);
        if($course==false){
            $this->redirect(array('index'));
        }
        $this->render('courseProfile',array(
            "course"=>$course,
        ));
    }
    
    //View session detail
    public function actionSession($id)
    {
		$this->subPageTitle = 'Thông tin buổi học';
        $userID = Yii::app()->user->id;
        $session = SessionStudent::model()->checkPermission($userID,$id);
        if($session==false){
            $this->redirect(array('index'));
        }
        $this->render('session',array(
            "session"=>$session,
        ));
    }
    //Display nearest session of Student
    public function actionNearestSession()
    {
		$this->subPageTitle = Yii::t('lang','nearest_session');
    	$studentId = Yii::app()->user->id;
        $ClsSession = new ClsSession();
        $nearestSessions = $ClsSession->getNearestSessions($studentId, 'student', 8);
        $this->render("nearestSession",array(
        	"nearestSessions"=>$nearestSessions,
        ));
    }
    
	//Display ended session of Student
    public function actionEndedSession()
    {
		$this->subPageTitle = Yii::t('lang','ended_session');
    	$studentId = Yii::app()->user->id;
    	$endedSession = Session::model()->getEndedStudentSessions($studentId, 'student');
	    $this->render('endedSession', $endedSession);
    }

    //set languages
    public function actionLanguage()
    {
        
        if(isset($_POST['lang'])||isset($_GET['lang'])){
            $userID = Yii::app()->user->id;
            $languages = User::model()->findByPk($userID);
            if(isset($_POST['lang'])){
                $lang=$_POST['lang'];
                if(isset($lang['vi'])){
                     Yii::app()->language=$languages->language = 'vi';
                    $languages->save();
                }
                if(isset($lang['en'])){
                     Yii::app()->language=$languages->language = 'en';
                    $languages->save();
                }
                if($lang=='en'||$lang=='vi'){
                    Yii::app()->language=$languages->language = $lang;
                    $languages->save();
                }
            }else if(isset($_GET['lang'])){
                Yii::app()->language=$languages->language = $_GET['lang'];
                $languages->save();
            }
            $ClsSession = new ClsSession();
            $nearestSessions = $ClsSession->getNearestSessions($userID, 'student', 8);
            $this->render("nearestSession",array(
                    "nearestSessions"=>$nearestSessions,
            ));
        }
       
    }
}

?>