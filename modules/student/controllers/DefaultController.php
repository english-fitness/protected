<?php

class DefaultController extends CController{
    public function actionIndex()
    {
    	$userId = Yii::app()->user->id;
    	$user = User::model()->findByPk($userId);
    	// $clsNotification = new ClsNotification();
     //    $enoughProfile = $clsNotification->enoughProfile($userId);
    	// $checkRegisteredCourse = Course::model()->checkRegisteredCourseStudent($userId);
    	// $countPreCourse = PreregisterCourse::model()->countByAttributes(array('student_id'=>$userId, 'deleted_flag'=>0));
        $defaultPwHash = crypt("speakup.vn", $user->password);
        if ($defaultPwHash == $user->password){
            $this->redirect('/student/account/resetPassword');
        }
    	$returnUrl = Yii::app()->session['returnUrl'];
    	if(isset($returnUrl) && $returnUrl!==false){
    		$this->redirect($returnUrl);
    	} else {
			$this->redirect('/student/class/nearestSession');
		}
		// elseif($checkRegisteredCourse || $countPreCourse>0){
        	// $this->redirect('/student/class/nearestSession');
    	// }elseif(!$enoughProfile || $user->status<User::STATUS_ENOUGH_PROFILE){
    		// if($user->status==User::STATUS_PENDING){
    			// $this->redirect('/student/support/index');
    		// }
			// else{
    			// $this->redirect('/student/class/nearestSession');
    		// }
    	// }elseif($user->status<User::STATUS_ENOUGH_AUDIO){
    		// $this->redirect('/student/testCondition/index');
    	// }else{
    		// $this->redirect('/student/presetRequest/index');
    	// }
    }

}