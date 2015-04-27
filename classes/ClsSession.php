<?php
class ClsSession
{
    /**
     * Get nearest sessions of Student
     * @param $userId, $type="student" or "teacher"
     */
    public function getNearestSessions($userId, $type="student", $limit=5)
    {
        $criteria = new CDbCriteria;
        $nextWeekTime = date('Y-m-d H:i:s', time('now')+7*86400);
        //Plan end will >= time now -(prev 30p)
        $checkDisplayTime = date('Y-m-d H:i:s', time('now')-30*60);
        $criteria->select = 't.*'; $condition = "";//Select fields & condition
        if($type=="student"){
	        $criteria->join = "INNER JOIN tbl_session_student ON t.id= tbl_session_student.session_id";
	        $condition = "(student_id = $userId) AND ";//Student id condition
        }elseif($type=="teacher"){
        	$condition = "(teacher_id = $userId) AND ";//Teacher id condition
        }
        $condition .= "(DATE_ADD(plan_start,INTERVAL plan_duration MINUTE)>='".$checkDisplayTime."') AND (plan_start<='".$nextWeekTime."')";
        $condition .= " AND (status=".Session::STATUS_APPROVED." OR status=".Session::STATUS_WORKING.") AND deleted_flag=0";
        $criteria->condition = $condition;
        $criteria->limit = $limit;
        $criteria->order = "plan_start ASC";
        $nearestSessions = Session::model()->findAll($criteria);
        return $nearestSessions;
    }
    
    /**
     * Check update time for all future session of course
     */
    public function checkAndUpdateCalendarSessions($courseId, $modifyDay=1)
    {
    	$criteria = new CDbCriteria();
    	$criteria->condition = "(course_id = $courseId) AND (plan_start>'".date('Y-m-d H:i:s')."')";
    	$criteria->order = ($modifyDay==1)? "plan_start DESC": "plan_start ASC";
		$futureSessions = Session::model()->findAll($criteria);
		$sessionPlanStarts = array();//Session plan start desc or asc
		if(count($futureSessions)>0){
			$markLastTimeInList = strtotime($futureSessions[0]->plan_start);//Last time session in list(desc or asc)
			$markDayOfWeek = date('l', strtotime($futureSessions[count($futureSessions)-1]->plan_start));//Day in week of first plan start
			if($modifyDay==1){//Up to future one session
				$nextCycleDay = date('Y-m-d', strtotime('next '.$markDayOfWeek, $markLastTimeInList));
				$firstPlanStartWillChange = $nextCycleDay.' '.date('H:i:s', $markLastTimeInList);
			}elseif($modifyDay==-1){//Down to current time one session
				$prevCycleDay = date('Y-m-d', strtotime('last '.$markDayOfWeek, $markLastTimeInList));
				$firstPlanStartWillChange = $prevCycleDay.' '.date('H:i:s', $markLastTimeInList);
			}
			if($firstPlanStartWillChange>=date('Y-m-d H:i:s')){
				foreach($futureSessions as $key=>$session){
					$sessionPlanStarts[$key] = $session->plan_start;
					if($key==0){//Last(DESC) or Fisrt(ASC) session in course
						$session->plan_start = $firstPlanStartWillChange;
					}else{
						$session->plan_start = $sessionPlanStarts[$key-1];
					}
					$session->status = Session::STATUS_PENDING;//Pending status
					$session->save();//Save plan start session
				}
				return true;
			}
		}
    	return false;
    }
    
	/**
	 * Display enter class button in session page
	 */
	public static function displayEnterBoardButton($whiteboard, $checkBrowser=true)
	{
		$validBrowserVersion = Yii::app()->session['validBrowserVersion'];
		if(!isset($validBrowserVersion)){
			$validBrowserVersion = TestConditions::app()->validBrowserVersion();
			Yii::app()->session['validBrowserVersion'] = $validBrowserVersion;
		}
		Yii::app()->controller->renderPartial("student.views.widgets.enterBoard",array(
			'whiteboard'=>$whiteboard,
			'checkBrowser'=>$checkBrowser,
			'validBrowserVersion'=>$validBrowserVersion,
		 ));
	}

}
?>