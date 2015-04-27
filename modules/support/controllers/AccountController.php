<?php
class AccountController extends Controller {
	//Account information
    public function actionIndex() {
		$this->subPageTitle = 'Thông tin tài khoản';
		$userId = Yii::app()->user->id;
        $model = User::model()->findByPk($userId);
    	if(isset($_POST['User']))
        {
            $common = new Common();
            $formValue = $_POST['User'];
            $model->attributes = $formValue;
            $model->role = User::ROLE_SUPPORT;
        	//Check validate date format
        	$birthday = $_POST['birthday'];//Post birthday part
            $model->birthday = $birthday['year']."-".$birthday['month']."-".$birthday['date'];
            if(!Common::validateDateFormat($model->birthday)){
            	$model->birthday = NULL;
            }
        	//Check validate phone number
            if(!Common::validatePhoneNumber($model->phone)){
	            $model->phone = NULL;
            }
            $dir = "media/uploads/profiles";
            $profilePicture = $common->uploadProfilePicture("profilePicture",$dir);
            if($profilePicture){
                $model->profile_picture =$profilePicture;
            }
            $model->save();
        }
        $this->render('index',array("model"=>$model));
    }

}