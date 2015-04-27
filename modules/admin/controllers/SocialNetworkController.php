<?php

class SocialNetworkController extends Controller
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
				'actions'=>array('index','view','update','facebook','google','hocmai'),
				'users'=>array('*'),
				'expression' => 'Yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Index social network
	 */
	public function actionIndex($id)
	{
		$this->redirect('/admin/socialNetwork/facebook');
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->subPageTitle = 'Thông tin mạng xã hội kết nối';
		$model = $this->loadModel($id);
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all of Facebook User.
	 */
	public function actionFacebook()
	{
		$this->subPageTitle = 'Tài khoản Facebook đã kết nối';
		$this->loadJQuery = false;//Not load jquery
		$model = new UserFacebook('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserFacebook'])){
			$model->attributes=$_GET['UserFacebook'];
		}
		$this->render('facebook',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Lists all of Google User.
	 */
	public function actionGoogle()
	{
		$this->subPageTitle = 'Tài khoản Gmail đã kết nối';
		$this->loadJQuery = false;//Not load jquery
		$model = new UserGoogle('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserGoogle'])){
			$model->attributes=$_GET['UserGoogle'];
		}
		$this->render('google',array(
			'model'=>$model,
		));
	}
	/**
	 * Lists all of Hocmai User.
	 */
	public function actionHocmai()
	{
		$this->subPageTitle = 'Tài khoản Hocmai đã kết nối';
		$this->loadJQuery = false;//Not load jquery
		$model = new UserHocmai('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['UserHocmai'])){
			$model->attributes=$_GET['UserHocmai'];
		}
		$this->render('hocmai',array(
			'model'=>$model,
		));
	}

}
