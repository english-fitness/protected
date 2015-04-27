<?php

class PriceDiscountPercent
{
	/**
	 * Price discount config for resigister soon
	 */
	public function discountPriceTable()
	{
		$discountPriceTable = array(
			'class1_1'=> array(
				'step1'=> array('description'=>'Đóng tiền ngay trong thời gian học thử', 'next_price'=>135000, 'user_status'=>User::STATUS_TRAINING_SESSION, 'in_number_day'=>365),
				'step2'=> array('description'=>'Đóng tiền ngay không cần học thử', 'next_price'=>125000, 'user_status'=>User::STATUS_REGISTERED_COURSE, 'in_number_day'=>365),
			),
			'class1_2'=> array(
				'step1'=> array('description'=>'Đóng tiền ngay trong thời gian học thử', 'next_price'=>85000, 'user_status'=>User::STATUS_TRAINING_SESSION, 'in_number_day'=>365),
				'step2'=> array('description'=>'Đóng tiền ngay không cần học thử', 'next_price'=>75000, 'user_status'=>User::STATUS_REGISTERED_COURSE, 'in_number_day'=>365),
			),
			'class1_3'=> array(
				'step1'=> array('description'=>'Đóng tiền ngay trong thời gian học thử', 'next_price'=>70000, 'user_status'=>User::STATUS_TRAINING_SESSION, 'in_number_day'=>365),
				'step2'=> array('description'=>'Đóng tiền ngay không cần học thử', 'next_price'=>60000, 'user_status'=>User::STATUS_REGISTERED_COURSE, 'in_number_day'=>365),
			),
		);
		$discountPriceTable['class1_4'] = $discountPriceTable['class1_3'];//Class 1-4
		$discountPriceTable['class1_5'] = $discountPriceTable['class1_3'];//Class 1-5
		$discountPriceTable['class1_6'] = $discountPriceTable['class1_3'];//Class 1-6
		return $discountPriceTable;
	}
	
    /**
     * Discount price for register soon
     */
    public function discountForRegisterSoon($currentPrice, $params, $step='step1', $user)
    {
    	$priceTable = $this->discountPriceTable();//Discount price table
    	$configKey = 'class1_'.$params['total_of_student'];
    	$configStep = $priceTable[$configKey][$step];//Config Step discount
    	//Check logged in status & calculate price
    	if(!(isset($user) && $user->role==User::ROLE_STUDENT)){
    		return false;
    	}
    	$statusDate = $user->getStatusDate();
    	$checkConditionDate = date('Y-m-d H:i:s', time('now')-$configStep['in_number_day']*86400);
    	if($user->status<=$configStep['user_status'] && $statusDate>=$checkConditionDate
    		&& $statusDate<=date('Y-m-d H:i:s'))
    	{
    		return array(
            	'next_price'=>$configStep['next_price'],
            	'description'=>$configStep['description']
            );
    	}
        return false;
    }
    
    /**
     * Discount price for register soon step1
     */
    public function discountForRegisterSoonStep1($currentPrice, $params, $user)
    {
    	return $this->discountForRegisterSoon($currentPrice, $params, 'step1', $user);
    }
    
	/**
     * Discount price for register soon step2
     */
    public function discountForRegisterSoonStep2($currentPrice, $params,$user)
    {
    	return $this->discountForRegisterSoon($currentPrice, $params, 'step2', $user);
    }
    
}