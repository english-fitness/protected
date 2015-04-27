<?php

class CartModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'cart.models.*',
			'cart.components.*',

		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action)  && Yii::app()->user->role==User::ROLE_ADMIN)
		{
            Yii::app()->getClientScript()->registerCoreScript('jquery');
            Yii::app()->theme = 'cart-admin';
            // this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return $controller->redirect('/admin/default/permission');;
	}
}
