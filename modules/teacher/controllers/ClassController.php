<?php

class ClassController extends Controller
{
    public function actionIndex()
    {
		$this->subPageTitle = 'Courses List';
        $uid = yii::app()->user->id;
        $teacherCourses = Course::model()->findAll(array("condition"=>"teacher_id = ".$uid." AND deleted_flag=0 AND type=".Course::TYPE_COURSE_NORMAL));
        $this->render('index',array(
            "teacherCourses"=>$teacherCourses
            )
        );
    }
    
    //Display courses of Teacher
    public function actionCourse($id)
    {
		$this->subPageTitle = 'Courses Information';
        $uid = yii::app()->user->id;
        $course  = Course::model()->findByPk($id);
        if($course->teacher_id!=$uid)
        {
            $this->redirect(array('index'));
        }
        $sessions = $course->getSessions(null, $uid);//Get all session of course
        $this->render('course',array(
                "sessions"=>$sessions,
                "course"=>$course,
            )
        );
    }
    
	//Display nearest session of Student
    public function actionNearestSession()
    {
		$this->subPageTitle = 'Schedule';
    	$teacherId = Yii::app()->user->id;
        $ClsSession = new ClsSession();
        $nearestSessions = $ClsSession->getNearestSessions($teacherId, 'teacher', 8);
        $this->render("nearestSession",array("nearestSessions"=>$nearestSessions));
    }
    
	//Display attending session of Teacher
    public function actionAttendingSession()
    {
		$this->subPageTitle = 'Buổi dự giờ gần nhất';
    	$teacherId = Yii::app()->user->id;
        $ClsSession = new ClsSession();
        $nearestSessions = $ClsSession->getNearestSessions($teacherId, 'student', 8);
        $this->render("attendingSession",array("nearestSessions"=>$nearestSessions));
    }
    
	//Display ended session of Teacher
    public function actionEndedSession()
    {
		$this->subPageTitle = 'Completed sesion';
    	$teacherId = Yii::app()->user->id;
    	$endedSession = Session::model()->getEndedStudentSessions($teacherId, 'teacher');
	    $this->render('endedSession', $endedSession);
    }
    
    //Course profile
	public function actionCourseProfile($id)
    {
        $this->subPageTitle = 'Course Information';
        $userID = Yii::app()->user->id;
        $course  = Course::model()->findByPk($id);
    	if($course->teacher_id!=$userID){
            $this->redirect(array('index'));
        }
        $this->render('courseProfile',array(
            "course"=>$course,
        ));
    }
    
    //Ajax edit session by Id
    public function actionAjaxEditSessionById($id)
    {
        $success = false;
        $notice="";
        if(isset($_POST['title']))
        {
            $session = Session::model()->findByPk($id);
            $session->subject = $_POST['title'];
            $session->content = $_POST['content'];
            $session->save();
            $success  = true;
            $notice = $_POST;
        }
        $this->renderJSON(array("success"=>$success,"notice"=>$notice));
    }
    
    //Ajax load session by course id
    public function actionAjaxLoadSessionsByCourseId($id)
    {
        $uid = yii::app()->user->id;
        $course  = Course::model()->findByPk($id);
        $sessions = $course->getSessions(null, $uid);//Get all session of course
        $sessionDay = array();
        if($sessions)
        {
            foreach($sessions as $item)
            {
                if($item->content==null)
                {
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
    
    //Session detail
    public function actionSession($id)
    {
        $this->subPageTitle = 'Session Information';
        $uid = yii::app()->user->id;
        $session  = Session::model()->findByPk($id);
        if($session->teacher_id!=$uid)
        {
            $this->redirect(array('index'));
        }
        $this->render('session',array(
                "session"=>$session,
            )
        );
    }
}
?>