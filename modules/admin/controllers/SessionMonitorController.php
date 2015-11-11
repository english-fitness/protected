<?php

class SessionMonitorController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    // public $layout='//layouts/column2';

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
            array('allow',  // allow all users to perform 'index' and 'view' actions
                'actions'=>array('index', 'courseView', 'sessionView', 'student', 'saveSessionNote'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'users'=>array('*'),
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
    
    public function actionIndex(){
        $this->subPageTitle = "Theo dõi buổi học";

        if (isset($_GET['type'])){
        	$this->loadJQuery = false;
            try {
                $sessions = ReportBuilder::getSessionReport($_GET);
                
                $this->render('index', array(
                    'sessions'=>$sessions,
                ));
            } catch (Exception $e){
            	exit($e);
                throw new CHttpException('500', 'Internal Server Error');
            }
        }
        else {
            $this->render('index');
        }
    }
	
	public function actionStudent(){
		$this->subPageTitle = "Theo dõi buổi học";
		$this->loadJQuery = false;
		
		$student = new User;
		$student->unsetAttributes();
		$student->role=User::ROLE_STUDENT;
		$student->getDbCriteria()->order = 'created_date DESC';
		
		if (isset($_GET['User'])){
			$student->attributes = $_GET['User'];
			
			if (isset($_GET['User']['firstname'])){
				$keyword = $_GET['User']['firstname'];
				$student->getDbCriteria()->addCondition("firstname LIKE '%" . $keyword . "%' OR lastname LIKE '%" . $keyword . "%'");
			}
		}
		
		$this->render('student', array(
			'model'=>$student,
		));
	}
	
	public function actionCourseView(){
		$this->subPageTitle = "Các khóa học";
		$this->loadJQuery = false;
		
		$course = new Course;
		$course->unsetAttributes();
		
		if (isset($_GET['Course'])){
			$course->attributes = $_GET['Course'];
		}
		
		$student = User::model()->findByPk($_GET['sid']);
		
		$this->render('courseView', array(
			'model'=>$course,
			'student'=>$student,
		));
	}
	
	public function actionSessionView(){
		$this->subPageTitle = "Các buổi học";
		$this->loadJQuery = false;
		
		if (isset($_GET['using_platform'])){
			$using_platform = $_GET['using_platform'];
		} else {
			$using_platform = null;
		}
		
		if (isset($_GET['ended'])){
			$ended = $_GET['ended'];
		} else {
			$ended = false;
		}
		
		$sessions = SessionNote::getSessionNoteByCourse($_GET['cid'], $using_platform, $ended);
		$course = Course::model()->findByPk($_GET['cid']);
		
		$this->render('sessionView', array(
			"sessions"=>$sessions,
			"course"=>$course,
		));
	}

	public function actionSaveSessionNote($id){
		$session = Session::model()->findByPk($id);
		if ($session == null){
			throw new CHttpException(404, "The requested page could not be found");
		}

		$session->attributes = $_POST["Session"];

		$changed = array($session);

		if (isset($_POST["SessionNote"]["using_platform"]) && $_POST["SessionNote"]["using_platform"] != ""){
			$sessionNote = $session->note;
			if ($sessionNote == null){
				$sessionNote = new SessionNote();
				$sessionNote->session_id = $session->id;
			}
			$sessionNote->attributes = $_POST["SessionNote"];
			$changed[] = $sessionNote;
		}

		if (isset($_POST["TeacherFine"]["points"]) && $_POST["TeacherFine"]["points"] != ""){
			$teacherFine = $session->teacherFine;
			if ($teacherFine == null){
				$teacherFine = new TeacherFine();
				$teacherFine->session_id = $session->id;
				$teacherFine->teacher_id = $session->teacher_id;
			}
			$teacherFine->attributes = $_POST["TeacherFine"];
			if ($teacherFine->isNewRecord){
				$teacherFine->points_to_be_fined = $teacherFine->points;
			}
			$changed[] = $teacherFine;
		}

		$transaction = Yii::app()->db->beginTransaction();

		try {
			foreach ($changed as $model) {
				if (!$model->save()){
					throw new Exception("Error saving model: " . get_class($model) . ". The model error is " . var_dump($model->getErrors()));
				}
			}
			$transaction->commit();
			$this->renderJSON(array("success"=>true));
		} catch (Exception $e) {
			$transaction->rollback();
			$this->renderJSON(array("success"=>false, "error"=>$e->getMessage()));
		}
	}
}
