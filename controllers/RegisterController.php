<?php

class RegisterController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'vi';
        $this->layout = '//layouts/blank';
    }

	public function actionContact()
	{
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
            
            if (isset($_POST['referer'])){
                $model->source = $_REQUEST['referrer'];
            }
			
			if ($model->save()){
				$success = true;
				if ($model->id == null){
					$model->id = $model->getPrimaryKey();
				}
			}
		}
		
		$this->renderJSON(array("success"=>$success, "model"=>$model));
	}
    
    public function actionGetPreregisterUser(){
        if (isset($_REQUEST['id'])){
            $user = PreregisterUser::model()->findByPk($_REQUEST['id']);
            $this->renderJSON(array('user'=>$user));
        }
    }
}