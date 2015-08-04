<?php

class SessionCommentController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView()
	{
		$this->subPageTitle = 'Đánh giá buổi học';
		if (isset($_REQUEST['sessionId'])){
			$sessionId = $_REQUEST['sessionId'];
			$session = Session::model()->findByPk($sessionId);
			
			$query = "SELECT * FROM tbl_session_comment ".
					 "WHERE session_id = " . $sessionId . " " .
					 "AND user_id = " . $session->teacher_id;
			
			$teacherComment = SessionComment::model()->findBySql($query);
			
			$query = "SELECT c.* FROM tbl_session_comment c JOIN tbl_session_student s " .
					 "ON c.session_id = s.session_id " .
					 "AND c.user_id = s.student_id " .
					 "WHERE c.session_id = " . $sessionId;
			
			$studentComment = SessionComment::model()->findAllBySql($query);
			
			$this->render('view', array(
				'session'=>$session,
				'teacherComment'=>$teacherComment,
				'studentComment'=>$studentComment,
			));
		}
	}
}
