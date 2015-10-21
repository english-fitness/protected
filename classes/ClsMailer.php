<?php
class ClsMailer
{
	public $fromEmail = 'contact@daykem11.com';
	public $fromName = 'Contact Daykem11';
	public $expiredDays = 60;// 60 days

	public static function availableSenders(){
		return array(
			"speakup"=>"Speak up (speakup@hocmai.vn)"
		);
	}

	private static function senderDetails($sender){
		$senders = array(
			"speakup"=>array(
				"name"=>"Speak up",
				"email"=>"speakup@hocmai.vn",
				"username"=>"speakup@hocmai.com.vn",
				"password"=>"hocmai.vn2015",
			)
		);

		if ($sender){
			return $senders[$sender];
		} else {
			return $senders;
		}
	}

	/**
	 * Send Activation email to student
	 */
	public function sendActivation($email, $activationCode)
	{
		$mail = new YiiMailer('activation', array('activationCode'=>$activationCode), 'mail');
		$mail->setFrom($this->fromEmail, $this->fromName);
		$mail->setSubject("Email xác nhận tài khoản học sinh!");
		$mail->setTo($email);
		try {
			$mail->send();
			return true;
		}catch(Exception $e){
			return false;
		}
	}
	
	/**
	 * Send email forgot password 
	 */
	public function forgotPassword($email,$code)
    {
        $user = User::model()->findByAttributes(array('email'=>$email));
        if(isset($user->id)){
            $mail = new YiiMailer('resetPassword', array('code'=>$code), 'mail');
            $mail->setFrom($this->fromEmail, $this->fromName);
            $mail->setSubject("Mã xác nhận thay đổi mật khẩu");
            $mail->setTo($email);
            try {
                $mail->send();
                return true;
            }catch(Exception $e){}
        }
        return false;
    }
    
	/**
	 * Add welcome email to email queue 
	 */
	public function saveWelcomeEmailToQueue($email, $name="")
    {
    	$subject = "Chúc mừng bạn đã là thành viên của Dạy kèm trực tuyến";
    	$mail = new YiiMailer('welcome', array(), 'mail');
    	$body = $mail->renderView('application.views.mail.welcome', array('name'=>$name));
    	$content = $mail->renderView('application.views.layouts.mail', array('content'=>$body));
		$emailQueue = new EmailQueue();
		$emailQueue->attributes = array(
			'subject' => $subject,
			'content' => $content,
			'receiver_address' => $email,
			'status' => 0,
		);
		$emailQueue->save();
    }
    
    public function sendSessionReminder($students, $content, $translation, $date, $time){
        foreach($students as $student){
            $mail = new YiiMailer();
            $mail->setSubject("Ghi nhớ cho buổi học ngày " . $date);
            $mail->setFrom("no-reply@speakup.vn", "Speak up");
            $mail->setTo(array($student->email=>$student->fullname()));
            $body = $mail->renderView('application.views.mail.sessionReminder', array(
                "name"=>$student->fullname(),
                "content"=>$content,
                "translation"=>$translation,
                "date"=>$date,
                "time"=>$time
            ));
            $mail->setBody($body);
            try {
                $mail->send();
                return true;
            } catch(Exception $e){
                return false;
            }
        }
    }

    public static function sendMail($sender, $receivers, $template, $params){
    	$mailer = new PHPMailer;
    	$mailer->isSMTP();
    	$mailer->CharSet = 'UTF-8';
    	$mailer->SMTPAuth = true;
    	$mailer->Host = 'smtp.gmail.com';
    	$mailer->Port = 587;
    	$mailer->SMTPSecure = 'tls';
    	$mailer->isHTML(true);

    	$senderInfo = self::senderDetails($sender);
    	$mailer->Username = $senderInfo['username'];
    	$mailer->Password = $senderInfo['password'];
    	$mailer->setFrom($senderInfo['email'], $senderInfo['name']);
    	if (count($receivers) != count($receivers, 1)){
    		foreach ($receiver as $receiver) {
    			$mailer->addAddress($receiver['email'], $receiver['name']);
    		}
    	} else {
    		$mailer->addAddress($receivers['email'], $receivers['name']);
    	}

    	$mailer->Subject = $params['subject'];

    	$content = self::renderView('//../modules/admin/views/mailTemplate/mail/'.$template, $params);
    	$signature = self::renderView('//../modules/admin/views/mailTemplate/mail/signature');
    	$mailer->Body = $content.$signature;

    	if ($mailer->send()){
    		return true;
    	} else {
    		return false;
    	}

    }

    public static function validateTemplate($template, $params){
    	if (empty($params['name']) || empty($params['date']) || empty($params['time']) || empty($params['email'])){
    		return false;
    	}
    	switch ($template){
    		case 'trialSchedule' || 'testSchedule':
    			return true;
    			break;
    		case 'classSchedule':
    			if (empty($params['wday'])){
    				return false;
    			}
    			break;
    		default:
    			return false;
    			break;
    	}
    	return true;
    }

    //copied from YiiMailer
    private static function renderView($viewName, $viewData=null){
    	if(($viewFile=self::getViewFile($viewName))!==false)
		{
			//use controller instance if available or create dummy controller for console applications
			if(isset(Yii::app()->controller))
				$controller=Yii::app()->controller;
			else
				$controller=new CController(__CLASS__);

			//render and return the result
			return $controller->renderInternal($viewFile,$viewData,true);
		}
		else
		{
			//file name does not exist
			throw new CException('View "'.$viewName.'" does not exist!');
		}
    }

    private static function getViewFile($viewName)
	{
		//In web application, use existing method
		if(isset(Yii::app()->controller))
			return Yii::app()->controller->getViewFile($viewName);
		//resolve the view file
		//TODO: support for themes in console applications
		if(empty($viewName))
			return false;
		
		$viewFile=Yii::getPathOfAlias($viewName);
		if(is_file($viewFile.'.php'))
			return Yii::app()->findLocalizedFile($viewFile.'.php');
		else
			return false;
	}
}
?>