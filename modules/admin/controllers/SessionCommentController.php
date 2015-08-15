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
				'actions'=>array('index','view','send', 'sendToStudents'),
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
			
            $comments = SessionComment::findBySession($session);
			
			$this->render('view', array(
				'session'=>$session,
				'teacherComment'=>$comments['teacherComments'],
				'studentComment'=>$comments['studentComments'],
			));
		} else {
            throw new CHttpException(400, 'Bad request');
        }
	}
    
    public function actionSend(){
        $this->subPageTitle = 'Gửi nhận xét';
        if (isset($_REQUEST['sessionId'])){
            $sessionId = $_REQUEST['sessionId'];
            $session = Session::model()->findByPk($sessionId);
            
            $students = $session->assignedStudents(true);
            $comments = SessionComment::findBySession($session);
            
            $this->render('send', array(
                'session'=>$session,
                'students'=>$students,
                'teacherComment'=>$comments['teacherComments'],
				'studentComment'=>$comments['studentComments'],
            ));
        } else {
            throw new CHttpException(400, 'Bad request');
        }
    }
    
    public function actionSendToStudents(){
        $studentIds = $_POST['students'];
        $students = User::model()->findAllByAttributes(array("id"=>$studentIds));
        $content = nl2br($_POST['content']);
        $translation = nl2br($_POST['translation']);
        $date = $_POST['date'];
        $time = $_POST['time'];
        
        $mailer = new ClsMailer;
        
        if ($mailer->sendSessionReminder($students, $content, $translation, $date, $time)){
            $this->renderJSON(array(
                "success"=>true,
            ));
        } else {
            $this->renderJSON(array(
                "success"=>false,
            ));
        }
    }
}
