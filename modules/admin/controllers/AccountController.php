<?php

class AccountController extends Controller
{

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index', 'changePassword'),
				'users'=>array('*'),
				'expression' => 'Yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionChangePassword()
	{
		$this->subPageTitle = 'Thay đổi mật khẩu hệ thống';
		$adminUserId = Yii::app()->user->id;
		$params = array();
		$user = User::model()->findByPk($adminUserId);
		if(isset($_POST['User']))
		{
			$info = $_POST['User'];
			$user->passwordSave = $info['passwordSave'];
            $user->repeatPassword = $info['repeatPassword'];
			if($user->password != crypt($info['password'],$user->password)){
                $params['errorPassword'] = "Mật khẩu cũ không chính xác!";
            }elseif(trim($user->passwordSave=="") || ($user->passwordSave != $user->repeatPassword)){
                $params['errorRepeatPassword'] = "Vui lòng nhập mật khẩu mới và xác nhận lại mật khẩu mới!";
            }elseif(strlen($user->passwordSave)<6){
                $params['errorPasswordSave'] = "Mật khẩu mới phải ít nhất 6 ký tự!";
            }else{
            	$user->save();
            	$params['successMsg'] = "Thay đổi mật khẩu mới thành công!";
            }
		}
		$this->render('changePassword',$params);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


}
