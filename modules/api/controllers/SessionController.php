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
                'actions'=>array('start','end', 'getFeedbackUrls', 'kickUser'),
                'users'=>array('*'),
            ),
            array('deny',),
        );
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
}
