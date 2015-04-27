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

    //View course as calendar
    public function actionCalendar($course)
	{
		$this->subPageTitle = 'Khóa học dạng lịch';
        $userID = Yii::app()->user->id;
        $course  = CourseStudent::model()->checkPermission($userID,$course);
        if($course ==false){
            $this->redirect(array('index'));
        }
        $this->render('calendar',array(
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
		$this->subPageTitle = 'Buổi học gần nhất';
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
		$this->subPageTitle = 'Buổi học đã hoàn thành';
    	$studentId = Yii::app()->user->id;
    	$endedSession = Session::model()->getEndedStudentSessions($studentId, 'student');
	    $this->render('endedSession', $endedSession);
    }

    //Ajax modify calendar
    public function  actionAjaxCalendarSession($id)
    {
        $userID = Yii::app()->user->id;
        $course = CourseStudent::model()->checkPermission($userID,$id);
        if($course==false){
            exit();
        }
        $session = $course->getSessions();
        $sessionDay = array();
        if($session){
            foreach($session as $item){
                if($item->content==null){
                    $item->content = " ";
                }
                $sessionDay[] = array(
                    'id' => $item->id,
                    'title' => $item->subject,
                    'content'=>$item->content,
                    'start' => $item->plan_start,
                    'end'=> date("Y-m-d H:i:s",strtotime($item->plan_start) + $item->plan_duration*60),
                    'allDay'=> false
                );
            }
        }
        echo json_encode($sessionDay);
    }
}

?>