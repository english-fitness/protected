<?php

class QuizController extends Controller
{

    public function  init()
    {
        $this->layout = '//layouts/blank';
    }

    //Facebook login from Quiz page
    public function actionLogin()
    {
    	$this->subPageTitle = 'Đăng nhập bằng Facebook';
        $facebook = new ClsFacebook();
        $params = $facebook->connectToFacebook(true);
        if($params['facebookConnected']){
        	$checkLoginByFB = User::model()->loginByFacebook($params['userData']);
        	if(!$checkLoginByFB){
        		$user = User::model()->createUserByGoogleFB($params['userData'], 'facebook');
        		$checkLoggedIn = User::model()->UserApiIdentity($user->id);
        	}
            $like = $facebook->fb->api('/me/likes/'.$facebook->pageId);
        	if($like && $like['data']){//If like page
                $this->redirect(array('index'));
            }else{
                $this->render('like');
            }
        }else{//If not connected
	    	$this->render('login',array('appId'=>$facebook->appId));
        }
    }

    //Quiz index - list exam
    public function actionIndex()
    {
        $facebook = new ClsFacebook();
        $user = $facebook->fb->getUser();
        if($user) {
            print_r($user);
        }
    }

}