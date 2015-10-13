<?php

class CartController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	public function init(){
		$this->menu=array(
		    array('label'=>'<i class="glyphicon glyphicon-th-list"></i> Danh sách thẻ', 'url'=>array('index')),
		    array('label'=>'<i class="glyphicon glyphicon-list-alt"></i> Quản lý thẻ', 'url'=>array('admin')),
		);
	}

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(

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
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
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
		$model=new Cart;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Cart']))
		{
			if (isset($_POST['amount']) && $_POST['amount'] > 1){
				if (Cart::batchCreate($_POST['Cart']['cart_price'], $_POST['amount'])){
					CartLog::model()->log($_POST['amount'],'Tạo thẻ khuyến mãi mới');
					$this->redirect(array('admin'));
				}
			} else {
				$model->attributes=$_POST['Cart'];
	            $model->cart_code = mt_rand(1000000000,9999999999).rand(10,99);
	            $model->cart_status = 1;
				if($model->save()) {
	                CartLog::model()->log(1,'Tạo thẻ khuyến mãi mới');
	                $this->redirect(array('view','id'=>$model->cart_id));
	            }
	        }

		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Cart',array(
            'criteria' => array(
                'order'=>'cart_status asc',
            )
        ));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Cart('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Cart']))
			$model->attributes=$_GET['Cart'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Cart the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Cart::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Cart $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='cart-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
