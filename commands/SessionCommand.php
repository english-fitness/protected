<?php

class SessionCommand extends CConsoleCommand
{
    public function actionUpdateBoard()
    {
        $now = new DateTime();
        $nextHour = $now->add(new DateInterval('PT48H'));
        $criteria=new CDbCriteria();
        $criteria->condition = 'status='.Session::STATUS_APPROVED;
        $criteria->addCondition("plan_start<'".$nextHour->format('Y-m-d H:i:s')."'");
        $criteria->addCondition("whiteboard is NULL");

        $sessions = Session::Model()->findAll($criteria);

        foreach($sessions as $session) {
        	$trial = ($session->type==Session::TYPE_SESSION_PRESET)? true: false;
            Yii::app()->board->createBoard($session->id, $trial);
        }
    }

    public function actionRemoveBoard()
    {
        // 2 months
        $prevTime = date('Y-m-d H:i:s', time('now')-3600*24*60);
        $criteria = new CDbCriteria();
        $condition = "(DATE_ADD(plan_start,INTERVAL plan_duration MINUTE)<'".$prevTime."')";
        $condition .= " AND whiteboard IS NOT NULL ";
        $criteria->addCondition($condition);

        $sessions = Session::Model()->findAll($criteria);

        foreach($sessions as $session) {
            Yii::app()->board->removeBoard($session->whiteboard);
            $session->whiteboard = NULL;
            $session->save();
            echo $session->id.', ';
        }
    }
	//End session command
    public function actionEndSession()
    {
        //After plan end 1 hour
        $prevTime = date('Y-m-d H:i:s', time('now')-3600);
        $criteria = new CDbCriteria();
        $condition = "(DATE_ADD(plan_start,INTERVAL plan_duration MINUTE)<'".$prevTime."')";
        $condition .= " AND status<>'".Session::STATUS_ENDED."' AND status<>'".Session::STATUS_CANCELED."' AND deleted_flag=0";
        $criteria->addCondition($condition);

        $sessions = Session::Model()->findAll($criteria);

        foreach($sessions as $session) {
            if($session->status==Session::STATUS_PENDING || $session->status==Session::STATUS_APPROVED) {
                $session->deleted_flag = 1;
            } else if($session->status==Session::STATUS_WORKING) {
            	$session->status = session::STATUS_ENDED;
            }
            $session->save();
            echo $session->id.':'.$session->status.', ';
        }
    }

    //End training & update status of student
    public function actionEndTraining()
    {
    	$criteria = new CDbCriteria();
    	$criteria->addCondition("status=".Course::STATUS_APPROVED." OR status=".Course::STATUS_WORKING);
    	$criteria->addCondition("type=".Course::TYPE_COURSE_TRAINING);
    	$trainingCourses = Course::model()->findAll($criteria);
    	if($trainingCourses){
        	$prevDay = date('Y-m-d H:i:s', time('now')-86400);
    		foreach($trainingCourses as $course){
    			$lastPlanStart = $course->getFirstDateInList("DESC", "Y-m-d H:i:s");
    			if($lastPlanStart<$prevDay){
    				$assignedStudentIds = $course->assignedStudents();
    				if(count($assignedStudentIds)>0){
    					foreach($assignedStudentIds as $studentId){
    						$user = User::model()->findByPk($studentId);
    						if($user && $user->status==User::STATUS_TRAINING_SESSION){
    							$user->status = User::STATUS_ENDED_TRAINING;
    							$user->save();//Update user to ended training
    							echo 'uid:'.$user->id.', ';
    						}
    					}
    				}
    				$course->status = Course::STATUS_ENDED;
    				$course->save();//End training course
    				echo "courseId: ".$course->id.'; ';
    			}
    		}
    	}
    }

}