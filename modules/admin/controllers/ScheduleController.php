<?php

class ScheduleController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    // public $layout='//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index', 'view', 'calendarCreateSession', 'calendarUpdateSession', 'calendarDeleteSession', 
				'ajaxSearchTeacher', 'calendarTeacherView', 'getSessions', 'registerSchedule', 'getTeacherSchedule', 'saveSchedule',
				'ajaxLoadCourse', 'countSession', 'changeSchedule'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete'),
                'users'=>array('*'),
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }

	public function actionView()
	{
		$this->subPageTitle = 'Lịch học';
		
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
				"page"=>$page,
			));
		} else {
			$this->render('teacher', $_REQUEST);
		}
		//remember to do some timezone too
	}
	
	public function actionCalendarCreateSession()
	{
		$success = false;
		
		if (isset($_POST['Session'])){
			if (isset($_POST['studentId'])){
				$studentId = $_POST['studentId'];
				$query = "SELECT session_id FROM tbl_session JOIN tbl_session_student " .
						 "ON tbl_session.id = tbl_session_student.session_id " .
						 "WHERE tbl_session_student.student_id = " . $studentId . " " .
						 "AND tbl_session.plan_start = '" . $_POST['Session']['plan_start'] . ' '.$_POST['startHour'].':'.$_POST['startMin'].":00'";
				$existingSession = Yii::app()->db->createCommand($query)->queryRow();
			}
			
			if (!$existingSession){
				$courseId = $_POST['Session']['course_id'];
				if ($courseId < 0){
					$newCourseType = $_POST['Session']['course_id'] * -1;
					$course = new Course();
					$course->created_user_id = Yii::app()->user->id;
					$course->teacher_id = $_POST['Session']['teacher_id'];
					$course->status = Course::STATUS_APPROVED;
					$course->type = $newCourseType;
					$course->subject_id = $_POST['subjectId']; //->hardcoded menu
					$user = User::model()->findByPk($studentId);
					if ($newCourseType == Course::TYPE_COURSE_TRAINING){
						$course->title = "Trial course for " .  $user->fullname();
					} else {
						$course->title = $user->fullname();
					}
					$course->save();
					$courseId = $course->getPrimaryKey();
				}
				if (!isset($course)){
					$course = Course::model()->findByPk($courseId);
				}
				
				$session = new Session();
				
				$session->attributes = $_POST['Session'];
				$session->course_id = $courseId;
				$session->plan_start .= ' '.$_POST['startHour'].':'.$_POST['startMin'].':00';
				$session->status = Session::STATUS_APPROVED;
				$session->type = Session::TYPE_SESSION_NORMAL;
				if($session->save()){
					$assignedStudentIds = $course->assignedStudents();
					if (empty($assignedStudentIds)){
						$course->assignStudentsToCourseSession(array($studentId));
					} else {
						$session->assignStudentsToSession($assignedStudentIds);
					}
					$success = true;
				}
				$this->renderJSON(array("success"=>$success, "session"=>$session, "course"=>$course));
			} else {
				$this->renderJSON(array(
					"success"=>$success,
					"teacher"=>$_POST['Session']['teacher_id'],
					"existingSession"=>$existingSession['session_id']
				));
			}
		} else {
			$this->renderJSON(array("success"=>$success));
		}
	}
	
	public function actionCalendarUpdateSession(){
		$success = false;
		
		//Creating new session encounter another session with another teacher
		//->change teacher of that session
		if (isset($_POST['changeTeacher'])){
			if (isset($_POST['session']) && isset($_POST['teacher'])) {
				$session = Session::model()->findByPk($_POST['session']);
				$session->status = Session::STATUS_APPROVED;
				$session->teacher_id = $_POST['teacher'];
				if ($session->save()){
					$success = true;
				}
			}
			$this->renderJSON(array("success"=>$success));
		//editing session encounter another session with another teacher
		} else if (isset($_POST['duplicateSession'])){
			if (isset($_POST['existingSession']) && isset($_POST['currentSession']) && isset($_POST['studentId'])){
				$existingSession = Session::model()->findByPk($_POST['existingSession']);
				$existingSession->delete();
				
				$currentSession = Session::model()->findByPk($_POST['currentSession']);
				$currentSession->deleteAssignedStudents();
				$currentSession->assignStudentsToSession(array($_POST['studentId']));
				$currentSession->course_id = $_POST['courseId'];
				$currentSession->subject = $_POST['subject'];
				if ($currentSession->save()){
					$success = true;
				}
			}
			$this->renderJSON(array("success"=>$success));
		} else {
			//normal case, change student of the session
			if (isset($_POST['studentId'])){
				$studentId = $_POST['studentId'];
				$query = "SELECT session_id FROM tbl_session JOIN tbl_session_student " .
						 "ON tbl_session.id = tbl_session_student.session_id " .
						 "WHERE tbl_session_student.student_id = " . $studentId . " " .
						 "AND tbl_session.plan_start = '" . $_POST['Session']['plan_start'] . "'";
				$existingSession = Yii::app()->db->createCommand($query)->queryRow();
			}
			
			if (!$existingSession){
				$session = Session::model()->findByPk($_POST['sessionId']);
				$session->deleteAssignedStudents();
				$session->assignStudentsToSession(array($_POST['studentId']));
				$session->course_id = ($_POST['Session']['course_id']);
				$session->subject = ($_POST['Session']['subject']);
				if ($session->save()){
					$success = true;
				}
			} else {
				$this->renderJSON(array(
					"success"=>$success,
					"existingSession"=>$existingSession['session_id'],
					"currentSession"=>$_POST['sessionId'],
					"currentCourse"=>$_POST['Session']['course_id'],
					"currentSubject"=>$_POST['Session']['subject'],
					"currentStudent"=>$_POST['studentId'],
				));
			}
			
			$this->renderJSON(array("success"=>$success));
		}
	}
	
	public function actionCalendarDeleteSession(){
		$session = Session::model()->findByPk($_POST['id']);
		$course_id = $session->course_id;
		$whiteboard = $session->whiteboard;
		$session->deleteAssignedStudents();
		$session->delete();
		if (trim($whiteboard) != ""){
			try{
				Yii::app()->board->removeBoard($whiteboard);
			} catch(Exception $e){
				//errr... nothing to do here
			}
		}
	}
	
	public function actionAjaxSearchTeacher($keyword){
		$teacherAttributes = User::model()->searchUsersToAssign($keyword, 'role_teacher');
		$this->renderJSON(array("result"=>$teacherAttributes));
	}
	
	public function actionGetSessions(){
		$teacherIds = json_decode($_REQUEST["teachers"]);
		
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
		
		$query = "SELECT * FROM tbl_session " . 
				 "WHERE teacher_id IN (" . implode(', ',$teacherIds) . ") " .
				 "AND plan_start BETWEEN '" . $start . "' AND '" . $end . "' " .
				 "AND status <> " . Session::STATUS_CANCELED . " " .
				 "AND deleted_flag <> 1";
		$sessions = Session::model()->findAllBySql($query);
		$sessionDays = array();
		if (!empty($sessions)){
			foreach ($sessions as $session){
				$backgroundColor;
				switch ($session->status){
					case Session::STATUS_APPROVED:
						$backgroundColor = 'lime';
						$className = 'approvedSession';
						break;
					case Session::STATUS_WORKING:
						$backgroundColor = 'turquoise';
						$className = 'ongoingSession';
						break;
					case Session::STATUS_CANCELED:
						$backgroundColor = 'red';
						$className = 'canceledSession';
						break;
					case Session::STATUS_ENDED:
						$backgroundColor = 'darkorange';
						$className = 'endedSession';
						break;
					case Session::STATUS_PENDING:
						$backgroundColor = 'green';
						$className = 'pendingSession';
						break;
					default:
						$backgroundColor = '#3a87ad';
						$className = 'unknownSessionStatus';
						break;
				}
				
				$students = $session->assignedStudents();
				if (!empty($students)){
					$studentId = array_pop($students);
					$student = User::model()->findByPk($studentId);
					$title = $student->fullname() . ' (' . $studentId . ')';
				} else {
					$title = '';
				}
				
				$sessionDays[] = array(
                    'id' => $session->id,
                    'title' => (($title != '') ? $title : $session->subject) . (($session->type == Session::TYPE_SESSION_TRAINING) ? '(Trial)' : ''),
                    'content'=>$session->content,
                    'start' => $session->plan_start,
                    'end'=> date("Y-m-d H:i:s",strtotime($session->plan_start) + $session->plan_duration*60),
                    'allDay'=> false,
					'backgroundColor'=>$backgroundColor,
					'className'=>$className,
					'resources'=> $session->teacher_id,
					'teacher'=> $session->teacher_id,
					'subject'=> $session->subject,
					'teacherName'=> User::model()->findByPk($session->teacher_id)->fullname(),
					'course_id'=>$session->course_id,
					'student'=>$title,
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
				"name"=> "<a href=" . Yii::app()->baseUrl . "/admin/schedule/view?teacher=" . $teacher->id . ">
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
		));
	}
	
	public function actionRegisterSchedule(){
		$this->subPageTitle = 'Lịch dạy của giáo viên';
		if (!isset($_REQUEST['teacher'])){
			$this->loadJQuery = false; //loading jquery will cause conflict thus yii jquery for gridview will be failed to execute
								   //if you want to write a comment, write it properly =.=
			$teacher=new User('search');
			$teacher->unsetAttributes();
			$criteria = new CDbCriteria();
			$criteria->compare('role',User::ROLE_TEACHER);
			$criteria->compare('status', User::STATUS_OFFICIAL_USER);
			$criteria->compare('deleted_flag', 0);
			$teacher->setDbCriteria($criteria);
			if(isset($_GET['User'])){
				$teacher->attributes=$_GET['User'];
				if(isset($_GET['User']['firstname'])){
					$keyword = $_GET['User']['firstname'];
					$teacher->getDbCriteria()->addCondition("CONCAT(`lastname`,' ',`firstname`) LIKE '%".$keyword."%'");
				}
			}
			$this->render('listTeacher',array(
				'teacher'=>$teacher,
			));
		} else {
			$this->render('registerSchedule', array(
				'teacher'=>$_REQUEST['teacher'],
			));
		}
	}
	
	public function actionGetTeacherSchedule(){
		if (isset($_REQUEST['teacher'])){
			$teacherId = $_REQUEST['teacher'];
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
	}

	public function actionSaveSchedule(){
		$success = false;
		if (isset($_POST['teacher']) && isset($_POST['week_start']) && isset($_POST['timeslots'])){
			$query = "INSERT INTO tbl_teacher_timeslots (teacher_id, week_start, timeslots) " . 
					 "VALUES(" . $_POST['teacher'] . ", '" . $_POST['week_start'] . "', '" . $_POST['timeslots'] . "')" . " " .
					 "ON DUPLICATE KEY UPDATE timeslots = VALUES(timeslots)";
			try {
				$success = true;
				Yii::app()->db->createCommand($query)->query();
			} catch (Exception $ex){
				$success = false;
			}
		}
		$this->renderJSON(array(
			'success'=>$success,
		));
	}
	
	public function actionAjaxLoadCourse($student){
		$query = "SELECT id, title, type FROM tbl_course JOIN tbl_course_student " .
				 "ON tbl_course.id = tbl_course_student.course_id " .
				 "WHERE tbl_course_student.student_id = " . $student . " " .
				 "AND (tbl_course.status = " . Course::STATUS_WORKING . " " .
				 "OR tbl_course.status = " . Course::STATUS_APPROVED . ") " .
				 "AND deleted_flag <> 1 " . " " .
				 "ORDER BY course_id DESC";
		$courses = Course::model()->findAllBySql($query);
		
		foreach($courses as $key=>$course){
			if ($course->title == ""){
				$courses[$key]['title'] = $course->id;
			}
			if ($course->type == Course::TYPE_COURSE_TRAINING){
				$courses[$key]['title'] .= " (Trial)";
			}
		}
		
		$student = User::model()->findByPk($student);
		if (empty($courses) && $student->status < User::STATUS_OFFICIAL_USER){
			$courseOptions = array(
				array(
					"id"=>Course::TYPE_COURSE_TRAINING*-1,
					"title"=>"Tạo khóa học thử",
				),
			);
		} else {
			$courseOptions = array(
				array(
					"id"=>Course::TYPE_COURSE_NORMAL*-1,
					"title"=>"Tạo khóa học thường",
				),
				array(
					"id"=>Course::TYPE_COURSE_TRAINING*-1,
					"title"=>"Tạo khóa học thử",
				),
			);
		}
		
		$courseOptions = array_merge($courses, $courseOptions);
		
		$this->renderJSON($courseOptions);
	}
	
	public function actionCountSession(){
		if (isset($_REQUEST['course'])){
			$query = "SELECT COUNT(id) FROM tbl_session " .
					 "WHERE course_id = " . $_REQUEST['course'] . " " . 
					 "AND deleted_flag <> 1";
			$result = Yii::app()->db->createCommand($query)->queryColumn();
			$sessionCount = $result[0];
			
			$this->renderJSON(array("sessionCount"=>$sessionCount));
		}
	}
	
	public function actionChangeSchedule(){
		$success = false;
		if (isset($_POST['sessionId']) && isset($_POST['teacher']) && isset($_POST['start'])){
			$session = Session::model()->findByPk($_POST['sessionId']);
			$students = $session->assignedStudents();
			$existingSession = false;
			if (!empty($students)){
				$query = "SELECT session_id FROM tbl_session JOIN tbl_session_student " .
						 "ON tbl_session.id = tbl_session_student.session_id " .
						 "WHERE tbl_session_student.student_id IN (" . implode(',', $students) . ") " .
						 "AND tbl_session.plan_start = '" . $_POST['start'] . "'";
				$existingSession = Yii::app()->db->createCommand($query)->queryRow();
			}
			if (!$existingSession){
				$session->teacher_id = $_POST['teacher'];
				$session->plan_start = $_POST['start'];
				if ($session->save()){
					$success = true;
				}
			} else {
				$this->renderJSON(array('success'=>false, 'reason'=>'duplicate_session'));
			}
		}
		$this->renderJSON(array('success'=>$success));
	}
}
