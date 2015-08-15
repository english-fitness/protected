<?php
class ClsMailer
{
	public $fromEmail = 'contact@daykem11.com';
	public $fromName = 'Contact Daykem11';
	public $expiredDays = 60;// 60 days

	/**
	 * Send Activation email to student
	 */
	public function sendEmail($subject, $content, $email)
	{
		$mail = new YiiMailer();
		$mail->setFrom($this->fromEmail, $this->fromName);
		$mail->setSubject($subject);
		$mail->setTo($email);
		$mail->setBody($content);
		try {
			$mail->send();
			return true;
		}catch(Exception $e){
			return false;
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
            $mail->setFrom("phuongth@hocmai.com.vn", "Speak up");
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
}
?>