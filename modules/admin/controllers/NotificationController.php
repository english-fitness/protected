<?php

class NotificationController extends Controller
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
				'actions'=>array('index','view','create','update','ajaxLoadUser'),
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
		$this->subPageTitle = 'Chi tiết thông báo';
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
		$this->subPageTitle = 'Thêm thông báo mới';
		$model = new Notification;
		$errorMsg = "";
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Notification']))
		{
			$noticeValues = $_POST['Notification'];
            $userIds = Yii::app()->request->getPost('extraUserIds', array());
			if(count($userIds)>0 && trim($noticeValues['content']))
			{
				foreach($userIds as $userId){
					$notice = new Notification;
					$notice->attributes = array(
						'receiver_id' => $userId,
						'content' => $noticeValues['content'],
					);
					$notice->save();
				}
				$this->redirect(array('index'));
			}else{
				$errorMsg = 'Email của người nhận không tồn tại hoặc chưa điền nội dung thông báo!';
			}
		}

		$this->render('create',array(
			'model'=>$model, 'errorMsg'=>$errorMsg,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->subPageTitle = 'Sửa thông báo';
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Notification']))
		{
			$model->attributes=$_POST['Notification'];
			if($model->save())
				$this->redirect(array('index'));
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
            $this->redirect(array('/admin/notification/update/id/'.$model->id));
        }else{
        	$model->delete();//Delete this notification
        }
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/notification'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Thông báo từ hệ thống';
		$this->loadJQuery = false;//Not load jquery
		$model=new Notification('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Notification'])){
			$model->attributes=$_GET['Notification'];
			if(isset($_GET['Notification']['created_date'])){
				$model->created_date = Common::convertDateFilter($_GET['Notification']['created_date']);//Created date filter
			}
			if(isset($_GET['Notification']['receiver_id'])){
				$receiver = User::model()->findByAttributes(array('email'=>$_GET['Notification']['receiver_id']));
				if(isset($receiver->id)){//Filter by receiver id
					$model->receiver_id = $receiver->id;
				}
			}
		}
		$model->deleted_flag = 0;//Deleted flag
		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
			$model->deleted_flag = 1;
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Notification the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Notification::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Notification $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='notification-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

    /* action Ajax Load User */
    public  function  actionAjaxLoadUser($keyword){
        $usersAttributes = User::model()->searchUsersToAssign($keyword);
        $this->renderJSON(array($usersAttributes));
    }
}
