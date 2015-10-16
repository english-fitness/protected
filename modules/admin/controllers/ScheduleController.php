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
				'ajaxLoadCourse', 'countSession', 'changeSchedule', 'overview', 'getWeekSchedule'),
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

            if (isset($_REQUEST['date'])){
                $currentDay = $_REQUEST['date'];
            } else {
                $currentDay = date('Y-m-d');
            }
		}

		if ($view == 'day'){
			if (isset($_GET['status']) && isset(Teacher::statusOptions()[$_GET['status']])){
				$teacherStatus = $_GET['status'];
			} else {
				$teacherStatus = Teacher::STATUS_OFFICIAL;
			}

			if (isset($_REQUEST['page'])){
				$page = $_REQUEST['page'];
			} else {
				$page = 1;
			}

			$teacherCount = User::model()->countByAttributes(array(
				'role'=>User::ROLE_TEACHER,
				'status'=>User::STATUS_OFFICIAL_USER
			));

			$pageCount = ceil($teacherCount / 12);

			$query = "SELECT id FROM tbl_user " .
					 "WHERE role = '" . User::ROLE_TEACHER . "' ".
					 "AND status = " . $teacherStatus . " " .
					 "LIMIT 12 OFFSET " . (($page - 1) * 12);
			$teachers = Yii::app()->db->createCommand($query)->queryColumn();

			$this->render('calendar', array(
				"teachers"=>json_encode(array_values($teachers)),
				"pageCount"=>$pageCount,
				"page"=>$page,
                "current_day"=>$currentDay,
			));
		} else {
			$this->render('teacher', $_REQUEST);
		}
		//remember to do some timezone too
	}

	public function actionOverview(){
		$this->render("overview");
	}

	public function actionGetWeekSchedule(){
		if (isset($_GET["w"]) && ctype_digit($_GET["w"])){
			if (isset($_GET["y"]) && ctype_digit($_GET["y"])){
				$y = $_GET["y"];
			} else {
				$y = date("Y");
			}

			if (isset($_GET["teacher_status"])){
				$teacherStatus = $_GET["teacher_status"];
			} else {
				$teacherStatus = Teacher::STATUS_OFFICIAL;
			}

			$validTeachers = Yii::app()->db->createCommand()
			    ->select("id")
			    ->from(User::model()->tableSchema->name)
			    ->where("role = '".User::ROLE_TEACHER."' AND status = ".$teacherStatus)
			    ->queryColumn();

		    $weekSchedule = array();
		    $bookedSlots = array();
		    if (count($validTeachers) > 0){
				$teacherCondition = "AND teacher_id IN (" . implode(",", $validTeachers) . ")";

				$weekStartTimestamp = strtotime($y."W".$_GET["w"]);
				$weekStart = date("Y-m-d", $weekStartTimestamp);

				$criteria = new CDbCriteria;
				$criteria->condition = "week_start = '" . $weekStart . "' " . $teacherCondition;
				$criteria->select = array("teacher_id", "timeslots");
				$schedules = TeacherTimeslots::model()->findAll($criteria);

				$availableTeachers = array();
				foreach($schedules as $schedule){
					$weekSchedule[$schedule->teacher_id] = $schedule->timeslots;
				}

				$weekStart = date("Y-m-d 00:00:00", $weekStartTimestamp);
				$weekEnd = date("Y-m-d 00:00:00", strtotime("+7 days", $weekStartTimestamp));
				$criteria = new CDbCriteria;
				$criteria->alias = "s";
				$criteria->condition =  "plan_start >= '" . $weekStart . "' AND plan_start < '" . $weekEnd . "' " .
										$teacherCondition . " " .
										"AND s.status <> " . Session::STATUS_CANCELED . " " .
										"AND s.deleted_flag = 0";
				$criteria->select = array("teacher_id", "plan_start");
				$sessions = Session::model()->with(array(
					"students"=>array(
						"select"=>array("student_id"),
					),
				))->findAll($criteria);

				foreach ($sessions as $session){
					$students = array();
					foreach ($session->students as $student) {
						$students[] = $student->student_id;
					}
					$bookedSlots[] = array(
						"teacher_id"=>$session->teacher_id,
						"plan_start"=>$session->plan_start,
						"students"=>$students,
					);
				}

			}


			$this->renderJSON(array(
				"schedule"=>$weekSchedule,
				"booked"=>$bookedSlots,
			));
		}
	}

	public function actionCalendarCreateSession()
	{
		$success = false;

		if (isset($_POST['Session'])){
			if (isset($_POST['studentId'])){
				$studentId = $_POST['studentId'];
				$start_time = $_POST['Session']['plan_start'] . ' '.$_POST['startHour'].':'.$_POST['startMin'].":00";
				$existingSession = Session::model()->findStudentExistingSession($studentId, $start_time);
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
				$session->type = isset($course->type) ? $course->type : Session::TYPE_SESSION_NORMAL;
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
				$existingSession = Session::model()->findStudentExistingSession($studentId,  $_POST['Session']['plan_start']);
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
		$teacherIds = json_decode($_GET["teachers"]);

		if (isset($_GET['view'])){
			$view = $_GET['view'];
		} else {
			$view = 'week';
		}

		if ($view == 'month'){
			if (isset($_GET['month'])){
				$month = $_GET['month'];
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
			if (isset($_GET['week_start']) && date ('w', strtotime($_GET['week_start'])) == 1){
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
				 "AND deleted_flag = 0";
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
			$availableSlots = TeacherTimeslots::model()->getMultipleSchedules($teacherIds, $start);
		} else {
			$availableSlots = TeacherTimeslots::model()->getMultipleSchedules($teacherIds, $start, $end);
		}

		$tempTeachers = User::model()->findAllBySql('SELECT id, firstname, lastname, profile_picture FROM tbl_user WHERE id IN (' . implode(', ', $teacherIds) . ")");

		$teachers = array();

		foreach($tempTeachers as $teacher){
			$teachers[] = array(
				"id"=>$teacher->id,
				"name"=>$teacher->getProfilePictureHtml(
					array('style'=>'margin:3px;width:180px;height:180px'),
					Yii::app()->baseUrl . "/admin/schedule/view?teacher=" . $teacher->id ,
					$teacher->fullname()
				),
			);
		}

		$this->renderJSON(array(
			"teachers"=>$teachers,
			"sessions"=>$sessionDays,
			"availableSlots"=>$availableSlots,
			"start"=>$start,
			"end"=>$end,
		));
	}

	//actions for registering teacher schedule

	public function actionRegisterSchedule(){
		$this->subPageTitle = 'Lịch dạy của giáo viên';
		if (!isset($_REQUEST['teacher'])){
			$this->loadJQuery = false; //loading jquery will cause conflict thus yii jquery for gridview will be failed to execute
								   //if you want to write a comment, write it properly =.=
			$teacher=new User('search');
			$teacher->unsetAttributes();
			$criteria = new CDbCriteria();
			$criteria->compare('role',User::ROLE_TEACHER);
			$criteria->compare('deleted_flag', 0);
			$criteria->addCondition("status = " . Teacher::STATUS_OFFICIAL . " OR status = " . Teacher::STATUS_TESTER);
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
				$timeslots = TeacherTimeslots::model()->getSchedule($teacherId, $_REQUEST['week_start']);

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
			if (TeacherTimeslots::model()->saveSchedule($_POST['teacher'], $_POST['week_start'], $_POST['timeslots'])){
				$success = true;
			}
		}
		$this->renderJSON(array(
			'success'=>$success,
		));
	}

	//end of actions for registering teacher schedule

	public function actionAjaxLoadCourse($student){
		$courses = Course::model()->findByStudent($student);

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
					"title"=>"Tạo khóa học thử mới",
				),
			);
		} else {
			$courseOptions = array(
				array(
					"id"=>Course::TYPE_COURSE_NORMAL*-1,
					"title"=>"Tạo khóa học thường mới",
				),
				array(
					"id"=>Course::TYPE_COURSE_TRAINING*-1,
					"title"=>"Tạo khóa học thử mới",
				),
			);
		}

		$courseOptions = array_merge($courses, $courseOptions);

		$this->renderJSON($courseOptions);
	}

	public function actionCountSession(){
		if (isset($_REQUEST['course'])){
            $sessionCount = Course::model()->findByPk($_REQUEST['course'])->countCurrentSession();

			$this->renderJSON(array("sessionCount"=>$sessionCount));
		}
	}

	public function actionChangeSchedule(){
		$success = false;
		if (isset($_POST['sessionId']) && isset($_POST['teacher']) && isset($_POST['start'])){
			$session = Session::model()->findByPk($_POST['sessionId']);
			$students = $session->assignedStudents();
			if (!empty($students)){
				$existingSession = Session::model()->findStudentExistingSession($students, $_POST['start'], false)['existingSession'];
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
