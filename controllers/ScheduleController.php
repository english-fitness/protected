<?php

class ScheduleController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'vi';//Config admin language is Vietnamese
    }
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$query = "SELECT COUNT(*) FROM tbl_teacher";
		$teacherCount = Yii::app()->db->createCommand($query)->queryColumn()[0];
		$pageCount = ceil($teacherCount / 12);
		
		if (isset($_REQUEST['page'])){
			$page = $_REQUEST['page'];
		} else {
			$page = 1;
		}
		
		$query = "SELECT id FROM tbl_user WHERE role = '" . User::ROLE_TEACHER . "' AND status = " . User::STATUS_OFFICIAL_USER . "  LIMIT 12 OFFSET " . ($page - 1) * 12;
		$result = Yii::app()->db->createCommand($query)->queryColumn();
		
		$this->layout = '//layouts/blank';
		$this->render('schedule', array("teachers"=>json_encode($result), "pageCount"=>$pageCount, "page"=>$page));
	}
	
	public function actionGetSessions(){
		$teacherIds = json_decode($_REQUEST["teachers"]);
		
		$query = "SELECT * FROM tbl_session " . 
				 "WHERE teacher_id IN (" . implode(', ',$teacherIds) . ") " .
				 "AND plan_start BETWEEN '" . date('Y-m-d', strtotime('monday this week')) . "' AND '" . date ('Y-m-d', strtotime('sunday this week')) . "'";
		$sessions = Session::model()->findAllBySql($query);
		$sessionDays = array();
		if (!empty($sessions)){
			foreach ($sessions as $session){
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
				
				$title = array_pop($session->getAssignedStudentsArrs());
				
				$sessionDays[] = array(
                    'id' => $session->id,
                    'title' => (($title != '') ? $title : $session->subject),
                    'content'=>$session->content,
                    'start' => $session->plan_start,
                    'end'=> date("Y-m-d H:i:s",strtotime($session->plan_start) + $session->plan_duration*60),
                    'allDay'=> false,
					'backgroundColor'=>$backgroundColor,
					'resources'=> $session->teacher_id,
					'teacher'=> $session->teacher_id,
                );
			}
		}
		
		$query = "SELECT * FROM tbl_teacher_timeslots " . 
				 "WHERE teacher_id IN (" . implode(', ', $teacherIds) . ") AND week_start = '" . date('Y-m-d', strtotime('monday this week')) . "'";
		$result = Yii::app()->db->createCommand($query)->queryAll();
		
		$tempTeachers = User::model()->findAllBySql('SELECT id, firstname, lastname, profile_picture FROM tbl_user WHERE id IN (' . implode(', ', $teacherIds) . ")");
		
		$teachers = array();
		
		foreach($tempTeachers as $teacher){
			$teachers[] = array(
				"id"=>$teacher->id,
				"name"=> "<img src=". Yii::app()->user->getProfilePicture($teacher->id) . " style='margin:3px;width:80%;height:80%'></img><br>" . $teacher->fullname(),
			);
		}
		
		$availableSlots = array();
		foreach ($result as $item){
			$availableSlots[] = array("teacher"=>$item["teacher_id"], "weekStart"=>$item["week_start"], "timeslots"=>$item["timeslots"]);
		}
		
		$this->renderJSON(array("teachers"=>$teachers,"sessions"=>$sessionDays, "availableSlots"=>$availableSlots));
	}
}
