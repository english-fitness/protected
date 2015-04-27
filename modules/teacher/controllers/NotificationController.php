<?php

class NotificationController extends Controller
{

	public function actionIndex()
    {
		$this->subPageTitle = 'Thông báo từ hệ thống';
        $notifications = Notification::model()->getNotifications(Yii::app()->user);
        $this->render("index",array("notifications"=>$notifications));
    }
    
	//Mark read notification
	public function actionMarkRead()
    {
		$noticeId = isset($_REQUEST['noticeId'])? $_REQUEST['noticeId']: 0;
		$success = false;
		$notice = Notification::model()->findByPk($noticeId);
		if(isset($notice->id)){
			$notice->confirmed_ids .= Yii::app()->user->id.',';
			$notice->save();
			$success = true;
		}
		$this->renderJSON(array('success'=>$success));
    }
}