<?php

class SessionCommentController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }
    

    public function actionView(){
		$this->subPageTitle = "Nhận xét cho buổi học";
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
}

?>