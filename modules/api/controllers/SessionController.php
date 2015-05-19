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
                'actions'=>array('start','end', 'getFeedbackUrls', 'kickUser', 'getSettings', 'setRecordFile'),
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
        $session =  Session::model()->findByPk($sessionId);

        if($session && $session->actual_start) {
            $session->actual_end = date('Y-m-d H:i:s');
            if($actualDuration == 0) {
                $start= strtotime($session->actual_start);
                $end = strtotime($session->actual_end);
                $duration = $end - $start;
                $actualDuration = $duration / 60; // minutes
            }

            // $session->status = Session::STATUS_ENDED;
            $session->actual_duration = $actualDuration;
            $session->save();
            $this->renderJSON(array('success'=>true));
        } else {
            $this->renderJSON(array('success'=>false));
        }
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
		if (!$record_dir)
			$record_dir = "/home/administrator/records/";
		$old_url = $record_dir . $record_file;
		
		if (!file_exists($old_url)) return;
		
		$new_dir = $session_id;
		if (!file_exists($record_dir . $new_dir))
			mkdir($record_dir . $new_dir);
		
		$date = date("D M d, Y G:i:s");
		$extension = pathinfo($record_file, PATHINFO_EXTENSION);
		if ($extension) {
			$real_file_name = $session_id . "_" . $date . "." . $extension;
		}
		else {
			$real_file_name = $session_id . "_" . $date;
		}
		
		$model = new SessionRecord();
		$model->attributes = array('session_id'=>$session_id, 'record_file'=>$real_file_name);
		
		$new_url = $record_dir . $new_dir . "/" . $real_file_name;
		if (rename($old_url, $new_url))
		{
			$model->save();
		}
	}
}
