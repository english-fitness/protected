<?php

class SessionController extends Controller
{

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
            array('allow',
                'actions'=>array('start','end', 'getFeedbackUrls', 'kickUser', 'getSettings', 'setRecordFile',
								'getTimeUntilExpiration', 'addComment'),
                'users'=>array('*'),
            ),
            array('deny',),
        );
    }
	
	public function loadModel($id)
    {
        $model=Session::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    public function actionStart()
    {
        $sessionId = Yii::app()->request->getQuery('sessionId', '');
        $session =  Session::model()->findByPk($sessionId);

        if($session) {
            if(!$session->actual_start) {
                // only store session start first time
                $session->actual_start = date('Y-m-d H:i:s');
            }

            $session->status = Session::STATUS_WORKING;
            $session->save();
            $this->renderJSON(array('success'=>true));
        } else {
            $this->renderJSON(array('success'=>false));
        }
    }

    /**
     * End a session, only end if we started it before.
     * Set duration if nodejs doesn't send it along
     */
    public function actionEnd()
    {
        $sessionId = Yii::app()->request->getQuery('sessionId', '');
        $actualDuration = Yii::app()->request->getQuery('actualDuration', 0);
		$forceEnd = Yii::app()->request->getQuery('forceEnd', false);
        $session =  Session::model()->findByPk($sessionId);

		$success = false;

        if($session && $session->actual_start) {
            $actual_end = date('Y-m-d H:i:s');
            if($actualDuration == 0) {
                $start= strtotime($session->actual_start);
                $end = strtotime($session->actual_end);
                $duration = $end - $start;
                $actualDuration = $duration / 60; // minutes
            }
			
			$plannedEndTime = date("Y-m-d H:i:s",strtotime($session->plan_start)+$session->plan_duration*60);
			$currentTime = date('Y-m-d H:i:s');
			
			if ($actualDuration >= $session->plan_duration || $currentTime >= $plannedEndTime || $forceEnd)
			{
				$session->status = Session::STATUS_ENDED;
				$session->actual_duration = $actualDuration;
				$session->actual_end = $actual_end;
                $session->teacher_paid = true;
				
				if ($session->save()){
					$success = true;
				}
			}
        }
		$this->renderJSON(array('success'=>$success));
    }

    public function actionGetFeedbackUrls()
    {
        $sessionId = Yii::app()->request->getQuery('sessionId', '');
        $session =  Session::model()->findByPk($sessionId);

        if($session) {
            $course = Course::model()->findByPk($session->course_id);
            $data = array('success'=>true, 'student_form_url'=>$course->student_form_url, 'teacher_form_url'=>$course->teacher_form_url);
            $this->renderJSON($data);
        } else {
            $this->renderJSON(array('success'=>false));
        }
    }

    public function actionKickUser()
    {
        $sessionId = Yii::app()->request->getQuery('sessionId', '');
        $userId = Yii::app()->request->getQuery('userId', '');
        $session =  Session::model()->findByPk($sessionId);

        if($session && $userId) {
            $session->unassignStudents(array($userId));
            $this->renderJSON(array('success'=>true));
        } else {
            $this->renderJSON(array('success'=>false));
        }
    }
	
	public function actionGetSettings()
	{
		$session_id = $_REQUEST['id'];
		$model = $this->loadModel($session_id);//Load model
		$record = $model->record;
		$response['settings'] = array(
			'record'=>$record,
		);
		$encoded = json_encode($response);
		header('Content-type: application/json');
		exit($encoded);
	}
	
	public function actionSetRecordFile()
	{
		$session_id = $_REQUEST['id'];
		$record_file = $_REQUEST['file'];
		
		$record_dir = Yii::app()->params['recordDir'];
		if (!isset($record_dir)){
			$record_dir = "/home/administrator/records/";
        }
		$file_url = $record_dir . $record_file;
		
		if (!file_exists($file_url)) return;
		
		$new_dir = $session_id;
		$session_dir = $record_dir . $new_dir;
		if (!file_exists($session_dir))
		{
			mkdir($session_dir);
		}
		
		$date = date("D M d, Y G:i:s");
		//only use mkv for now, don't need extension handling
		
		// $extension = pathinfo($record_file, PATHINFO_EXTENSION);
		// if ($extension) {
			// $real_file_name = $session_id . "_" . $date . "." . $extension;
		// }
		// else {
			// $real_file_name = $session_id . "_" . $date;
		// }
		
		$real_file_name = $session_id . "_" . $date . ".mkv";
		
		$criteria=new CDbCriteria();
		$criteria->condition='session_id = '.$session_id;
		
		$model = SessionRecord::model()->find($criteria);
		
		if ($model == null)
		{
			$model = new SessionRecord();
			$model->attributes = array('session_id'=>$session_id, 'record_file'=>$real_file_name);
			
			$new_url = $session_dir . "/" . $real_file_name;
			if (rename($file_url, $new_url))
			{
				$model->save();
			}
		}
		else
		{
			$oldRecordFile = $session_dir . "/" . $model->record_file . "";
			
			if (file_exists($oldRecordFile))
			{
				echo $record_dir . "resources/paused.mkv";
				if (file_exists($record_dir . "resources/paused.mkv"))
				{
					$command = "mkvmerge -o '" . $session_dir . "/" . $real_file_name . "' '" . $oldRecordFile . "' +" . "'" . $record_dir . "resources/paused.mkv'" . " +'" . $file_url . "'";
					shell_exec($command);
				}
				else
				{				
					$command = "mkvmerge -o '" . $session_dir . "/" . $real_file_name . "' '" . $oldRecordFile . "' +" . "'" . $file_url . "'";
					shell_exec($command);
				}
				
				unlink($oldRecordFile);
				unlink($file_url);
				
				$model->record_file = $real_file_name;
				$model->save();
			}
			else
			{
				$new_url = $session_dir . "/" . $real_file_name;
				echo $new_url;
				$model->record_file = $real_file_name;
				if (rename($file_url, $new_url))
				{
					$model->save();
				}
			}
		}
		
	}
	
	public function actionGetTimeUntilExpiration(){
		$sessionId = $_REQUEST['sessionId'];
		$session = Session::model()->findByPk($sessionId);
		if ($session != null && $session->status != Session::STATUS_ENDED){
			$now = time();
			$sessionEndTime = strtotime($session->plan_start) + $session->plan_duration*60 + 5*60;
			$remainingTime = $sessionEndTime - $now;
		} else {
			$remainingTime = 0;
		}
		$this->renderJSON(array('remainingTime'=>$remainingTime));
	}
	
	public function actionAddComment(){
		$sessionId = $_POST['session_id'];
		$userId = $_POST['user_id'];
		$comment = SessionComment::model()->findByAttributes(array('session_id'=>$sessionId, 'user_id'=>$userId));
		if ($comment == null){
			$comment = new SessionComment;
			$comment->session_id = $sessionId;
			$comment->user_id = $userId;
			$comment->created_date = date('Y-m-d H:i:s');
		}
		if (isset($_POST['rating'])){
			$comment->rating = $_POST['rating'];
		}
		$comment->comment = $_POST['comment'];
		if ($comment->save()){
			$success = true;
		} else {
			$this->renderJSON(array('error'=>$comment->getErrors()));
			// $success = false;
		}
		$this->renderJSON(array('success'=>$success));
	}
}
