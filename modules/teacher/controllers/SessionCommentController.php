<?php

class SessionCommentController extends Controller
{
    public function init()
    {
        if(Yii::app()->user->isGuest)
            $this->redirect("/");
    }
    
    public function actionView(){
		$this->subPageTitle = "View Reminders";
		if (isset($_REQUEST['sessionId'])){
			$sessionId = $_REQUEST['sessionId'];
			$session = Session::model()->findByPk($sessionId);
			
			$query = "SELECT * FROM tbl_session_comment ".
					 "WHERE session_id = " . $sessionId . " " .
					 "AND user_id = " . $session->teacher_id;
			
			$teacherComment = SessionComment::model()->findBySql($query);
			
			$this->render('view', array(
				'session'=>$session,
				'teacherComment'=>$teacherComment,
			));
		} else {
			throw new CHttpException(400,'Bad request');
		}
	}
	
	public function actionUpdate(){
		$this->subPageTitle = "Edit Reminders";
		if (isset($_REQUEST['sessionId'])){
			$sessionId = $_REQUEST['sessionId'];
			$session = Session::model()->findByPk($sessionId);
			
			$query = "SELECT * FROM tbl_session_comment ".
					 "WHERE session_id = " . $sessionId . " " .
					 "AND user_id = " . $session->teacher_id;
			
			$teacherComment = SessionComment::model()->findBySql($query);
			
			if ($teacherComment == null){
				$teacherComment = new SessionComment;
				$teacherComment->session_id = $sessionId;
				$teacherComment->created_date = date('Y-m-d H:i:s');
			}
			
			if (isset($_POST['SessionComment'])){
				$teacherComment->attributes = $_POST['SessionComment'];
				if ($teacherComment->save()){
					$this->redirect("/teacher/class/endedSession");
				} else {
					exit(var_dump($teacherComment->getErrors()));
				}
			}
			
			$this->render('update', array(
				'session'=>$session,
				'teacherComment'=>$teacherComment,
			));
		} else {
			throw new CHttpException(400,'Bad request');
		}
	}
    
    public function actionUnfilled(){
        $this->subPageTitle = "Unfilled Reminders";
        $sessions = SessionComment::getUnfilledReminders(Yii::app()->user->id);
        $this->render('unfilled', $sessions);
    }
}
?>