<?php

class RegisterController extends Controller
{
    public function  init()
    {
        Yii::app()->language = 'vi';
        $this->layout = '//layouts/blank';
    }

	public function actionContact()
	{
        if (isset($_COOKIE['utmParams'])){
            $utmParams = json_decode($_COOKIE['utmParams'], true);
        }
        
		$success = false;
		
		if(isset($_POST['PreregisterUser']))
		{
			$preUserValues = $_POST['PreregisterUser'];
			if (isset($preUserValues['id'])){
				$model = PreregisterUser::model()->findByPk($preUserValues['id']);
			} else {
				$model = new PreregisterUser;
			}
		
			// Uncomment the following line if AJAX validation is needed
			// $this->performAjaxValidation($model);
			// $preUserValues = $_POST['PreregisterUser'];
			$model->attributes = $preUserValues;
			
			if (isset ($preUserValues['wday'])){
				$weekday = $preUserValues['wday'];
				if (preg_match('/([0-9]{1}\s*,+\s*)+/', $weekday)){
					$model->weekday = $weekday;
				}
			}
			
            $timerangeRegex = '/([01]?[0-9]|2[0-3]):[0-5][0-9]/';
            
			if (isset ($preUserValues['timerange_from']) && ($preUserValues['timerange_to'])){
				if (preg_match($timerangeRegex ,$preUserValues['timerange_from']) && 
					preg_match($timerangeRegex, $preUserValues['timerange_to'])){
					$model->timerange = $preUserValues['timerange_from'] . " - " . $preUserValues['timerange_to'];
				}
			}
            
            if (isset($_REQUEST['referrer'])){
                $model->source = $_REQUEST['referrer'];
            } else {
                $model->source = 'Online';
            }

            if ($model->validate()){
                //remove all space in phone number so we can do searches in db
                //duplicate with model before save but we need it so just do it anyway
                $model->phone = preg_replace('/\s+/', '', $model->phone);
                $model->phone = str_replace('+84', '0', $model->phone);
                $possibleDuplicate = ClsUserRegistration::findDuplicate($model->phone, $model->email);
                if ($possibleDuplicate['phone_duplicate'] == null && $possibleDuplicate['email_duplicate'] == null){
                    $model->sale_user_id = ClsUserRegistration::getNextSaleStaff();
                } else {
                    if ($possibleDuplicate['phone_duplicate'] != null){
                        $model->phone_duplicate = true;
                        $model->sale_user_id = $possibleDuplicate['phone_duplicate'];
                    }
                    if ($possibleDuplicate['email_duplicate'] != null){
                        $model->email_duplicate = true;
                        $model->sale_user_id = $possibleDuplicate['email_duplicate'];
                    }
                }
            }

            //no need to validate again since we have just done it right above
			if ($model->save(false)){
                if (isset($utmParams)){
                    $utmStat = new UtmSaleStat;
                    $utmStat->register_id = $model->id;
                    $utmStat->attributes = $utmParams;
                    $utmStat->save();
                }
				$success = true;
			}
		}
		
		// $this->renderJSON(array("success"=>$success, "model"=>$model, 'error'=>json_encode($model->getErrors())));
		$this->renderJSON(array("success"=>$success));
	}
    
    public function actionGetPreregisterUser(){
        if (isset($_REQUEST['id'])){
            $user = PreregisterUser::model()->findByPk($_REQUEST['id']);
            $this->renderJSON(array('user'=>$user));
        }
    }
}