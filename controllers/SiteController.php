<?php

class SiteController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'vi';//Config admin language is Vietnamese
    }
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->redirect(Yii::app()->baseurl.'/news');
		$this->layout = '//layouts/blank';
		$this->subPageTitle = 'Trang chủ';
		$this->render('index');
	}
	
	public function actionLogin()
	{
		$this->layout = '//layouts/blank';
		$this->subPageTitle = 'Đăng nhập';
		if(isset(Yii::app()->user->id)){
			$this->redirect('/site/loggedRedirect');
		}
		$this->render('index', array('openSigninPopup'=>true));
	}
	//Register form
	public function actionRegister()
	{
		$this->layout = '//layouts/blank';
		$this->subPageTitle = 'Đăng ký học';
		if(isset($_GET['returnUrl'])){
			Yii::app()->session['returnUrl'] = trim($_GET['returnUrl']);
		}else{
			Yii::app()->session['returnUrl'] = false;
		}
		if(isset(Yii::app()->user->id)){
			$this->redirect('/site/loggedRedirect');
		}
		$this->render('index', array('openSigninPopup'=>true));
	}
	
	/**
	 * User logged in to redirect from popup
	 */
	public function actionLoggedRedirect()
	{
		if(isset(Yii::app()->user->id)){
        	$adminRules = array(User::ROLE_ADMIN, User::ROLE_MONITOR);
			$userRole = Yii::app()->user->role;
			$urlModule = in_array($userRole, $adminRules)? 'admin': str_replace("role_","",$userRole);
            $this->redirect(Yii::app()->baseurl."/".$urlModule);
		}else{
			$this->redirect(Yii::app()->baseurl."/");
		}
	}
	
	/**
	 * User connected from account profile
	 */
	public function actionConnectedRedirect()
	{
		if(isset(Yii::app()->user->id)){
        	$adminRules = array(User::ROLE_ADMIN, User::ROLE_MONITOR);
			$userRole = Yii::app()->user->role;
			$urlModule = in_array($userRole, $adminRules)? 'admin': str_replace("role_","",$userRole);
            $this->redirect(Yii::app()->baseurl."/".$urlModule.'/account/socialNetwork');
		}else{
			$this->redirect(Yii::app()->baseurl."/");
		}
	}
	
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionActivateAccount()
	{
		$this->subPageTitle = 'Kích hoạt tài khoản';
		$code = isset($_REQUEST['code'])? $_REQUEST['code']: '-*-*-';
		$user = User::model()->findByAttributes(array('activation_code'=>$code));
		$activationStatus = 0;
		if(isset($user->id) && $user->activation_expired>=date('Y-m-d H:i:s')){
			$user->status = 1;
			$user->activation_code = NULL;
        	$user->activation_expired = NULL;
        	$user->save();
        	$activationStatus = 1;
		}
        $class = Classes::model()->findAll();
		$this->render('index', array("activationStatus"=>$activationStatus));

	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		$environment = getenv('ENV') ? getenv('ENV') : 'development';
		//Display error in development env, not redirect to homepage
		$this->layout = '//layouts/blank';
		$error = Yii::app()->errorHandler->error;
		if($environment != 'development' && isset($error['code']) && $error['code']!=403){
			$this->redirect(Yii::app()->homeUrl);
		}
		if($error){
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
		
	}

	//new sign in action
	public function actionSignin(){
		$success = false;
		$model = new LoginForm;
		
		// if (isset(Yii::app()->user->id)){
			// $this->redirect(Yii::app() -> homeUrl);
		// }
		
		if (isset($_POST['email'])){
			$model->attributes = $_POST;
			if ($model->validate() && $model->login()){
				$success = true;
				$adminRules = array(User::ROLE_ADMIN, User::ROLE_MONITOR);
				$userRole = Yii::app()->user->role;
				$urlModule = in_array($userRole, $adminRules)? 'admin': str_replace("role_","",$userRole);
				$url = Yii::app()->baseurl."/".$urlModule;
				
			}else{
				Yii::app()->user->setFlash('success','Incorrect username or password.');
			}
			
		}
		$this->redirect("/site/login");
	}
	
	//Popup login account
	// public function actionSignin()
	// {
		// $this->subPageTitle = 'Đăng nhập tài khoản';
        // $success = false;
        // $htmlTag = ".noticeForm";
        // $notice = "";
		// $model=new LoginForm;
		// if(isset(Yii::app()->user->id)){
			// $this->redirect(Yii::app()->homeUrl);
		// }
		// // collect user input data
		// if(isset($_POST['email']))
		// {
			// $model->attributes=$_POST;
			// // validate user input and redirect to the previous page if valid
			// if($model->validate() && $model->login()){
                // $success =  true;
				// $adminRules = array(User::ROLE_ADMIN, User::ROLE_MONITOR);
				// $userRole = Yii::app()->user->role;
				// $urlModule = in_array($userRole, $adminRules)? 'admin': str_replace("role_","",$userRole);
                // $url = Yii::app()->baseurl."/".$urlModule;
                // $notice ="Đăng nhập thành công... ".Common::windowLocationJs($url);
			// }else{
                // $notice =str_replace(",","<br/>",array_values($model->getErrors()));
			// };
		// }
		// // display the login form
		// $this->renderJSON(array(
            // 'success'=>$success,
            // 'htmlTag'=>$htmlTag,
            // 'notice'=>$notice
        // ));
	// }
	
	//Popup forgot password
    public function actionForgotPassword()
    {
    	$this->subPageTitle = 'Quên mật khẩu';
        $success = false;
        $htmlTag = ".noticeForm";
        $notice = "Vui lòng nhập đầy đủ thông tin.";

        if(isset($_POST['email']))
        {
            $user = User::model()->findByAttributes(array('email'=>$_POST['email']));
            if(isset($user->id)):
                $clsMailer = new ClsMailer();
                $code = sha1(mt_rand(10000,99999).time().$user->email);
                $mail = $clsMailer->forgotPassword($_POST['email'],$code);
                if($mail){
                    $user->reset_password_code = $code;
                    $user->reset_password_expired = date('Y-m-d H:i:s', time('now')+7*86400);
                    if($user->save())
                    {
                        $success  = true;
                        $notice = '<script>noticeForm("Thông tin xác minh mật khẩu mới đã được gửi về địa chỉ email. Xin vui lòng kiểm tra email");</script>';
                    }
                }else{
                    $notice = "Gửi mã xác nhận thất bại, xin vui lòng kiểm tra lại.";
                }
            else:
                $notice = "Email này không tồn tại. Xin vui lòng kiểm tra lại.";
            endif;
        }
        // display the login form
        $this->renderJSON(array(
            'success'=>$success,
            'htmlTag'=>$htmlTag,
            'notice'=>$notice
        ));
    }
    
    //Reset password popup
    public function actionResetPassword($code)
    {
    	$this->subPageTitle = 'Thiết lập lại mật khẩu';
        $class = Classes::model()->findAll();
        $this->render('index',array("class"=>$class));
        if($code){
            $user = User::model()->findByAttributes(array('reset_password_code'=>$code));
            if(isset($user->id) && $user->reset_password_expired>=date('Y-m-d H:i:s')){
                $profile = "<div class='profile_email'>".$user->email."</div>";
                echo '<script>formChangePassword("'.$profile.'","'.$code.'");</script>';
            }else{
                echo '<script>noticeForm("Chúng tôi không thể xác định được yêu cầu của bạn.");</script>';
            }
        }
    }
    
    //Change password popup
    public function actionChangePassword($code)
    {
    	$this->subPageTitle = 'Thay đổi mật khẩu';
        $success = false;
        $htmlTag = ".noticeForm";
        $notice = "Vui lòng nhập đầy đủ thông tin.";

        if($code and isset($_POST['password']) and $_POST['password'] !=""){
            $user = User::model()->findByAttributes(array('reset_password_code'=>$code));
            if(isset($user->id) && $user->reset_password_expired>=date('Y-m-d H:i:s')){
                $user->password = Common::passwordCrypt($_POST['password']);
                $user->reset_password_code = null;
                $user->reset_password_expired = null;
                if($user->save()){
                    $success = true;
                    $link = "<a href='#' class='accountLogin'>Đăng nhập</a>";
                    $notice = '<script>noticeForm("Mật khẩu của bạn đã được thay đổi ('.$link.').");</script>';
                }else{
                    $notice = array_values($user->getErrors());
                }
            }else{
                $notice = '<script>noticeForm("Chúng tôi không thể xác định được yêu cầu của bạn.");</script>';
            }
        }

        // display the login form
        $this->renderJSON(array(
            'success'=>$success,
            'htmlTag'=>$htmlTag,
            'notice'=>$notice
        ));
    }
    
	//Ajax Check enter board time of Student, Teacher, Support
	public function actionEnterBoard()
	{
        $userId = Yii::app()->user->id;
        $userRole = Yii::app()->user->role;
        $whiteboard = $_REQUEST['whiteboard'];//Whiteboard
        $session = Session::model()->findByAttributes(array('whiteboard'=>$whiteboard));
        if(isset($session->id)){
        	//Only check & save enter time in first time
        	if($userRole==User::ROLE_TEACHER && $userId==$session->teacher_id){
        		if(!$session->teacher_entered_time || $session->plan_start>=date('Y-m-d H:i:s')){
        			$session->teacher_entered_time = date('Y-m-d H:i:s');
        			$session->save();
        		}
        	}elseif($userRole==User::ROLE_STUDENT || $userRole==User::ROLE_TEACHER){
        		$sessionStudent = SessionStudent::model()->findByAttributes(array('student_id'=>$userId, 'session_id'=>$session->id));
        		//Check & save attended time of student
        		if(isset($sessionStudent->id) && !$sessionStudent->attended_time){
        			$sessionStudent->attended_time = date('Y-m-d H:i:s');
        			$sessionStudent->save();
        		}
        	}
		}
		$this->renderJSON(array('success'=>true));
	}
	
	/**
	 * Get data layer to wordpress page
	 */
	public function actionAjaxGetDatalayer()
	{
		if(Yii::app()->request->isAjaxRequest){
			$urlReferrer = Yii::app()->request->urlReferrer;
			$baseProtocolUrl = Yii::app()->getRequest()->getBaseUrl(true);
			if(strpos($urlReferrer, $baseProtocolUrl)!==false){
				echo $this->renderPartial('/site/widget/dataLayer');
			}
		}
		echo "";//echo empty string
	}

	/**
	 * Check upload access file
	 */
	public function actionCheckUploadAccess()
	{
		$success = false;
		if(Yii::app()->request->isAjaxRequest){
			$urlReferrer = Yii::app()->request->urlReferrer;
			$baseProtocolUrl = Yii::app()->getRequest()->getBaseUrl(true);
			if(strpos($urlReferrer, $baseProtocolUrl)!==false){
				if(isset(Yii::app()->user->id) && isset(Yii::app()->user->role)){
					if(in_array(Yii::app()->user->role, array(User::ROLE_ADMIN, User::ROLE_MONITOR, User::ROLE_TEACHER))){
						$success = true;
					}
				}
			}
		}
		$this->renderJSON(array('success'=>$success));
	}

    //Logout 
	public function actionLogout()
	{
		Yii::app()->user->logout(true);
		$this->redirect(Yii::app()->homeUrl);
	}

}
