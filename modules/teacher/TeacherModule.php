<?php

class TeacherModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
            'teacher.assets.*',
			'teacher.models.*',
			'teacher.components.*',
            'student.components.*',
			'admin.classes.*',
            'student.classes.*',
            'student.actions.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
        //Check loggin user is admin user
        if(!(Yii::app()->user->getId() && Yii::app()->user->role==User::ROLE_TEACHER))
        {
            $controller->redirect(Yii::app()->homeUrl);
        }elseif(isset(Yii::app()->user->id)){
        	Yii::app()->session['loggedUserId'] = Yii::app()->user->id;
        	Yii::app()->session['checkAccessUploadUsers'] = false;
        }
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
            $controller->layout = '//layouts/teacher';
            Yii::app()->language = 'vi';//Config admin language is Vietnamese
			return true;
		}
		else
			return false;
	}
}
