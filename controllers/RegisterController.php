<?php

class RegisterController extends Controller
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
	public function actionContact()
	{
		$this->subPageTitle = 'Đăng ký thông tin tư vấn';
		$model = new PreregisterUser;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['PreregisterUser']))
		{
			$preUserValues = $_POST['PreregisterUser'];
			$model->attributes = $preUserValues;
			if($model->save()){
				$this->redirect(array('/register/contact?status=success'));
			}
		}
		$this->render('/site/preregister', array(
			'model'=>$model,
		));
	}
	
}