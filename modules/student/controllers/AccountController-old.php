<?php
class AccountController extends Controller
{
	//Account information
    public function actionIndex()
    {
    	$this->subPageTitle = 'Thông tin tài khoản';
    	$uid = Yii::app()->user->id;
    	$model = User::model()->findByPk($uid);
    	$clsNotification = new ClsNotification();
        if(isset($_POST['User']))
        {
            $common = new Common();
            $formValue = $_POST['User'];
            $model->attributes = $formValue;
            $model->role = User::ROLE_STUDENT;
        	//Check validate date format
        	$birthday = $_POST['birthday'];//Post birthday part
            $model->birthday = $birthday['year']."-".$birthday['month']."-".$birthday['date'];
            if(!Common::validateDateFormat($model->birthday)){
            	$model->birthday = NULL;
            }
            //Check validate phone number
            if(!Common::validatePhoneNumber($model->phone)){
	            $model->phone = NULL;
            }
            $dir = "media/uploads/profiles";
            $profilePicture = $common->uploadProfilePicture("profilePicture",$dir);
            if($profilePicture) {
                $model->profile_picture = $profilePicture;
            }
            if($model->save()) {
                $student = Student::model()->findByPk($uid);
                $student->attributes = $_POST['Student'];
                //Check validate father phone
	            if(!Common::validatePhoneNumber($student->father_phone)){
		            $student->father_phone = NULL;
	            }
            	//Check validate mother phone
	            if(!Common::validatePhoneNumber($student->mother_phone)){
		            $student->mother_phone = NULL;
	            }
                $student->save();
                //Check enough profile & update status
            	$enoughProfile = $clsNotification->enoughProfile($uid);
                if($enoughProfile && $model->status < User::STATUS_ENOUGH_PROFILE){
                    $model->status = User::STATUS_ENOUGH_PROFILE;
                    $model->save();
                }
            }
        }
        $student = Student::model()->find(array("condition"=>"user_id = ".$uid));
        $classes = CHtml::listData(Classes::model()->getAll(false), 'id', 'name');
        $this->render('index',array("model"=>$model,"classes"=>$classes,"student"=>$student));
    }
    
    //Change password
    public function actionChangePassword()
    {
    	$this->redirect(Yii::app()->baseurl.'/student/account/index');
		$this->subPageTitle = 'Thay đổi mật khẩu tài khoản';
        $model = User::model()->findByPk(Yii::app()->user->id);
        $this->render('changePassword',array("model"=>$model));
    }
    
	//Connect to social network
    public function actionSocialNetwork()
    {
		$this->subPageTitle = 'Kết nối các mạng xã hội';
        $user = User::model()->findByPk(Yii::app()->user->id);
        $this->render('socialNetwork',array("user"=>$user));
    }
    
    //Ajax change password
    public function actionAjaxChangePassword()
    {
        $notice = "Vui lòng nhập đầy đủ thông tin";
        $success = false;
        if(isset($_POST['passwordSave']))
        {
            $user = User::model()->findByPk(Yii::app()->user->id);
            $user->passwordSave = $_POST['passwordSave'];
            $user->repeatPassword = $_POST['repeatPassword'];
            if($user->password != crypt($_POST['password'],$user->password)){
                $notice = "Mật khẩu cũ không chính xác";
            }else if($user->passwordSave =="" or $user->passwordSave != $user->repeatPassword){
                $notice = "Xác nhận lại mật khẩu mới.";
            }else{
                if($user->save()) {
                    $notice = "Sửa mật khẩu thành công";
                    $success = true;
                } else {
                    $success = false;
                    $notice = array_values($user->getErrors());
                }
            }

        }

        $this->renderJSON(array(
            'success' =>$success,
            'htmlTag'=>".editPassword",
            'notice'=>$notice
        ));
    }

	//Connect to facebook from profile 
	public function actionConnectFacebook()
	{
		$this->layout = '//layouts/login';
		$this->subPageTitle = 'Kết nối với tài khoản Facebook';
        $facebook = new ClsFacebook();
        $userId = Yii::app()->user->id;
        $redirectUri = Yii::app()->getRequest()->getBaseUrl(true)."/student/account/connectFacebook";
        $params = $facebook->connectToFacebook($redirectUri);
        if($params['facebookConnected']){
        	$existedOtherConnectFb = UserFacebook::model()->checkConnectedFbByOtherUser($userId, $params['userData']['id']);
        	if(!$existedOtherConnectFb){
        		$facebook = new UserFacebook();
	        	$facebook->saveFacebookUser($userId, $params['userData']);
	        	$params['connectedSuccess'] = 1;
        	}
        }else{
	    	$this->redirect($params['fbLoginUrl']);
        }
		$this->render('connectPopup',$params);
	}
	
	//Connect to facebook from profile 
	public function actionConnectGoogle()
	{
		$this->layout = '//layouts/login';
		$this->subPageTitle = 'Kết nối với tài khoản Gmail';
		$goolge = new ClsGoogle();
		$userId = Yii::app()->user->id;
		$params = $goolge->connectToGoogle();
        //Check & login by google
        if ($params['googleConnected']){
        	$existedOtherConnectGoogle = UserGoogle::model()->checkConnectedGoogleByOtherUser($userId, $params['userData']['id']);
        	if(!$existedOtherConnectGoogle){
        		$goolge = new UserGoogle();
	        	$goolge->saveGoogleUser($userId, $params['userData']);
	        	$params['connectedSuccess'] = 1;
        	}
        }else{
        	//Get return google code
			if (isset($_GET['code'])){
				$goolge->gClient->authenticate($_GET['code']);
				Yii::app()->session['token'] = $goolge->gClient->getAccessToken();
				$googleRedirectUri = Yii::app()->getRequest()->getBaseUrl(true).'/student/account/connectGoogle';
				$this->redirect($googleRedirectUri);
				return ;
			}			
        	$googleAuthUrl = $goolge->gClient->createAuthUrl();
	    	$this->redirect($googleAuthUrl);
        }
		$this->render('connectPopup',$params);
	}
	
	//Connect to Hocmai from profile 
	public function actionConnectHocmai()
	{
		$this->layout = '//layouts/login';
		$this->subPageTitle = 'Kết nối với tài khoản Hocmai.vn';
        $hocmai = new ClsHocmai(); $params = array();
        $userId = Yii::app()->user->id;
        if(isset($_REQUEST['username'])){
	        $username = $_REQUEST['username'];//Username Hocmai
	        $connectType = 'password';//Connect type by username & password
	        $tokenOrPass = isset($_REQUEST['password'])? $_REQUEST['password']: NULL;
	        if(isset($_REQUEST['token'])){//Connect by username & token
	        	$tokenOrPass = $_REQUEST['token'];
	        	$connectType = 'token';
	        }
	        $params = $hocmai->connectToHocmai($username, $tokenOrPass, $connectType);
	        if($params['hocmaiConnected']){
	        	$existedOtherConnectHocmai = UserHocmai::model()->checkConnectedHocmaiByOtherUser($userId, $username);
	        	if(!$existedOtherConnectHocmai){
	        		$hocmaiUser = new UserHocmai();
		        	$hocmaiUser->saveHocmaiUser($userId, $params['userData']);
		        	$params['connectedSuccess'] = 1;
	        	}
	        }
        }
		$this->render('connectHocmaiPopup',$params);
	}

}