<?php

class PermissionController extends Controller
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
				'actions'=>array('index','view','create','update','admin','delete', 'user', 'manage'),
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
		$this->subPageTitle = 'Chi tiết quyền truy cập';	
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
		$this->subPageTitle = 'Thêm quyền truy cập';	
		$model=new Permission;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Permission']))
		{
			$model->attributes=$_POST['Permission'];
			if($model->save()){
				$this->redirect(array('/admin/permission/manage'));
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
		$this->subPageTitle = 'Sửa quyền truy cập';	
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Permission']))
		{
			$model->attributes=$_POST['Permission'];
			if($model->save()){
				$this->redirect(array('/admin/permission/manage'));
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
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/permission/index'));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionManage()
	{
		$this->subPageTitle = 'Quản lý quyền truy cập';
		$this->loadJQuery = false;//Not load jquery
		$model = new Permission('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Permission'])){
			$model->attributes=$_GET['Permission'];
		}
		$model->getDbCriteria()->order = 'controller ASC, action ASC';
		$this->render('manage',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Danh mục quyền truy cập';
		$permissions = Permission::model()->findAll(array('order'=>'controller ASC, action ASC'));
		$this->render('index',array(
			'permissions'=>$permissions,
		));
	}
	
	/**
	 * Get & update permission for user
	 */
	public function actionUser($id)
	{
		$this->subPageTitle = 'Bảng phân quyền người dùng';	
		$permissions = Permission::model()->findAll(array('order'=>'controller ASC, action ASC'));
		$user = User::model()->findByPk($id);
		if(isset($_POST['chkSave'])){
			$allowPermissions = Yii::app()->request->getPost('Permission', array());
			Permission::model()->assignPermissionsToUser($id, $allowPermissions);
		}
		$assignedPermissionIds = Permission::model()->getUserPermissions($id);
		$this->render('index',array(
			'permissions'=>$permissions,
			'user'=>$user,
			'assignedPermissionIds' => $assignedPermissionIds,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Permission the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Permission::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Permission $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='permission-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
