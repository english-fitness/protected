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

	public function actionCalendar()
    {
		$this->subPageTitle = Yii::t('lang','reservation');
		
		if (isset($_REQUEST['teacher']) && trim($_REQUEST['teacher']) != ''){
			$view = 'teacher';
		} else {
			$view = 'day';
		}
		
		if ($view == 'day'){
			//get teacher id
			//render the day view page
			//do some pagination
			if (isset($_REQUEST['page'])){
				$page = $_REQUEST['page'];
			} else {
				$page = 1;
			}
			
			$query = "SELECT id FROM tbl_user " .
					 "WHERE role = '" . User::ROLE_TEACHER . "' ".
					 "AND status = " . User::STATUS_OFFICIAL_USER;
			$result = Yii::app()->db->createCommand($query)->queryColumn();
			$pageCount = ceil(sizeOf($result) / 12);
						
			$teachers = array_slice($result, ($page - 1) * 12, 12, true);
			
			$this->render('calendar', array(
				"teachers"=>json_encode(array_values($teachers)),
				"pageCount"=>$pageCount,
				"page"=>$page
			));
		} else {
			//render teacher view
			//will fix the action later
			$this->render('teacher', $_REQUEST);
		}
		//remember to do some timezone too
    }
	
	public function actionGetSessions(){
		$teacherIds = json_decode($_REQUEST["teachers"]);
		$user = User::model()->findByPk(Yii::app()->user->id);
		
		if (isset($_REQUEST['view'])){
			$view = $_REQUEST['view'];
		} else {
			$view = 'week';
		}
		
		if ($view == 'month'){
			if (isset($_REQUEST['month'])){
				$month = $_REQUEST['month'];
			} else {
				$month = date('m');
			}
			
			$year = date('Y');
			$startWeek = date('W', mktime(0, 0, 0, $month, 1, $year));
			$start = date('Y-m-d', strtotime($year.'W'.$startWeek));
			$endWeek = $startWeek + (int)(date('t', mktime(0, 0, 0, $month, 1, $year))/7);
			$end = date('Y-m-d', strtotime('+6 days', strtotime($year.'W'.$endWeek)));
		} else {
			//too lazy to write handling code for non-monday week_start
			if (isset($_REQUEST['week_start']) && date ('w', strtotime($_REQUEST['week_start'])) == 1){
				$weekStartTimestamp = strtotime($_REQUEST['week_start']);
				$start = date('Y-m-d', $weekStartTimestamp);
				$end = date('Y-m-d', strtotime('+6 days', $weekStartTimestamp));
			} else {
				$start = date('Y-m-d', strtotime('monday this week'));
				$end = date('Y-m-d', strtotime('sunday this week'));
			}
		}
		
		$query = "SELECT tbl_session.* FROM tbl_session JOIN tbl_session_student " .
				 "ON tbl_session.id = tbl_session_student.session_id " .
				 "WHERE tbl_session.teacher_id IN (" . implode(', ',$teacherIds) . ") " .
				 "AND plan_start BETWEEN '" . $start . "' AND '" . $end . "' ".
				 "AND tbl_session_student.student_id <> " . $user->id . " " .
				 "AND status <> " .  Session::STATUS_CANCELED . " " .
				 "GROUP BY tbl_session.id";
		$otherSessions = Session::model()->findAllBySql($query);
		
		$query = "SELECT tbl_session.* FROM tbl_session JOIN tbl_session_student " .
				 "ON tbl_session.id = tbl_session_student.session_id " .
				 "WHERE tbl_session.teacher_id IN (" . implode(', ',$teacherIds) . ") " .
				 "AND plan_start BETWEEN '" . $start . "' AND '" .$end . "' ".
				 "AND tbl_session_student.student_id = " . $user->id;
		$ownSessions = Session::model()->findAllBySql($query);
		
		$sessionDays = array();
		if (!empty($otherSessions)){
			foreach ($otherSessions as $session){
				$backgroundColor = 'dodgerblue';
				$title = "Booked";
				
				$sessionDays[] = array(
					'id' => $session->id,
					'title' => $title,
					'content'=>$session->content,
					'start' => $session->plan_start,
					'end'=> date("Y-m-d H:i:s",strtotime($session->plan_start) + $session->plan_duration*60),
					'allDay'=> false,
					'backgroundColor'=>$backgroundColor,
					'resources'=> $session->teacher_id,
					'teacher'=> $session->teacher_id,
					'subject'=> $session->subject,
				);
			}
		}
		
		if (!empty($ownSessions)){
			foreach($ownSessions as $session){
				$title = $user->fullname() . (($session->type == Session::TYPE_SESSION_TRAINING) ? "(Trial)" : "");
				$className = "";
				
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
						$className = "unbookable";
						break;
					default:
						$backgroundColor = '#3a87ad';
						break;
				}
				
				$sessionDays[] = array(
					'id' => $session->id,
					'title' => $title,
					'content'=>$session->content,
					'start' => $session->plan_start,
					'end'=> date("Y-m-d H:i:s",strtotime($session->plan_start) + $session->plan_duration*60),
					'allDay'=> false,
					'backgroundColor'=>$backgroundColor,
					'className'=>$className,
					'resources'=> $session->teacher_id,
					'teacher'=> $session->teacher_id,
					'subject'=> $session->subject,
				);
			}
		}
		
		if ($view == 'week'){
			$query = "SELECT * FROM tbl_teacher_timeslots " . 
					 "WHERE teacher_id IN (" . implode(', ', $teacherIds) . ") AND week_start = '" . $start . "'";
		} else {
			$query = "SELECT * FROM tbl_teacher_timeslots " . 
					 "WHERE teacher_id IN (" . implode(', ', $teacherIds) . ") " .
					 "AND week_start BETWEEN '" . $start . "' AND '" . $end . "'";
		}
		$result = Yii::app()->db->createCommand($query)->queryAll();
		
		$tempTeachers = User::model()->findAllBySql('SELECT id, firstname, lastname, profile_picture FROM tbl_user WHERE id IN (' . implode(', ', $teacherIds) . ")");
		
		$teachers = array();
		
		foreach($tempTeachers as $teacher){
			$teachers[] = array(
				"id"=>$teacher->id,
				"name"=> "<a href=" . Yii::app()->baseUrl . "/student/class/calendar?teacher=" . $teacher->id . ">
							<img src=". Yii::app()->user->getProfilePicture($teacher->id) ." style='margin:3px;width:180px;height:180px'></img><br>" . 
							$teacher->fullname() .
						 "</a>",
			);
		}
		
		$availableSlots = array();
		foreach ($result as $item){
			$availableSlots[] = array("teacher"=>$item["teacher_id"], "weekStart"=>$item["week_start"], "timeslots"=>$item["timeslots"]);
		}
		
		$this->renderJSON(array(
			"teachers"=>$teachers,
			"sessions"=>$sessionDays,
			"availableSlots"=>$availableSlots,
			"start"=>$start,
			"end"=>$end,
			"language"=>Yii::app()->sourceLanguage,
		));
	}
	
	public function actionBookSession(){
		$success = false;
		
		if(isset($_POST['Session'])){
			if (date ("Y-m-d H:i:s", strtotime($_POST['Session']['plan_start'])) <= date('Y-m-d H:i:s')){
				$this->renderJSON(array("success"=>$success, "reason"=>"slot_closed"));
			}
			$user = User::model()->findByPk(Yii::app()->user->id);
			$userId = $user->id;
			
			//check for existing session in the same timeslot
			$query = "SELECT session_id, tbl_session.status FROM tbl_session JOIN tbl_session_student " .
					 "ON tbl_session.id = tbl_session_student.session_id " .
					 "WHERE tbl_session_student.student_id = " . $userId . " " .
					 "AND tbl_session.plan_start = '" . $_POST['Session']['plan_start'] . "'";
			$existingSession = Yii::app()->db->createCommand($query)->queryRow();
			
			if (!$existingSession){
				//try to get an active course
				$query = "SELECT tbl_course.id as course_id, tbl_course.type as type FROM tbl_course_student JOIN tbl_course " .
						 "ON tbl_course.id = tbl_course_student.course_id " .
						 "WHERE tbl_course_student.student_id = " . $userId . " ".
						 "AND (tbl_course.status = " . Course::STATUS_WORKING . " " .
						 "OR tbl_course.status = " . Course::STATUS_APPROVED . ") " .
						 "ORDER BY course_id DESC";
				$currentCourses = Yii::app()->db->createCommand($query)->queryAll();
				
				if (!empty($currentCourses)){
					$activeCourse = $currentCourses[sizeOf($currentCourses) - 1];
								
					$query = "SELECT COUNT(session_id) FROM tbl_session_student JOIN tbl_session " .
							 "ON tbl_session_student.session_id = tbl_session.id " .
							 "WHERE tbl_session_student.student_id = " . $userId . " " .
							 "AND  tbl_session.course_id = " . $activeCourse['course_id'];
					//php 5.3 doesn't allow function array dereference hence the separated statement
					$result = Yii::app()->db->createCommand($query)->queryColumn();
					$sessionCount = $result[0];
				} else {
					$this->renderJSON(array('success'=>false, "reason"=>"no_active_course"));
				}
				
				$session = new Session();
				$session->attributes = $_POST['Session'];
				$session->plan_duration = Session::DEFAULT_DURATION;
				if ($activeCourse['type'] == Course::TYPE_COURSE_NORMAL){
					$session->subject = "Session " . ($sessionCount + 1);
				} else {
					$session->subject = "Trial Session";
				}
				$session->course_id = $activeCourse['course_id'];
				$session->status = Session::STATUS_PENDING;
				$session->type = $activeCourse['type'];
				
				$course = Course::model()->findByPk($activeCourse['course_id']);
				if($session->save()){
					$assignedStudentIds = $course->assignedStudents();
					if (empty($assignedStudentIds)){
						$course->assignStudentsToCourseSession(array($user->id));
					} else {
						$session->assignStudentsToSession($assignedStudentIds);
					}
					$success = true;
				}
				
				$this->renderJSON(array("success"=>$success, "session"=>$session));
			} else {
				if ($existingSession['status'] == Session::STATUS_APPROVED){
					$this->renderJSON(array(
						"success"=>$success,
						"canRebook"=>false,
					));
				}
				$this->renderJSON(array(
					"success"=>$success, 
					"teacher"=>$_POST['Session']['teacher_id'], 
					"existingSession"=>$existingSession['session_id'],
					"canRebook"=>true,
					"query"=>$query,
				));
			}
		} else {
			$this->renderJSON(array("success"=>$success));
		}
	}
	
	public function actionChangeTeacher(){
		$success = false;
		if (isset($_POST['session']) && isset($_POST['teacher'])){
			$session = Session::model()->findByPk($_POST['session']);
			$session->status = Session::STATUS_PENDING;
			$session->teacher_id = $_POST['teacher'];
			if ($session->save()){
				$success = true;
			}
		}
		
		$this->renderJSON(array("success"=>$success));
	}
	
	public function actionUnbookSession(){
		if (isset($_POST['session'])){
			$session = Session::model()->findByPk($_POST['session']);
			$course_id = $session->course_id;
			$whiteboard = $session->whiteboard;
			$session->deleteAssignedStudents();
			$session->delete();
			if (trim($whiteboard) != ""){
				try{
					Yii::app()->board->removeBoard($whiteboard);
				} catch(Exception $e){
				}
			}
		}
	}
	
	public function actionAjaxSearchTeacher($keyword){
		$query = "SELECT id, firstname, lastname FROM tbl_user " . 
				 "WHERE CONCAT(`lastname`,' ',`firstname`) LIKE '%".$keyword."%' " .
				 "AND role = '" . User::ROLE_TEACHER . "' " .
				 "AND status = " . User::STATUS_OFFICIAL_USER . " " .
				 "LIMIT 30";
		$teachers = User::model()->findAllBySql($query);
		$results = array();
		foreach($teachers as $teacher){
			$results[] = array(
				'id' => $teacher->id,
				"usernameAndFullName" => $teacher->fullName(),
			);
		}
		$this->renderJSON(array("result"=>$results));
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