<?php

class UserSalesHistoryController extends Controller
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
				'actions'=>array('index','view','create', 'update'),
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
		$this->subPageTitle = 'Chi tiết thông tin tư vấn';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->subPageTitle = 'Sửa thông tin tư vấn';
		$model = $this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['UserSalesHistory']))
		{
			$model->attributes=$_POST['UserSalesHistory'];
			if($model->save()){
				$this->redirect(array('userSalesHistory/index?student_id='.$model->user_id));
			}
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Lịch sử chăm sóc, tư vấn';
		$model=new UserSalesHistory('search');
		$this->loadJQuery = false;//Not load jquery
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserSalesHistory'])){
			$model->attributes = $_GET['UserSalesHistory'];
			if(isset($_GET['UserSalesHistory']['sale_date'])){
				$model->sale_date = Common::convertDateFilter($_GET['UserSalesHistory']['sale_date']);//Birthday filter
			}
			if(isset($_GET['UserSalesHistory']['next_sale_date'])){
				$model->next_sale_date = Common::convertDateFilter($_GET['UserSalesHistory']['next_sale_date']);//Birthday filter
			}
		}
		$student = NULL;//Preregister course
		if(isset($_GET['student_id']) && trim($_GET['student_id'])!=""){
			$student = User::model()->findByPk($_GET['student_id']);
			if(isset($student->id)){
				$model->user_id = $student->id;
			}
		}
		$model->getDbCriteria()->order = 'sale_date DESC';
		$this->render('index',array(
			'model'=>$model,
			'student'=>$student,
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/student'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UserSalesHistory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UserSalesHistory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UserSalesHistory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-sales-history-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
