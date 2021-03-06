<?php

class StudentModule extends CWebModule{
    public function init()
    {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application

        // import the module-level models and components
        $this->setImport(array(
            'student.classes.*',
            'student.models.*',
            'student.components.*',
        	'admin.classes.*',
        ));
    }
	
    public function beforeControllerAction($controller, $action)
    {
		$user = Yii::app()->user->model;
		if (!isset($user->active_session) || !isset($_SESSION['active_session']) || $user->active_session != $_SESSION['active_session'])
		{
			Yii::app()->user->logout(true);
		}
		
        //Check loggin user is admin user
        if(!(Yii::app()->user->getId() && Yii::app()->user->role==User::ROLE_STUDENT))
        {
        	$otherAccessRoles = User::monitorRoles();
        	if(!(in_array(Yii::app()->user->role, $otherAccessRoles) && $controller->id=='quiz')){
            	$controller->redirect(Yii::app()->homeUrl);
        	}
        }elseif(isset(Yii::app()->user->id)){
        	Yii::app()->session['loggedUserId'] = Yii::app()->user->id;
        	Yii::app()->session['checkAccessUploadUsers'] = false;
        }
        //Redirect to status param page to tracking
    	// if(!(isset($_GET['status']) || strpos(Yii::app()->request->requestUri, "?")>0)){
    		// $trueBaseUrl = Yii::app()->getRequest()->getBaseUrl(true);
    		// if(!(isset($_POST) && count($_POST)>0)){
	        	// $controller->redirect($trueBaseUrl.Yii::app()->request->requestUri.'?status='.Yii::app()->user->status);
    		// }
		// }
        if(parent::beforeControllerAction($controller, $action))
        {
            // this method is called before any module controller action is performed
            // you may place customized code here
            $controller->layout = '//layouts/student';
            Yii::app()->language = $user->language;//Config admin language is Vietnamese
            return true;
        }
        else
            return false;
    }
}