<?php
class MessageController extends Controller {

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
				'actions'=>array('index','view','inbox','outbox','create','update','ajaxMarkRead'),
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
	
	//Index action
    public function actionIndex()
    {
        $this->redirect(array('/admin/message/inbox'));
    }
    
    //Inbox action
    public function actionInbox()
    {
        $this->subPageTitle = "Tin nhắn đến";
        $this->loadJQuery = false;//Not load jquery
        $uid = Yii::app()->user->id;
        $model =new Message('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Message'])){
            $model->attributes=$_GET['Message'];
            if(isset($_GET['Message']['created_date'])){
            	$model->created_date = Common::convertDateFilter($_GET['Message']['created_date']);//Created date filter
            }
        }
        $whereUserRole = "('".User::ROLE_ADMIN."','".User::ROLE_MONITOR."')";  
        $model->getDbCriteria()->addCondition("sender_id NOT IN (SELECT id FROM tbl_user WHERE role IN $whereUserRole)");
    	$model->deleted_flag = 0;//Deleted flag
		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
			$model->deleted_flag = 1;
		}
        $this->render("inbox",array(
	        "model"=>$model,
	        "title"=>"Tin nhắn gửi đến"
        ));
    }

    //Outbox action
    public function actionOutbox()
    {
        $this->subPageTitle = "Tin nhắn đã gửi";
        $this->loadJQuery = false;//Not load jquery
        $uid = Yii::app()->user->id;
        $model =new Message('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Message'])){
            $model->attributes=$_GET['Message'];
            if(isset($_GET['Message']['created_date'])){
            	$model->created_date = Common::convertDateFilter($_GET['Message']['created_date']);//Created date filter
            }
        }
        $whereUserRole = "('".User::ROLE_ADMIN."','".User::ROLE_MONITOR."')";  
        $model->getDbCriteria()->addCondition("sender_id IN (SELECT id FROM tbl_user WHERE role IN $whereUserRole)");
    	$model->deleted_flag = 0;//Deleted flag
		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
			$model->deleted_flag = 1;
		}
        $this->render("outbox",array(
	        "model"=>$model,
	        "title"=>"Tin nhắn đã gửi"
        ));
    }

    //Create message action
    public function actionCreate()
    {
        $this->subPageTitle = "Gửi tin nhắn";
        $model = new Message();
        $params = array();//Render view params
        if(isset($_GET['uid'])){
        	$receiver = User::model()->findByPk($_GET['uid']);
        	if(isset($receiver->id)) $params['receiver'] = $receiver;
        }
        if(isset($_GET['msgId'])){
        	$message = Message::model()->findByPk($_GET['msgId']);
        	if(isset($message->id)) $params['message'] = $message;
        }
        if(isset($_POST['Message'])){
        	$model->sender_id = Yii::app()->user->id;
            $model->attributes = $_POST['Message'];
            if($model->save()){
            	if(isset($_POST['extraUserIds'])){
	            	MessageStatus::model()->to($_POST['extraUserIds'], $model->id);
            	}
            	$this->redirect(array('/admin/message/outbox'));
            }
        }
        $params['model'] = $model;//Assign model param
        $this->render("create", array('params'=>$params));
    }

    //Update action
    public function actionUpdate($id)
    {
        $this->subPageTitle = "Sửa tin nhắn";
        $model = Message::model()->findByPk($id);
        if(isset($_POST['Message']) && isset($model->id)){
        	$model->attributes = $_POST['Message'];
            if($model->save()){
            	if(isset($_POST['extraUserIds'])){
	            	MessageStatus::model()->to($_POST['extraUserIds'], $model->id);
            	}
                $this->redirect(array('/admin/message/outbox'));
            }
        }
        $this->render("update", array("model"=>$model));
    }
    
    //View message
    public function actionView($id)
    {
        $this->subPageTitle = 'Chi tiết tin nhắn';
        $model = Message::model()->findByPk($id);
        $this->render('view',array(
            'model'=>$model,
        ));
    }

    //Delete message
    public function actionDelete($id)
    {
    	$indexPage = 'inbox';
    	$model=Message::model()->findByPk($id);//Load model
    	if($model->sender_id == Yii::app()->user->id){
    		$indexPage = 'outbox';
    	}
        if($model->deleted_flag==0){
        	$model->deleted_flag = 1;//Set deleted flag before delete
            $model->save();
            $this->redirect(array('/admin/message/'.$indexPage));
        }else{
	        MessageStatus::model()->deleteAll(array('condition'=>"message_id = $id"));
	        $model->delete();
	        $this->redirect(array('/admin/message/'.$indexPage));
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/message/'.$indexPage));
    }
    
   /**
     * Ajax mark read in message
     */
    public function actionAjaxMarkRead()
    {
        $messageId = $_REQUEST['messageId'];
        $success = true;//Set success
        $model = Message::model()->findByPk($messageId);
        $model->readFlagUpdate(1);
        $this->renderJSON(array('success'=>$success));
    }

}
