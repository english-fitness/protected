<?php

class ScheduleController extends Controller
{
	public function init(){
		parent::init();
		$baseAssetsUrl = $this->baseAssetsUrl;
		$cs = Yii::app()->getClientScript();
		$cs->registerScriptFile($baseAssetsUrl.'/js/calendar/fullcalendar.min.js');
		$cs->registerScriptFile($baseAssetsUrl.'/js/calendar/calendar.js');
		$cs->registerCssFile($baseAssetsUrl.'/js/calendar/fullcalendar.min.css');
		$cs->registerCssFile($baseAssetsUrl.'/css/calendar.css');
	}
	
    public function actionRegisterSchedule(){
        $this->subPageTitle = 'Register Schedule';
    	$teacherId = Yii::app()->user->id;
		
        if (isset($_POST['week_start']) && isset($_POST['timeslots'])){
			$success = false;
			if (TeacherTimeslots::model()->saveSchedule($teacherId, $_POST['week_start'], $_POST['timeslots'])){
				$success = true;
			}
			$this->renderJSON(array('success'=>$success));
		} else {
			$this->render('registerSchedule');
		}
    }
	
	public function actionGetSchedule(){
		$teacherId = Yii::app()->user->id;
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
	
    public function actionCalendar()
    {
		$this->subPageTitle = 'Calendar View';
        $uid = yii::app()->user->id;
		
		if (isset($_GET['month'])){
			$month = $_GET['month'];
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
			$monthStart = date('Y-m-d', $monthStartTimestamp);
		} else {
			$monthStart = date('Y-m-d', strtotime('next monday -1 week', $monthStartTimestamp));
		}
		$monthEnd = date('Y-m-d', strtotime('next monday', $monthEndTimestamp));
        
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