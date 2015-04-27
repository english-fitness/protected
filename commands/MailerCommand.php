<?php

class MailerCommand extends CConsoleCommand
{
    public function actionSendQueue()
    {
    	$queueEmails = EmailQueue::model()->findAllByAttributes(array('status'=>0));
    	$mailer = new ClsMailer();
    	if(count($queueEmails)>0){
    		foreach($queueEmails as $email){
				$email->status = 1;
				$checkSentEmail = $mailer->sendEmail($email->subject, $email->content, $email->receiver_address);
				if($checkSentEmail){
					$email->status = 2;//Sent email
					$email->sent_date = date('Y-m-d H:i:s');//Sent email
					$email->save();
					echo $email->receiver_address.',';					
					$email->delete();//Sent email
				}else{
					$email->status = 0;//Sent email
					$email->save();
				}
    		}
    	}
    }
    
}