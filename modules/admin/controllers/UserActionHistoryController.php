<?php

class UserActionHistoryController extends Controller
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
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Lịch sử các hoạt động';
		$model=new UserActionHistory('search');
		$this->loadJQuery = false;//Not load jquery	
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserActionHistory'])){
			$model->attributes=$_GET['UserActionHistory'];
			if(isset($_GET['UserActionHistory']['created_date'])){
				$model->created_date = Common::convertDateFilter($_GET['UserActionHistory']['created_date']);//Created date filter
			}
		}
		$model->getDbCriteria()->order = 'created_date DESC';
		$this->render('/user/actionHistory',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return UserActionHistory the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=UserActionHistory::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param UserActionHistory $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-action-history-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
