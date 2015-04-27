<?php

class UserController extends Controller
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
			array('allow',
				'actions'=>array('view'),
				'users'=>array('*'),
			),
			array('allow',
				'actions'=>array('index','create','update','admin','delete','deletedUser'),
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
		$this->subPageTitle = 'Thông tin người dùng';
		$model = $this->loadModel($id);
		if($model->role==User::ROLE_STUDENT){
			$this->redirect(array('/admin/student/view/id/'.$model->id));
		}elseif($model->role==User::ROLE_TEACHER){
			$this->redirect(array('/admin/teacher/view/id/'.$model->id));
		}
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->subPageTitle = 'Thêm mới người dùng';
		if(isset($_POST['User']))
		{
			$user_values = $_POST['User'];
			if(!isset($user_values['role'])){
				$user_values['role'] = User::ROLE_MONITOR;
			}
			if(trim($user_values['birthday'])==''){
				unset($user_values['birthday']);
			}
			$model->attributes= $user_values;
			$model->passwordSave = $user_values['password'];
			if($model->save()){
				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
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
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$this->subPageTitle = 'Sửa thông tin người dùng';
		if(isset($_POST['User']))
		{
			$userValues = $_POST['User'];
			if(trim($userValues['birthday'])==''){
				unset($userValues['birthday']);
			}
			$changePassStatus = (isset($_POST['changeStatus']) && $_POST['changeStatus']==1)? true: false;
			if($userValues['password']==""){
				$changePassStatus = false;
				unset($userValues['password']);//Not save/change password
			}
			$model->attributes = $userValues;			
			if($changePassStatus){
				$model->passwordSave = $model->password;
				$model->repeatPassword = $model->passwordSave;
			}
			if($model->save()){
				$this->redirect(array('index'));
			}
		}

		$this->render('update',array(
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
		$model = $this->loadModel($id);//Load model
		if($model->deleted_flag==0){
			$model->deleted_flag = 1;//Set deleted flag before delete
			$model->save();
			$this->redirect(array('/admin/user/update/id/'.$id));
		}else{
			try {
				if($model->role==User::ROLE_STUDENT){
					//Delete forever a student
					Student::model()->deleteForeverStudent($model->id);
				}elseif($model->role==User::ROLE_TEACHER){
					//Delete forever a teacher
					Teacher::model()->deleteForeverTeacher($model->id);
				}else{
					Course::model()->deleteAssignedCoursesByUser($model->id);
					$model->delete();//Delete forever this User
				}
				$this->redirect(array('/admin/user/deletedUser'));
			}catch(Exception $e){
				//Display error message here
			}
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/user/deletedUser'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Danh sách người dùng';
		$this->loadJQuery = false;//Not load jquery
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		$criteria = new CDbCriteria();
        $criteria->condition = "role NOT IN('".User::ROLE_STUDENT."','".User::ROLE_TEACHER."') AND id<>1";
        $criteria->compare('deleted_flag', 0);
        $model->setDbCriteria($criteria);
		if(isset($_GET['User'])){
			$model->attributes=$_GET['User'];
			if(isset($_GET['User']['birthday'])){
				$model->birthday = Common::convertDateFilter($_GET['User']['birthday']);//Birthday filter
			}
			if(isset($_GET['User']['firstname'])){
				$keyword = $_GET['User']['firstname'];
				$model->getDbCriteria()->addCondition("CONCAT(`lastname`,' ',`firstname`) LIKE '%".$keyword."%'");
			}
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionDeletedUser()
	{
		$this->subPageTitle = 'Danh sách người dùng đã bị xóa';
		$this->loadJQuery = false;//Not load jquery
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		$criteria = new CDbCriteria();
        $criteria->compare('deleted_flag',1);
        $model->setDbCriteria($criteria);
		if(isset($_GET['User'])){
			$model->attributes=$_GET['User'];
			if(isset($_GET['User']['birthday'])){
				$model->birthday = Common::convertDateFilter($_GET['User']['birthday']);//Birthday filter
			}
		}
		$this->render('deleted',array(
			'model'=>$model,
		));
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
