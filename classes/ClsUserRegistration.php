<?php
class ClsUserRegistration {
	public static function getNextSaleStaff($assign=true){
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
    	if ($assign){
	    	$cache->set('lastAssignedSale', $lastAssignedSale);
	    }

    	return $lastAssignedSale;
	}

	public static function findDuplicate($phone='', $email=''){
		//may be we don't need values binding at all
		//since it should be validated before sending here
		//but let's just do it
		$select = array();
		$values = array();
		if (!empty($phone)){
			$select[] = "(SELECT sale_user_id FROM tbl_preregister_user WHERE phone=:phone AND deleted_flag=0 LIMIT 1) AS phone_duplicate";
			$values['phone'] = $phone;
		}
		if (!empty($email)){
			$select[] = "(SELECT sale_user_id FROM tbl_preregister_user WHERE email=:email AND deleted_flag=0  LIMIT 1) AS email_duplicate";
			$values['email'] = $email;
		}

		if ($select != ''){
			$query ="SELECT ".implode(',',$select);
			return Yii::app()->db->createCommand($query)->bindValues($values)->queryRow();
		}

		return null;
	}

	public static function countDuplicate($phone='', $email=''){
		//may be we don't need values binding at all
		//since it should be validated before sending here
		//but let's just do it
		$select = array();
		$values = array();
		if (!empty($phone)){
			$select[] = "SUM(phone = :phone AND deleted_flag=0) AS phone_duplicate";
			$values['phone'] = $phone;
		}
		if (!empty($email)){
			$select[] = "SUM(email = :email AND deleted_flag=0) AS email_duplicate";
			$values['email'] = $email;
		}

		if ($select != ''){
			$query ="SELECT ".implode(',',$select)." FROM ".PreregisterUser::model()->tablename();
			return Yii::app()->db->createCommand($query)->bindValues($values)->queryRow();
		}

		return null;
	}
}