<?php

class ScheduleController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'en';//Config admin language is Vietnamese
    }
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Schedule';
        $this->layout = '//layouts/blank';
		
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
			//get teacher id
			//render the day view page
			//do some pagination
			
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
					 "AND status = " . User::STATUS_OFFICIAL_USER . " " .
					 "LIMIT 12 OFFSET " . (($page - 1) * 12);
			$teachers = Yii::app()->db->createCommand($query)->queryColumn();
            
			$this->render('schedule', array(
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

			if (isset($_GET['year'])){
				$year = $_GET['year'];
			} else {
				$year = date('Y');
			}
			
			$monthStartTimestamp = strtotime($year.'-'.$month.'-01');
			$monthEndTimestamp = strtotime(date('Y-m-t', $monthStartTimestamp));
			$monthStartWday = date('w', $monthStartTimestamp);
			if ($monthStartWday == 1){
				$start = date('Y-m-d', $monthStartTimestamp);
			} else {
				$start = date('Y-m-d', strtotime('next monday -1 week', $monthStartTimestamp));
			}
			$end = date('Y-m-d', strtotime('next monday', $monthEndTimestamp));
		} else {
			//too lazy to write handling code for non-monday week_start
			if (isset($_REQUEST['week_start']) && date ('w', strtotime($_REQUEST['week_start'])) == 1){
				$weekStartTimestamp = strtotime($_REQUEST['week_start']);
				$start = date('Y-m-d', $weekStartTimestamp);
				$end = date('Y-m-d', strtotime('+7 days', $weekStartTimestamp));
			} else {
				$start = date('Y-m-d', strtotime('monday this week'));
				$end = date('Y-m-d', strtotime('monday next week'));
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
                    'start' => $session->plan_start,
                    'end'=> date("Y-m-d H:i:s",strtotime($session->plan_start) + $session->plan_duration*60),
					'backgroundColor'=>$backgroundColor,
					'resources'=> $session->teacher_id,
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
					Yii::app()->baseUrl . "/schedule?teacher=" . $teacher->id ,
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
    
    public function actionAjaxSearchTeacher($keyword){
		$teacherAttributes = User::model()->searchUsersToAssign($keyword, 'role_teacher');
		$this->renderJSON(array("result"=>$teacherAttributes));
	}
}
