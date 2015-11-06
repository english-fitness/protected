<?php

class AdminModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
			'admin.classes.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		//Check loggin user is admin user
		$adminRules = User::adminRoles();
		if(!(Yii::app()->user->getId() && in_array(Yii::app()->user->role, $adminRules)))
		{
			$quizControllers = array('quizTopic', 'quizExam', 'quizItem');
			if(!(Yii::app()->user->role==User::ROLE_SUPPORT && in_array($controller->id, $quizControllers))){
				$controller->redirect(Yii::app()->homeUrl);
			}
		}
		$loggedUserId = Yii::app()->user->id;
		Yii::app()->session['loggedUserId'] = $loggedUserId;
		Yii::app()->session['checkAccessUploadUsers'] = true;
		//Check allow permission by user permission
		if(Yii::app()->user->role!=User::ROLE_ADMIN){
			$checkAllowAccess = Permission::model()->checkAllowUserAccess($loggedUserId, $controller->id, $action->id);
			if(!$checkAllowAccess){
				$controller->redirect('/admin/default/permission');
			}
		}
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			$controller->layout = '//layouts/admin';
			Yii::app()->language = 'vi';//Config admin language is Vietnamese
			return true;
		}
		else
			return false;
	}
}
