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
                'actions'=>array('index', 'courseView', 'sessionView', 'update'),
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
		
		$this->render('index', array(
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
		
		$sessions = SessionNote::getSessionNote($_GET['cid'], $using_platform, $ended);
		$course = Course::model()->findByPk($_GET['cid']);
		
		$this->render('sessionView', array(
			"sessions"=>$sessions,
			"course"=>$course,
		));
	}
	
	public function actionUpdate($id){
		$sessionNote = SessionNote::model()->findByPk($id);
		if ($sessionNote == null){
			$sessionNote = new SessionNote;
			$sessionNote->session_id = $id;
		}
		
		$success = false;
		if (isset($_POST['SessionNote'])){
			$sessionNote->attributes = $_POST['SessionNote'];
			if ($sessionNote->save()){
				$success = true;
			}
		}
		
		$this->renderJSON(array("success"=>$success));
	}
	
	public function actionUpdateUseForm($id){
		$this->subPageTitle = "Sửa ghi chú";
		
		$sessionNote = SessionNote::model()->findByPk($id);
		if ($sessionNote == null){
			$sessionNote = new SessionNote;
		}
		if (isset($_POST['SessionNote'])){
			if ($sessionNote != null){
				$sessionNote->attributes = $_POST['SessionNote'];
				if ($sessionNote->save()){
					$this->redirect("/admin/sessionMonitor/sessionView?cid=" . $_REQUEST['cid']);
				}
			} else {
				throw new CHttpException(404,'The requested page does not exist.');
			}
		}
		
		$this->render('update', array(
			'model'=>$sessionNote,
			'course_id'=>$_REQUEST['cid'],
		));
	}
}
