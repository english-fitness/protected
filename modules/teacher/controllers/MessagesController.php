<?php

/* Messages Controller */
class MessagesController extends Controller {
	/* sub Page Title */
    public  $subPageTitle = 'Messages';
    /* action Index */
    public function actionIndex() {
        $uid = Yii::app()->user->id;
        $this->renderAjax("teacher.views.messages.index",MessageStatus::model()->getInboxMessages($uid));
    }

    /* action  Sent*/
    public function actionSent() {
        $uid = Yii::app()->user->id;
        $this->renderAjax("teacher.views.messages.sent",Message::model()->getSentMessage($uid));
    }

    /* action Inbox */
    public function actionSend() {
        $this->renderAjax("teacher.views.messages.send");
    }

    /* action ajax sent */
    public function actionAjaxSent() {
        $result['success'] = false;
        $result['notice']['to'] = 'Please add a recipent';
        if(isset($_POST['title'])){
            $message = new Message();
            $from = Yii::app()->user->id;
            $to = array("1");
            $content = array(
                'title'=>$_POST['title'],
                'content'=>$_POST['content']
            );
            $message = $message->send($from,$to,$content);
            if(!$message->getErrors()){
                $result['success'] = true;
                $result['element'] = "#main-center";
                $result['redirect'] =$this->getUrl(array("sent"));
            }else{
                $result['notice'] = array('content'=>'Please review your message.<br>');
            }
        }
        $this->renderJSON($result);
    }

    /* action View Sent*/
    public function actionViewSent($id) {
        $uid = Yii::app()->user->id;
        $attributes  = array("id"=>$id,'sender_id'=>$uid);
        $message = Message::model()->findByAttributes($attributes);
        if($message)
            return $this->renderAjax("teacher.views.messages.viewSent",array('message'=>$message));
        return $this->renderAjax("teacher.views.messages.error",array('error'=>'This message does not exist'));
    }

    /* action View Sent*/
    public function actionViewInbox($id) {
        $uid = Yii::app()->user->id;
        $attributes  = array("message_id"=>$id,'recipient_id'=>$uid);
        $messageStatus = MessageStatus::model()->findByAttributes($attributes);
        if($messageStatus):
            $message = Message::model()->findByPk($id);
            $message->readFlagUpdate($uid);
            return $this->renderAjax("teacher.views.messages.viewInbox",array('message'=>$message));
        else:
            return $this->renderAjax("teacher.views.messages.error",array('error'=>'This message does not exist'));
        endif;
    }

    /* action Ajax Load User */
    public  function  actionAjaxLoadUsers($keyword){
        $usersAttributes = User::model()->searchUsersToAssign($keyword,"role_admin");
        $this->renderJSON(array($usersAttributes));
    }

    /* action Delete Sent Message */
    public function  actionDeleteSentMessage($id){
        $uid = Yii::app()->user->id;
        $message = Message::model()->findByPk(array("sender_id"=>$uid,"id"=>$id));
        if($message) {
            MessageStatus::model()->deleteAll(array("condition"=>"message_id=$id"));
            $message->delete();
        }
        $result['element'] = "#main-center";
        $result['redirect'] =$this->getUrl(array("sent"));
        $this->renderJSON($result);
    }

    /* action Delete Inbox Message */
    public function  actionDeleteInboxMessage($id){
        $uid = Yii::app()->user->id;
        $messageStatus = MessageStatus::model()->findByAttributes(array("recipient_id"=>$uid,'id'=>$id));
        if($messageStatus) {
            $messageStatus->delete();
        }
        $result['element'] = "#main-center";
        $result['redirect'] =$this->getUrl(array("index"));
        $this->renderJSON($result);
    }
}










