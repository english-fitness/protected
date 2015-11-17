<?php
class ClsNotification
{
	/**
	 * Send to user array
	 */
    public function sendToUsersArr($session)
    {
        $userId = Yii::app()->user->id;
        $students = $session->getAssignedStudentsArrs();
        foreach($students as $k=>$v){
            $user[] = $k;
        }
        $user[] = $session->getTeacher();
        unset($user[$userId]);
        return $user;
    }
    
    /**
     * Send to student edit profile session
     */
    public function sendToStudentEditProfileSession($session_id)
    {
        $session = Session::model()->findByPk($session_id);
        $usersArr = $this->sendToUsersArr($session);
        if(count($usersArr)>0){
            foreach($usersArr as $key){
                $notification  = new Notification();
                $link  = Yii::app()->baseurl."/student/class/session/id/$session->id";
                $link = "<a href='".$link."'>$session->subject</a>";
                $content = array(
                    "user_id_post" => Yii::app()->user->id,
                    "content"=>"Vừa chỉnh sửa thông tin  $link  "
                );
                $notification->content =json_encode($content);
                $notification->receiver_id = $key;
                $notification->notification_type = 1;
                $notification->save();
            }

        }
    }
    
    /**
     * Send to teacher edit profile session
     */
    public function sendToTeacherEditProfileSession($session_id)
    {
        $session = Session::model()->findByPk($session_id);
        $teacher = $session->getTeacher();
        if($teacher){
            $notification  = new Notification();
            $link  = Yii::app()->baseurl."/teacher/class/session/id/$session->id";
            $link = "<a href='".$link."'>$session->subject</a>";
            $content = array(
                "user_id_post" => Yii::app()->user->id,
                "content"=>"Vừa chỉnh sửa thông tin  $link  "
            );
            $notification->content =json_encode($content);
            $notification->receiver_id = $session->teacher_id;
            $notification->notification_type = 1;
            $notification->save();
        }
    }
    
    /*
     * record notices sent to teacher when students edit session
     */
    public function sendToTeacherEditSession($session_id)
    {
        $session = Session::model()->findByPk($session_id);
        $teacher = $session->getTeacher();
        if($teacher){
            $notification  = new Notification();
            $link  = Yii::app()->baseurl."/teacher/class/session/id/$session->id";
            $link = "<a href='".$link."'>$session->subject</a>";
            $content = array(
                "user_id_post" => Yii::app()->user->id,
                "content"=>"Vừa chỉnh sửa thời gian  $link  về ".date('H:i:s, d-m-Y',strtotime($session->plan_start))
            );
            $notification->content =json_encode($content);
            $notification->receiver_id = $session->teacher_id;
            $notification->notification_type = 1;
            $notification->save();
        }
    }
    
    /*
     * record notices sent to teacher when students move course
     */
    public function sendToTeacherMoveSession($course_id){
        $course = Course::model()->findByPk($course_id);
        $teacher = $course->getTeacher();
        if($teacher){
            $notification  = new Notification();
            $link = Yii::app()->baseurl."/teacher/class/course/id/$course->id";
            $link = '<a href="'.$link.'">'.$course->title.'</a>';
            $content = array(
                "user_id_post" => Yii::app()->user->id,
                "content"=>"Vừa dời khóa học  $link "
            );
            $notification->content =json_encode($content);
            $notification->receiver_id = $course->teacher_id;
            $notification->notification_type = 1;
            $notification->save();

        }
    }
    
    /**
     * Count number of not activated user
     */
    public function countNotActivatedUser($role='role_student', $fromDate='2014-04-30')
    {
    	$criteria=new CDbCriteria;
    	$criteria->compare('status',User::STATUS_PENDING);
    	$criteria->compare('role',$role,true);
    	$criteria->compare('deleted_flag',0);
    	if($fromDate!=NULL && $fromDate!=false){
    		$criteria->addCondition("created_date>='".$fromDate."'");
    	}
    	$count = User::model()->count($criteria);
    	return $count;
    }
    
    /**
     * Count number of pending preregister user
     */
    public function countPendingPreregisterUser()
    {
    	$attributes = array('care_status'=>PreregisterUser::CARE_STATUS_L0, 'deleted_flag'=>0);
    	$count = PreregisterUser::model()->countByAttributes($attributes);
    	return $count;
    }
    
	/**
     * Count number of pending course
     */
    public function countPendingCourse()
    {
    	$count = Course::model()->countByAttributes(array('status'=>Course::STATUS_PENDING, 'deleted_flag'=>0));
    	return $count;
    }
    
	/**
     * Count number of pending nearest session
     */
    public function countPendingNearestSession($reminder=false, $inComingDays=7)
    {
    	$criteria=new CDbCriteria;
    	if(!$reminder){//Show only nearest sessions
		    $planTo = date('Y-m-d H:i:s', time('now')+$inComingDays*86400);
		    $criteria->compare('plan_start',"<=$planTo",true);
    	}
	    $criteria->compare('status',Session::STATUS_PENDING);
	    $criteria->compare('deleted_flag',0);//Count not deleted
		$planFrom = date('Y-m-d H:i:s', time('now')-30*60);
		$criteria->compare('DATE_ADD(plan_start,INTERVAL plan_duration MINUTE)',">=$planFrom",true);
		$criteria->compare('(SELECT status FROM tbl_course WHERE id=course_id)',"<>".Course::STATUS_PENDING,true);
    	$pendingNearestSession = Session::model()->findAll($criteria);
    	return count($pendingNearestSession);
    }
    
	/**
     * Count number of pending request course
     */
    public function countPendingCourseRequest()
    {
    	$count = PreregisterCourse::model()->countByAttributes(array('status'=>PreregisterCourse::STATUS_PENDING, 'deleted_flag'=>0));
    	return $count;
    }
    
	/**
     * Count number of pending preset course
     */
    public function countPendingPresetCourse()
    {
    	$count = PresetCourse::model()->countByAttributes(array('status'=>PresetCourse::STATUS_PENDING, 'deleted_flag'=>0));
    	return $count;
    }
    
    /**
     * Display profile notification update
     */
    public function enoughProfile($userId)
    {
    	$user = User::model()->findByPk($userId);
    	if(isset($user->id)){
    		if(trim($user->birthday)=="" || $user->birthday==NULL || trim($user->phone)==""){
    			return false;
    		}elseif(trim($user->phone)!=""){
    			$validPhoneNumber = Common::validatePhoneNumber($user->phone);
    			if(!$validPhoneNumber) return false;
    		}
    		// if($user->role==User::ROLE_STUDENT){
    			// $student = Student::model()->findByPk($userId);
    			// if(isset($student->user_id) && $student->class_id==NULL){
    				// return false;
    			// }
    		// }
    	}
    	return true;
    }

}