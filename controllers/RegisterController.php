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
            }

            try {
            	$cache = Yii::app()->cache;
            	// $cache->flush();
            	$availableSalesStaffs = $cache->get('availableSalesStaffs');
            	if ($availableSalesStaffs === false){
            		$availableSalesStaffs = ClsUser::getAvailableSalesStaff();
            		$cache->add('availableSalesStaffs', $availableSalesStaffs);
            	}
            	$lastAssignedSale = $cache->get('lastAssignedSale');
            	$currentSale = array_search($lastAssignedSale, $availableSalesStaffs);
            	if ($lastAssignedSale === false || $currentSale == -1 || $currentSale == count($availableSalesStaffs) - 1){
            		$lastAssignedSale = $availableSalesStaffs[0];
            	} else {
            		$lastAssignedSale = $availableSalesStaffs[$currentSale + 1];
            	}
            	$model->sale_user_id = $lastAssignedSale;
            	$cache->set('lastAssignedSale', $lastAssignedSale);
            } catch (Exception $e) {
            	
            }

			if ($model->save()){
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