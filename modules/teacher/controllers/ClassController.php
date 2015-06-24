<?php

class ClassController extends Controller
{
    public function init()
    {
        if(Yii::app()->user->isGuest)
            $this->redirect("/");
    }
    
    public function actionIndex()
    {
		$this->subPageTitle = 'Courses List';
        $uid = yii::app()->user->id;
        $teacherCourses = Course::model()->findAll(array("condition"=>"teacher_id = ".$uid." AND deleted_flag=0"));
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
    public function actionRegisterSchedule(){
        $this->subPageTitle = 'Register Schedule';
    	$teacherId = Yii::app()->user->id;
            
        if (isset($_POST['week_start']) && isset($_POST['timeslots'])){
			$query = "INSERT INTO tbl_teacher_timeslots (teacher_id, week_start, timeslots) " . 
					 "VALUES(" . $teacherId . ", '" . $_POST['week_start'] . "', '" . $_POST['timeslots'] . "')" . " " .
					 "ON DUPLICATE KEY UPDATE timeslots = VALUES(timeslots)";
			Yii::app()->db->createCommand($query)->query();
		} else {
			$this->render('registerSchedule');
		}
    }
	
	public function actionGetSchedule(){
		$teacherId = Yii::app()->user->id;
		if (isset($_REQUEST['week_start'])){
			$query = "SELECT timeslots FROM tbl_teacher_timeslots WHERE teacher_id = " . $teacherId . " AND week_start = '" . $_REQUEST['week_start'] . "'";
		
			$result = Yii::app()->db->createCommand($query)->queryRow();
			$timeslots = $result['timeslots'];
			
			$weekEnd = date('Y-m-d', strtotime('+6 days', strtotime($_REQUEST['week_start'])));
			
			$query = "SELECT plan_start FROM tbl_session ".
					 "WHERE teacher_id = " . $teacherId . " ".
					 "AND plan_start BETWEEN '" . $_REQUEST['week_start'] . "' AND '" . $weekEnd . "' ".
					 "AND status <> " . Session::STATUS_CANCELED;
			$bookedSlots = Yii::app()->db->createCommand($query)->queryColumn();
			
			//converting booked session time to timeslot number
			//OMG too long, should I use an array instead
			$startHour = 9;
			$startMin = 0;
			$slotDuration = 40;
			$slotCount = 21;
			$start = $startHour * 60 + $startMin;
			
			$bookedSessions = array();
			if (!empty($bookedSlots)){
				foreach ($bookedSlots as $slot){
					$weekday = (date('w', strtotime($slot)) + 6) % 7;
					
					$slotHour = (int)substr($slot, -8, 2);
					$slotMin = (int)substr($slot, -5, 2);
					
					$slotTime = $slotHour * 60 + $slotMin;
					if ($slotTime < $start) continue;
					$slotNumber = (int) (($slotTime - $start) / $slotDuration);
					$bookedSessions[] = $weekday * $slotCount + $slotNumber;
				}
			}
			
			$this->renderJSON(array("timeslots"=>$timeslots, "bookedSessions"=>$bookedSessions));
		}
	}
	
    public function actionCalendar()
    {
		$this->subPageTitle = 'Calendar View';
        $uid = yii::app()->user->id;
		
		if (isset($_REQUEST['month'])){
			$month = $_REQUEST['month'];
		} else {
			$month = date('m');
		}
		
		$year = date('Y');
		$startWeek = date('W', mktime(0, 0, 0, $month, 1, $year));
		$monthStart = date('Y-m-d', strtotime($year.'W'.$startWeek));
		$endWeek = $startWeek + (int)(date('t', mktime(0, 0, 0, $month, 1, $year))/7);
		$monthEnd = date('Y-m-d', strtotime('+6 days', strtotime($year.'W'.$endWeek)));
        
		$query = "SELECT * FROM tbl_session " .
				 "WHERE teacher_id = " . $uid . " " .
				 "AND plan_start BETWEEN '" . $monthStart . "' AND '" . $monthEnd . "' ".
				 "AND status <> " . Session::STATUS_CANCELED . " " .
				 "AND deleted_flag <> 1";
		$sessions = Session::model()->findAllBySql($query);
		
		$sessionDay = array();
		if ($sessions)
		{
			foreach ($sessions as $session)
			{
				$backgroundColor;
				switch ($session->status){
					case Session::STATUS_APPROVED:
						$backgroundColor = 'lime';
						break;
					case Session::STATUS_WORKING:
						$backgroundColor = 'turquoise';
						break;
					case Session::STATUS_CANCELED:
						$backgroundColor = 'red';
						break;
					case Session::STATUS_ENDED:
						$backgroundColor = 'darkorange';
						break;
					case Session::STATUS_PENDING:
						$backgroundColor = 'green';
						break;
					default:
						$backgroundColor = '#3a87ad';
						break;
				}
				
				$title = implode('<br>', $session->getAssignedStudentsArrs());
				
				$sessionDay[] = array(
                    'id' => $session->id,
                    'title' => (($title != '') ? $title : $session->subject),
                    'content'=>$session->content,
                    'start' => $session->plan_start,
                    'end'=> date("Y-m-d H:i:s",strtotime($session->plan_start) + $session->plan_duration*60),
                    'allDay'=> false,
					'backgroundColor'=>$backgroundColor,
                );
			}
		}

		if (isset($_REQUEST['refresh'])){
			$this->renderJSON(array("sessions"=>$sessionDay));
		} else {
			$this->render('calendar',array(
					"sessions"=>json_encode($sessionDay),
				)
			);
		}
    }

}
?>