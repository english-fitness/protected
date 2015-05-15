<?php

class SessionController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout='//layouts/column2';

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
                'actions'=>array('index','view','ajaxCreateBoard', 'ajaxApprove', 'ajaxEditInline', 'nearest', 'ended', 'recorded'
                , 'ajaxDeleteBoard','unassignStudent','canceled','active','create','update', 'cancel', 'reminder', 'getSettings'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('admin','delete'),
                'users'=>array('*'),
                'expression' => 'Yii::app()->user->isAdmin()',
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
    public function actionView($id)
    {
        $this->subPageTitle = 'Thông tin buổi học';
        $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->subPageTitle = 'Tạo mới buổi học';
        $model = new Session;
        $modelCourse = new Course;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if(isset($_REQUEST['cid'])){
            $modelCourse = Course::model()->findByPk($_REQUEST['cid']);
        }
        if(isset($_POST['Session']))
        {
            $model->attributes = $_POST['Session'];
            $model->teacher_id = $modelCourse->teacher_id;
            $model->plan_start .= ' '.$_POST['startHour'].':'.$_POST['startMin'].':00';
            $model->status = Session::STATUS_PENDING;//Pending new session
            $model->type = isset($modelCourse->type)? $modelCourse->type: Session::TYPE_SESSION_NORMAL;
            if($model->save()){
                //Get assigned students of Course
                $assignedStudentIds = $modelCourse->assignedStudents();
                //Assign course's students to session
                $model->assignStudentsToSession($assignedStudentIds);
                $this->redirect(array('/admin/session?course_id='.$modelCourse->id));
            }
        }

        $this->render('create',array(
            'model'=>$model,
            'modelCourse'=>$modelCourse,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $this->subPageTitle = 'Sửa thông tin buổi học';
        $model=$this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        $modelCourse = Course::model()->findByPk($model->course_id);
        $availableTeachers = Teacher::model()->availableTeachers($modelCourse->subject_id);//Available teachers
        $assignedStudentIds = $model->assignedStudents();//Assigned students of Course
        if(count($assignedStudentIds)==0) $assignedStudentIds = array(0);
        //Available Student in session
        $availableStudents = User::model()->findAll(array("condition"=>"id IN (".implode(",", $assignedStudentIds).")"));
        if(isset($_POST['Session']))
        {
            $model->attributes=$_POST['Session'];
            if(isset($_POST['startHour']) && isset($_POST['startMin'])){
                $model->plan_start .= ' '.$_POST['startHour'].':'.$_POST['startMin'].':00';
            }
            if($model->save()){
                //Assign new students to session
                $extraUserIds = Yii::app()->request->getPost('extraUserIds', array());
                if(count($extraUserIds)>0){
                    //New assign students(compare to assigned student before)
                    $newAssignStudentIds = array_diff($extraUserIds, $assignedStudentIds);//New StudentIds
                    $model->assignStudentsToSession($newAssignStudentIds);
                }
                Yii::app()->board->updateBoard($model);
                $this->redirect(array('/admin/session?course_id='.$modelCourse->id));
            }
        }

        $this->render('update',array(
            'model'=>$model,
            'modelCourse'=>$modelCourse,
            'availableTeachers'=>$availableTeachers,
            'availableStudents'=>$availableStudents,
        ));
    }

    /**
     * Cancel session and create new pending session
     */
    public function actionCancel($id)
    {
        $this->subPageTitle = 'Báo hủy buổi học';
        $model = $this->loadModel($id);
    	if($model->status!=Session::STATUS_APPROVED){
           	$error = array('code'=>403, 'message'=>'Bạn không có quyền để thực hiện hành động này');
           	$this->render('//site/error', $error); die();
        }
        if(isset($_POST['Session']))
        {
        	$model->attributes = $_POST['Session'];
        	$model->deleted_flag = 0;//Undo trash session
            if($model->save()){
            	if(trim($model->whiteboard)!=""){
                	Yii::app()->board->removeBoard($model->whiteboard);//Delete board
            	}
            	if(isset($_POST['chkAddNewSession']) && $_POST['chkAddNewSession']==1){
            		$planStart = NULL;
	            	if(isset($_POST['planStart']['date'])){
	                	$planStart = $_POST['planStart']['date'].' '.$_POST['planStart']['hour'].':'.$_POST['planStart']['min'].':00';
	            	}
            		$model->addSessionEndOfCourse($planStart);//Add extra pending session at the end of course
            	}
                $this->redirect(array('/admin/session?course_id='.$model->course_id));
            }
        }

        $this->render('cancel',array(
            'model'=>$model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $this->subPageTitle = 'Hủy/Xóa buổi học';
        $model = $this->loadModel($id);//Load model
        $course_id = $model->course_id;
        $whiteboard = $model->whiteboard;
        if($model->status==Session::STATUS_PENDING && $model->deleted_flag==0){
            $model->deleted_flag = 1;//Set deleted flag before delete
            $model->save();
            $this->redirect(array('/admin/session/update/id/'.$model->id));
        }elseif($model->deleted_flag==1){
            $model->deleteAssignedStudents();//Delete all assigned student in this session
            $model->delete();//Delete this session
            if(trim($whiteboard)!=""){
                try {
                    Yii::app()->board->removeBoard($model->whiteboard);//Delete board
                }catch(Exception $e){
                    //Display error message here
                }
            }
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if(!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/session?course_id='.$course_id));
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $this->subPageTitle = 'Danh sách buổi học';
        $this->loadJQuery = false;//Not load jquery
        $model=new Session('search(null, "plan_start asc")');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
        }
        $course = NULL;
        if(isset($_GET['course_id'])){
            $model->course_id = $_GET['course_id'];
            $course = Course::model()->findByPk($model->course_id);
        	if(isset($_GET['Session']['plan_start'])){
				$model->plan_start = Common::convertDateFilter($_GET['Session']['plan_start']);//Created date filter
			}
        }
        $this->render('index',array(
            'model'=>$model, 'course'=>$course
        ));
    }

    /**
     * Dislay nearest sessions in admin
     */
    public function actionNearest()
    {
        $this->subPageTitle = 'Buổi học gần nhất';
        $this->loadJQuery = false;//Not load jquery
        $model= new Session('searchNearestSession');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
       		if(isset($_GET['Session']['plan_start'])){
				$model->plan_start = Common::convertDateFilter($_GET['Session']['plan_start']);//Created date filter
			}
        }
        $model->getDbCriteria()->addCondition('status<>'.Session::STATUS_CANCELED);
        $this->render('nearest',array(
            'model'=>$model,
        ));
    }

    /**
     * Dislay reminder sessions in admin
     */
    public function actionReminder()
    {
        $this->subPageTitle = 'Những buổi học đang chờ';
        $this->loadJQuery = false;//Not load jquery
        $model = new Session('searchNearestSession(365)');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
       		if(isset($_GET['Session']['plan_start'])){
				$model->plan_start = Common::convertDateFilter($_GET['Session']['plan_start']);//Created date filter
			}
        }
        $model->getDbCriteria()->addCondition('status='.Session::STATUS_PENDING);
        $this->render('reminder',array(
            'model'=>$model,
        ));
    }

	/**
     * Dislay active sessions in admin
     */
    public function actionActive()
    {
        $this->subPageTitle = 'Buổi học đang diễn ra';
        $this->loadJQuery = false;//Not load jquery
        $model= new Session('searchActiveSession');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
        }
        $this->render('active',array(
            'model'=>$model,
        ));
    }

    /**
     * Dislay nearest sessions in admin
     */
    public function actionEnded()
    {
        $this->subPageTitle = 'Buổi học đã kết thúc';
        $this->loadJQuery = false;//Not load jquery
        $model= new Session('search('.Session::STATUS_ENDED.', "plan_start desc")');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
        	if(isset($_GET['Session']['plan_start'])){
				$model->plan_start = Common::convertDateFilter($_GET['Session']['plan_start']);//Created date filter
			}
        }
        $model->deleted_flag = 0;//not deleted session
        $this->render('ended',array(
            'model'=>$model,
        ));
    }
	
	public function actionRecorded()
	{
		$this->subPageTitle  = 'Buổi học được ghi âm';
		$this->loadJQuery = false;
		$model = new Session('searchRecordedSession');
		$model->unsetAttributes();
		if (isset($_GET['Session'])){
			$model->attributes = $_GET['Session'];
		}
		$this->render('recorded', array(
			'model'=>$model,
		));
	}

    /**
     * Dislay nearest sessions in admin
     */
    public function actionCanceled()
    {
        $this->subPageTitle = 'Buổi học đã bị xóa/hủy';
        $this->loadJQuery = false;//Not load jquery
        $model= new Session('search(null, "plan_start desc")');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
        	if(isset($_GET['Session']['plan_start'])){
				$model->plan_start = Common::convertDateFilter($_GET['Session']['plan_start']);//Created date filter
			}
        }
        $model->getDbCriteria()->addCondition('deleted_flag=1 OR status='.Session::STATUS_CANCELED);
        $this->render('deleted',array(
            'model'=>$model,
        ));
    }

    /**
     * Unassign student from Session
     */
    public function actionUnassignStudent()
    {
        $this->subPageTitle = 'Hủy gán học sinh';
        $studentId = Yii::app()->request->getQuery('student_id', NULL);
        $sessionId = Yii::app()->request->getQuery('session_id', NULL);
        $session = $this->loadModel($sessionId);//Load course
        if(isset($session->id) && $studentId!=NULL){
            $session->unassignStudents(array($studentId));
            $this->redirect(array('/admin/session/update/id/'.$session->id));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Session the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model=Session::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Session $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='session-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Ajax create Session whiteboard
     */
    public function actionAjaxCreateBoard()
    {
        $sessionId = Yii::app()->request->getPost('session_id', '');
        $trial = Yii::app()->request->getPost('trial', 0);
        $p2p = Yii::app()->request->getPost('p2p', 0);                         
        $nuve = Yii::app()->request->getPost('nuve', 0);
        $mode = Yii::app()->request->getPost('mode', 0);
        try {
            $session = Yii::app()->board->createBoard($sessionId, $trial, $p2p, $nuve,$mode);
        }catch(Exception $e){
            $session = false;
        }
        $success = $session ? true : false;
        $displayBoard = $session ? ClsAdminHtml::displayBoard($session, true) : '';
        $this->renderJSON(
            array('success'=>$success, 'displayBoard'=>$displayBoard)
        );
    }

    /**
     * Ajax delete Session whiteboard
     */
    public function actionAjaxDeleteBoard()
    {
        $sessionId = Yii::app()->request->getPost('session_id', '');
        $whiteboard = Yii::app()->request->getPost('whiteboard', '');
        $success = true;
        $model = $this->loadModel($sessionId);//Load model
        $displayBoard = ClsAdminHtml::displayBoard($model, true);
        try {
            Yii::app()->board->removeBoard($whiteboard);//Delete board
            $model->whiteboard = NULL;//Set whiteboard to session table
            $model->save();//Save created whiteboard
            $displayBoard = ClsAdminHtml::displayBoard($model, true);
        }catch(Exception $e){
            //Display error message here
            $success = false;
        }
        $this->renderJSON(
            array('success'=>$success,
                'displayBoard'=>$displayBoard,
            )
        );
    }

    /**
     * Ajax Approve course
     */
    public function actionAjaxApprove()
    {
        $session_id = $_REQUEST['session_id'];
        $success = true;//Set success
        $model = $this->loadModel($session_id);//Load model
        $model->status = Session::STATUS_APPROVED;//Set status course approve
        $model->save();//Save status
        $this->renderJSON(array('success'=>$success));
    }

    /**
     * Ajax Edit inline Subject of Session
     */
    public function actionAjaxEditInline()
    {
        $session_id = $_REQUEST['session_id'];
        $success = true;//Set success
        if(trim($_REQUEST['subject'])!=""){
            $model = $this->loadModel($session_id);//Load model
            $model->subject = $_REQUEST['subject'];//Set subject
            $model->save();
        }
        $this->renderJSON(array('success'=>$success));
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
}
