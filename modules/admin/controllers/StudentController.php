<?php

class StudentController extends Controller
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
				'actions'=>array('index','view','changeToTeacher','create','update', 'manage', 'saleUpdate',
								 'courseWidget', 'tuitionWidget', 'studentWidget', 'sendMail', 'ajaxSendMail'),
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
		$this->subPageTitle = 'Thông tin học sinh';
		$model = $this->loadModel($id);
		if(isset($model->id) && $model->role==User::ROLE_TEACHER){
			$this->redirect(array('/admin/teacher/view/id/'.$model->id));
		}elseif(isset($model->id) && $model->role==User::ROLE_STUDENT){
			$studentProfile = Student::model()->findByPk($model->id);
			$this->render('view',array(
				'model'=>$model,
				'studentProfile'=>$studentProfile,
			));
		}else{
			$this->redirect(array('/admin/user/view/id/'.$model->id));
		}
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
        if (!isset($_REQUEST['preregisterId']) && !Yii::app()->user->isAdmin()){
            $this->redirect(array('index'));
        }
        
		$user=new User;
		$student = new Student; 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->subPageTitle = 'Thêm học sinh mới';
        
        if (isset($_REQUEST['preregisterId'])){
            $preregisterUser = PreregisterUser::model()->findByPk($_REQUEST['preregisterId']);
            if ($preregisterUser->hasExistingUser()){
                $student->addError("preregister_id", "Tài khoản học sinh cho đăng ký tư vấn này đã tồn tại");
            }
        }
        
		if(isset($_POST['User']))
		{
			$student_values = $_POST['User'];
			// $student_profile_values = $_POST['Student'];//Student profile values
			$student_values['role'] = User::ROLE_STUDENT;
			if(trim($student_values['birthday'])==''){
				unset($student_values['birthday']);
			}
			$user->attributes= $student_values;
			$user->passwordSave = $student_values['password'];
            if ($user->attributes['status'] >= Student::STATUS_L8A){
                $student->official_start_date = date('Y-m-d');
            }
            
            $transaction = Yii::app()->db->beginTransaction();
            
            try{
                if($user->save()){
                    // $student->attributes = $student_profile_values;
                    if (isset($_REQUEST['preregisterId'])){
                        $student->preregister_id = $_REQUEST['preregisterId'];
                        $preregisterUser->care_status = PreregisterUser::CARE_STATUS_L4;
                        $preregisterUser->save();
                    }
                    $student->user_id = $user->id;
                    $student->attributes = $_POST['Student'];
                    if($student->save()){
                        $transaction->commit();
                        $this->redirect(array('index'));
                    } else {
                        throw new Exception("student_not_saved");
                    }
                } else {
                    throw new Exception("user_not_saved");
                }
            } catch (Exception $e){
                $transaction->rollback();
            }
		}
        
        $params = array(
            'model'=>$user,
			'student'=>$student,
        );
        if(isset($_REQUEST['preregisterId'])){
            $preregisterUser = PreregisterUser::model()->findByPk($_REQUEST['preregisterId']);
            $params['preregisterUser'] = $preregisterUser;
        }
		$this->render('create',$params);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
        $student = Student::model()->with("user")->findByPk($id);
        if ($student == null){
            throw new CHttpException(404,'The requested page does not exist.');
        }
		$user=$student->user;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->subPageTitle = 'Sửa thông tin học sinh';
		if(isset($_POST['User']))
		{
			$studentValues = $_POST['User'];
			// $studentProfileValues = $_POST['Student'];//Student profile values
			if(trim($studentValues['birthday'])==''){
				unset($studentValues['birthday']);
			}
			$changePassStatus = (isset($_POST['changeStatus']) && $_POST['changeStatus']==1)? true: false;
			if($studentValues['password']==""){
				$changePassStatus = false;
				unset($studentValues['password']);//Not save password
			}
            $unofficialUser = ($user->status <= Student::STATUS_L8A);
			$user->attributes = $studentValues;
            if ($unofficialUser && $user->attributes['status'] >= Student::STATUS_L8A){
                $student->official_start_date = date('Y-m-d');
            }
			if($changePassStatus){
				$user->passwordSave = $user->password;
				$user->repeatPassword = $user->passwordSave;
			}
			if($user->save()){
				// $student->attributes = $studentProfileValues;
				$student->user_id = $user->id;
				$student->attributes = $_POST['Student'];
				if($student->save()){
					if (isset($_POST['urlReferrer'])){
	                    $this->redirect($_POST['urlReferrer']);
	                } else {
	                    $this->redirect(array('index'));
	                }
				}
			}
		}

		$this->render('update',array(
			'model'=>$user,
			'student'=>$student,
		));
	}
	
	/**
	 * Updates sale information
	 */
	public function actionSaleUpdate($id)
	{
        $this->subPageTitle = 'Thông tin chăm sóc, tư vấn';
        
        $student = Student::model()->findByPk($id);
        $user = User::model()->findByPk($student->user_id);
        if (isset($student->preregister_id)){
            $preregisterUser = PreregisterUser::model()->findByPk($student->preregister_id);
        }
        if (!isset($preregisterUser) || $preregisterUser == null){
            $preregisterUser = new PreregisterUser;
        }
        $saleHistory = new UserSalesHistory();
        $saleUserHistories = UserSalesHistory::model()->getSaleHistory($id);
        
        $updatePreUser = isset($_POST['PreregisterUser']);
        $updateSaleHistory = isset($_POST['chkAddNewHistory']) && isset($_POST['UserSalesHistory']);
        $updateStudent = isset($_POST['Student']);
        if ($updatePreUser || $updateSaleHistory || $updateStudent){
            $transaction = Yii::app()->db->beginTransaction();
            try {
                if ($updatePreUser){
                    $attributes = $_POST['PreregisterUser'];
                
                    $preregisterUser->attributes = $attributes;
                    if ($preregisterUser->isNewRecord){
                    	$preregisterUser->fullname = $student->user->fullname();
                    	$preregisterUser->phone = $student->user->phone;
                    }
                    
                    if ($preregisterUser->save()){
                        if (isset($student->preregister_id)){
                            $student->preregister_id = $preregisterUser->id;
                            $updateStudent = true;
                        }
                    } else {
                        throw new Exception('Error saving model: PreregisterUser '.var_dump($preregisterUser->getErrors()));
                    }
                    
                    if ($updateStudent){
                        if (isset($_POST['Student'])){
                            $student->attributes = $_POST['Student'];
                        }
                        if (!$student->save()){
                            throw new Exception('Error saving model: Student');
                        }
                    }
                }
                
                if ($updateSaleHistory){
                    $saleHistory->attributes = $_POST['UserSalesHistory'];
                    $saleHistory->user_id = $user->id;
                    if (!$saleHistory->save()){
                        throw new Exception('Error saving model: UserSalesHistory');
                    }
                }
                
                $transaction->commit();
                if (isset($_POST['urlReferrer'])){
                    $this->redirect($_POST['urlReferrer']);
                } else {
                    $this->redirect(array('index'));
                }
            } catch (Exception $e) {
                $transaction->rollback();
                exit($e);
            }
        }
        
        $this->render('saleForm',array(
            'preregisterUser'=>$preregisterUser,
            'user'=>$user,
			'student'=>$student,
            'saleHistory'=>$saleHistory,
			'saleUserHistories' => $saleUserHistories,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->subPageTitle = 'Xóa học sinh';
		$model = $this->loadModel($id);
		if($model->status==Student::STATUS_L5){
			$model->deleted_flag = 1;//Deleted flag
            $student = Student::model()->findByPk($id);
            if ($student != null){
                $student->preregister_id = null;
                $student->save();
            }
			$model->save();
			if (isset($_GET['urlReferrer'])){
                $this->redirect($_GET['urlReferrer']);
            } else {
                $this->redirect(array('/admin/preregisterUser'));
            }
		}
		$this->redirect(array('/admin/student/index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Danh sách học sinh';
		$this->loadJQuery = false;//Not load jquery
		$model = new User();
		$model->unsetAttributes();  // clear any default values
		$studentFilters = Yii::app()->request->getQuery('Student', array());
		if(isset($_GET['User'])){
			$model->attributes=$_GET['User'];
			if(isset($_GET['User']['created_date'])){
				$model->created_date = Common::convertDateFilter($_GET['User']['created_date']);//Created date filter
			}
			if(isset($_GET['User']['birthday'])){
				$model->birthday = Common::convertDateFilter($_GET['User']['birthday']);//Birthday filter
			}
			if(isset($_GET['User']['firstname'])){
				$model->getDbCriteria()->addCondition("CONCAT(`lastname`,' ',`firstname`) LIKE '%".$_GET['User']['firstname']."%'");
			}
			if(isset($_GET['User']['source'])){
				$model->source = $_GET['User']['source'];
			}
		}
		$model->role = User::ROLE_STUDENT;
		$model->deleted_flag = 0;
		$model->getDbCriteria()->order = $model->getTableAlias(false, false).'.created_date DESC';
		if (!Yii::app()->user->isAdmin()){
			$model->getDbCriteria()->addCondition('status <> '.Student::STATUS_TEST);
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Change student to teacher
	 */
	public function actionChangeToTeacher($id)
	{
		$model = $this->loadModel($id);
		if(isset($model->id)){//Change student to teacher
			$model->role = User::ROLE_TEACHER;
			$model->save();
		}
		//Create teacher profile if not existed
		$teacher = Teacher::model()->findByPk($id);
		if(!isset($teacher->user_id)){
			$modelTeacher = new Teacher();
			$modelTeacher->attributes = array(
				'user_id' => $id,
				'title' => 'Chuyển từ học sinh thành giáo viên!',
			);
			$modelTeacher->save();
		}
		$this->redirect(array('/admin/teacher/update/id/'.$id));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id, $with=array())
	{
		if (count($with) > 0){
			$model=User::model()->with($with)->findByPk($id);
		} else {
			$model=User::model()->findByPk($id);
		}
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionManage(){
		$this->subPageTitle = "Quản lý học sinh";

		if (isset($_REQUEST['sid'])){
			$sid = $_REQUEST['sid'];

			$this->render('manage', array(
				'sid'=>$sid,
			));
		}
	}

	public function actionCourseWidget($sid){
		$this->layout = '//layouts/widget';
		$course = new Course;
		$course->unsetAttributes();

		$assignedCourses = Student::model()->assignedCourses($sid);
		if(count($assignedCourses)==0) $assignedCourses = array(0);
		$course->id = $assignedCourses;

		if (isset($_GET["type"])){
			$course->type = $_GET["type"];
		} else {
			$course->type = Course::TYPE_COURSE_NORMAL;
		}

		$course = $course->search("created_date DESC");
		$course->pagination = array('pageSize' => 5);

		$this->render('widgets/course', array(
			'course'=>$course,
			'studentId'=>$sid,
		));
	}

	public function actionTuitionWidget($sid){
		$this->layout = '//layouts/widget';

		$model = new CoursePayment;
        $model->unsetAttributes();

        $assignedCourses = Student::model()->assignedCourses($sid);
		if(count($assignedCourses)==0) $assignedCourses = array(0);
		$model->course_id = $assignedCourses;

		$model = $model->search();
        $model->pagination = array('pageSize' => 10);

		$this->render('widgets/tuition', array(
			'model'=>$model,
		));
	}

	public function actionStudentWidget($sid){
		$this->layout = '//layouts/widget';

		$student = Student::model()->with('user')->findByPk($sid);

		$this->render('widgets/student', array(
			'student' => $student,
		));
	}

	public function actionSendMail($sid){
		$this->subPageTitle="Gửi Email";

		$student = $this->loadModel($sid, array("student"));

		$this->render('sendMail', array(
			'student'=>$student,
		));
	}

	public function actionAjaxSendMail($sid){
		$student = $this->loadModel($sid, array("student"));

		if (isset($_POST['Mail'])){
			$params = $_POST['Mail'];
			$params['username'] = $student->username;
			if (!empty($params['sender']) &&
				!empty($params['template']) &&
				!empty($params['email']) &&
				ClsMailer::validateTemplate($params['template'], $params)
				)
			{
				$receiver = array(
					'name'=>$student->fullname(),
					'email'=>$params['email'],
				);
				if (ClsMailer::sendMail($params['sender'], $receiver, $params['template'], $params) === true){
					$this->renderJSON(array(
						"success"=>true,
					));
				}

			}
		}

		$this->renderJSON(array(
			"success"=>false,
		));
	}
}