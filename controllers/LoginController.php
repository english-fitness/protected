<?php

class LoginController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'vi';//Config admin language is Vietnamese
       	$this->layout = '//layouts/login';
    }
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->redirect(array('/site/index'));
	}
	
	//Connect & login by facebook
	public function actionFacebook()
	{
		$this->subPageTitle = 'Đăng nhập bằng Facebook';
        $facebook = new ClsFacebook();
        $redirectUri = Yii::app()->getRequest()->getBaseUrl(true)."/login/facebook";
        $params = $facebook->connectToFacebook($redirectUri);
        if($params['facebookConnected']){
        	if(User::model()->loginByFacebook($params['userData'])){
        		$params['connectedSuccess'] = 1;
        	}else{
        		$user = User::model()->createUserByGoogleFB($params['userData'], 'facebook');
        		if(User::model()->UserApiIdentity($user->id)){
        			$params['connectedSuccess'] = 1;
                    Settings::shareFacebook(Settings::SHARE_SIGN_UP_ACCOUNT,$user);
        		}
        	}
        }else{
	    	$this->redirect($params['fbLoginUrl']);
        }
		$this->render('/site/loginPopup',$params);
	}
	
	//Connect & login by google
	public function actionGoogle()
	{
		$this->subPageTitle = 'Đăng nhập bằng Google';
        $goolge = new ClsGoogle();
        $user = new User();
		$params = $goolge->connectToGoogle();
		//Check & login by google
        if ($params['googleConnected']){
        	if(isset(Yii::app()->user->id)){
        		$urlModule = str_replace("role_","",Yii::app()->user->role);
        		$accountConnectUri = Yii::app()->baseUrl.'/'.$urlModule.'/account/connectGoogle';
        		$this->redirect($accountConnectUri);
        	}
        	if(User::model()->loginByGoogle($params['userData'])){
        		$params['connectedSuccess'] = 1;
        	}else{
        		$user = User::model()->createUserByGoogleFB($params['userData'], 'google');
        		if(User::model()->UserApiIdentity($user->id)){
        			$params['connectedSuccess'] = 1;
        		}
        	}
        }else{
        	//Get return google code
			if (isset($_GET['code'])){
				$goolge->gClient->authenticate($_GET['code']);
				Yii::app()->session['token'] = $goolge->gClient->getAccessToken();
				$googleRedirectUri = Yii::app()->getRequest()->getBaseUrl(true).'/login/google';
				$this->redirect($googleRedirectUri);
				return ;
			}			
        	$googleAuthUrl = $goolge->gClient->createAuthUrl();
	    	$this->redirect($googleAuthUrl);
        }
		$this->render('/site/loginPopup',$params);
	}
	
	//Connect & login by hocmai
	public function actionHocmai()
	{
		$this->subPageTitle = 'Đăng nhập bằng tài khoản trên Hocmai.vn';
        $hocmai = new ClsHocmai();
        $params = array();//Init hocmai params
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
	        	if(User::model()->loginByHocmai($params['userData'])){
	        		$params['connectedSuccess'] = 1;
	        	}else{
	        		$user = User::model()->createUserByGoogleFB($params['userData'], 'hocmai');
	        		if(User::model()->UserApiIdentity($user->id)){
	        			$params['connectedSuccess'] = 1;
	        		}
	        	}
	        }
        }
		$this->render('/site/loginHocmai',$params);
	}
	
	//Connect & login by studentUrl
	public function actionByUrl()
	{
		if(isset($_GET['username']) && isset($_GET['token'])){
			$user = User::model()->findByAttributes(array('username'=>$_GET['username']));
			if(isset($user->id) && $user->role){
				$tokenCode = sha1($user->id.$user->role.$user->username);
                // exit($_GET['token']==$tokenCode ? "access granted" : "access denied");
				if($_GET['token']==$tokenCode){
                    // exit(User::model()->UserApiIdentity($user->id) ? "access granted" : "access denied");
					if(User::model()->UserApiIdentity($user->id)){
                        $user->active_session = $_COOKIE['PHPSESSID'];
                        $user->save();
                        
                        $_SESSION['active_session'] = $user->active_session;
						$this->redirect('/site/loggedRedirect');
					}
				}
			}
		}
		$this->redirect('/site/index');
	}
	
	//Register account
	public function actionRegister()
	{
		$this->subPageTitle = 'Đăng ký tài khoản học sinh';
		$user = new User();
		$student = new Student;
		$classes = Classes::model()->findAll();
		$params = array("classes"=>$classes);
		$userData = array(); $errors = array();
        if(isset($_POST['User']))
        {
            $userData = $_POST['User'];
            $studentProfileValues = $_POST['Student'];//Student profile values
            $common = new Common();
            $user->attributes = $userData;
            $user->role = User::ROLE_STUDENT;
            $user->passwordSave = $userData['password'];
            $user->repeatPassword = $userData['repeatPassword'];           
            $dir = "media/uploads/profiles";
            if(isset($_POST['profilePicture'])){
            	$user->profile_picture = $common->uploadProfilePicture("profilePicture",$dir);
            }
            $birthday = $_POST['birthday'];
            $user->birthday = $birthday['year']."-".$birthday['month']."-".$birthday['date'];
            //Generate activation code
            $mailer = new ClsMailer();
            $activationCode = sha1(mt_rand(10000,99999).time().$user->email);
            $user->activation_code = $activationCode;
            $user->activation_expired = date('Y-m-d H:i:s', time('now')+$mailer->expiredDays*86400);
            $passwordConfirmValid = ($user->passwordSave==$user->repeatPassword)? true:false;
            if($user->save()) {
                //Save student profile
                $student->attributes = $studentProfileValues;
                $student->user_id = $user->id;
                if($student->save()) {
                    //Register by facebook api
                    if(isset($_POST['facebookConnected']) && $_POST['facebookConnected']==1){
                    	if(isset(Yii::app()->session['facebookData'])){
	                    	$facebook = new UserFacebook();
	                    	$facebook->saveFacebookUser($user->id, Yii::app()->session['facebookData']);
	                    	unset($_SESSION['facebookData']);
                    	}
                    }
                    //Register by google api
	                if(isset($_POST['googleConnected']) && $_POST['googleConnected']==1){
	                    if(isset(Yii::app()->session['googleData'])){
	                    	$google = new UserGoogle();
	                    	$google->saveGoogleUser($user->id, Yii::app()->session['googleData']);
	                    	unset($_SESSION['googleData']);
                    	}
                    }
                    //Auto login after register
                    $modelLogin = new LoginForm;
                    $modelLogin->attributes = array('email'=>$user->email, 'password'=>$user->passwordSave);
                    if($modelLogin->validate() && $modelLogin->login()){
                    	$params['connectedSuccess'] = 1;
                    }
                    //Send activation email when user registered success
                    $emailQueue = $mailer->saveWelcomeEmailToQueue($user->email, $user->fullName());
                 }else{
                 	$errors = $student->getErrors();
                 }
            }else{
            	$errors = $user->getErrors();
            	if(!$passwordConfirmValid){
            		$errors['repeatPassword'][0] = 'Vui lòng xác nhận lại mật khẩu!';
            	}
            }
        }
        $params['userData'] = $userData;//Set user values
        $params['errors'] = $errors;//Set error
        $this->render('/site/register',$params);
	}
	
}