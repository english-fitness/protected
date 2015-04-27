<?php

class SupportModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'support.models.*',
			'support.components.*',
            'admin.classes.*',
            'student.components.TestCondition',
		));
		Yii::app()->getClientScript()->scriptMap = array(
        	'jquery.yiigridview.js' => Yii::app()->baseUrl . '/media/js/jquery/jquery.yiigridview.js',
		);
	}

	public function beforeControllerAction($controller, $action)
	{
        //Check loggin user is admin user
        if(!(Yii::app()->user->getId() && Yii::app()->user->role==User::ROLE_SUPPORT))
        {
            $controller->redirect(Yii::app()->homeUrl);
        }
		Yii::app()->session['checkAccessUploadUsers'] = false;
		if(parent::beforeControllerAction($controller, $action))
		{
            // this method is called before any module controller action is performed
            // you may place customized code here
            $controller->layout = '//layouts/support';
            Yii::app()->language = 'vi';//Config admin language is Vietnamese
            return true;
		}
		else
			return false;
	}
}
