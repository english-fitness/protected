<?php
class MessageStatusController extends Controller {

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
				'actions'=>array('index','view', 'create', 'update'),
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
    public function actionIndex($id)
    {
    	$this->subPageTitle = "Danh sách người nhận";
    	$message = Message::model()->findByPk($id);
        if(isset($message->id)) {
            $messageStatus = new MessageStatus('search');
            $messageStatus->unsetAttributes();  // clear any default values
            if(isset($_GET['Message'])){
                $messageStatus->attributes=$_GET['Message'];
            }
            if(isset($_GET['readFlag'])){
                $messageStatus->read_flag=$_GET['readFlag'];//
            }
            $messageStatus->message_id = $id;
            $this->render("index",array('model'=>$messageStatus,'message'=>$message));
        }else{
            $this->redirect(array('/admin/message/inbox'));
        }
    }
    

    //Delete message
    public function actionDelete($id)
    {
    	$model= MessageStatus::model()->findByPk($id);//Load model
    	$messageId = $model->message_id;
        if(isset($model->id)){        	
	        $model->delete();
	        $this->redirect(array('/admin/messageStatus/index/id/'.$messageId));
        }
        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/messageStatus/index/id/'.$messageId));
    }
   
}
