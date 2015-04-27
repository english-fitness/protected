<?php

class PreregisterPaymentController extends Controller
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
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('create','update','admin','delete'),
				'users'=>array('*'),
				'expression' => 'Yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model = new PreregisterPayment();
		$this->subPageTitle = 'Thêm phiếu thu học phí';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$renderParams = array();
		if(isset($_GET['precourse_id'])){
			$preCourse = PreregisterCourse::model()->findByPk($_REQUEST['precourse_id']);
			if(isset($preCourse->id)) $renderParams['preCourse'] =  $preCourse;
			$model->precourse_id = $preCourse->id;
		}
		if(isset($_POST['PreregisterPayment']))
		{
			$model->attributes=$_POST['PreregisterPayment'];
			if(strtotime($model->payment_date)===false){
				$model->payment_date = NULL;//Not save payment date
			}
			$model->created_user_id = Yii::app()->user->id;
			if($model->save()){
				$this->redirect(array('/admin/preregisterPayment?precourse_id='.$model->precourse_id));
			}
		}
		$renderParams['model'] = $model;
		$this->render('create',$renderParams);
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$this->subPageTitle = 'Chỉnh sửa phiếu thu học phí';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$preCourse = PreregisterCourse::model()->findByPk($model->precourse_id);
		if(isset($_POST['PreregisterPayment']))
		{
			$model->attributes=$_POST['PreregisterPayment'];
			if(strtotime($model->payment_date)===false){
				$model->payment_date = NULL;//Not save payment date
			}
			if($model->save()){
				$this->redirect(array('/admin/preregisterPayment?precourse_id='.$model->precourse_id));
			}
		}		
		$this->render('update',array(
			'model'=>$model,
			'preCourse'=>$preCourse,
		));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Lịch sử thanh toán học phí';
		$this->loadJQuery = false;//Not load jquery
		$model=new PreregisterPayment('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PreregisterPayment'])){
			$model->attributes=$_GET['PreregisterPayment'];
			if(isset($_GET['PreregisterPayment']['payment_date'])){
				$model->payment_date = Common::convertDateFilter($_GET['PreregisterPayment']['payment_date']);//Created date filter
			}
		}
		$preCourse = NULL;//Preregister course
		if(isset($_GET['precourse_id'])){
			$model->precourse_id = $_GET['precourse_id'];
			$preCourse = PreregisterCourse::model()->findByPk($model->precourse_id);
		}
		$model->getDbCriteria()->order = 'payment_date DESC';
		$this->render('index',array(
			'model'=>$model,
			'preCourse'=>$preCourse,
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
		if($model->created_user_id==Yii::app()->user->id){
			$model->delete();//Delete payment history
			$this->redirect(array('/admin/preregisterPayment'));
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(array('/admin/preregisterPayment')));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PreregisterCourse the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PreregisterPayment::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PreregisterCourse $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='preregister-course-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
