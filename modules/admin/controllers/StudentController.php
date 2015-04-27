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
				'actions'=>array('index','view','changeToTeacher','create','update', 'saleUpdate'),
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
		$model=new User;
		$student = new Student; 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->subPageTitle = 'Thêm học sinh mới';
		if(isset($_POST['User']))
		{
			$student_values = $_POST['User'];
			$student_profile_values = $_POST['Student'];//Student profile values
			$student_values['role'] = User::ROLE_STUDENT;
			if(trim($student_values['birthday'])==''){
				unset($student_values['birthday']);
			}
			$model->attributes= $student_values;
			$model->passwordSave = $student_values['password'];
			if($model->save()){
				$student->attributes = $student_profile_values;
				$student->user_id = $model->id;
				if($student->save()){
					$this->redirect(array('index'));
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'student'=>$student,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$student = Student::model()->findByPk($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->subPageTitle = 'Sửa thông tin học sinh';
		if(isset($_POST['User']))
		{
			$studentValues = $_POST['User'];
			$studentProfileValues = $_POST['Student'];//Student profile values
			if(trim($studentValues['birthday'])==''){
				unset($studentValues['birthday']);
			}
			$changePassStatus = (isset($_POST['changeStatus']) && $_POST['changeStatus']==1)? true: false;
			if($studentValues['password']==""){
				$changePassStatus = false;
				unset($studentValues['password']);//Not save password
			}
			$model->attributes = $studentValues;			
			if($changePassStatus){
				$model->passwordSave = $model->password;
				$model->repeatPassword = $model->passwordSave;
			}
			if($model->save()){
				$student->attributes = $studentProfileValues;
				$student->user_id = $model->id;
				if($student->save()){
					$this->redirect(array('index'));
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'student'=>$student,
		));
	}
	
	/**
	 * Updates sale information
	 */
	public function actionSaleUpdate($id)
	{
		$model = $this->loadModel($id);
		$student = Student::model()->findByPk($id);
		$saleHistory = new UserSalesHistory();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->subPageTitle = 'Sửa thông tin chăm sóc, tư vấn';
		$saleUserHistories = UserSalesHistory::model()->getSaleHistory($id);
		if(isset($_POST['Student']))
		{
			$studentValues = $_POST['Student'];
			$student->attributes = $studentValues;
			$student->user_id = $model->id;
			if($student->save()){
				if(isset($_POST['chkAddNewHistory']) && isset($_POST['UserSalesHistory'])){
					$saleValues = $_POST['UserSalesHistory'];
					$saleHistory->attributes = $saleValues;
					$saleHistory->user_id = $model->id;
					if($saleHistory->save()){
						$this->redirect(array('/admin/userSalesHistory/index?student_id='.$id));
					}
				}else{
					$this->redirect(array('index'));
				}
			}
		}
		$this->render('saleForm',array(
			'model'=>$model,
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
		if($model->status==User::STATUS_PENDING){
			$model->deleted_flag = 1;//Deleted flag
			$model->save();
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
		$model = new User('search');
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
				$studentFilters['fullname'] = $_GET['User']['firstname'];
			}
		}
		$model = Student::model()->filterModelUser($model, $studentFilters);//Filter user
		$model->getDbCriteria()->order = 'created_date DESC';
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
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
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
}