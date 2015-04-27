<?php
/**
 * NganluongComponent class
 */
class ClsNganluong extends CApplicationComponent
{
    public $nlLocation = 'application.vendor.nganluong.nganluong';
    public $nlLocationv3 = 'application.vendor.nganluong.NL_Checkoutv3';
    public $nlMobiCardLocation = 'application.vendor.nganluong.MobiCard';
    public $nusoapLocation = 'application.vendor.nusoap.lib.nusoap';
    public $nlCheckout;//nlCheckout
    public $nlCheckoutv3;//nlCheckoutv3
    public $nlMobicard;//nlMobicard
    public $receiver;//Receiver email
    public $setExpressCheckout = false;//Sandbox mode
    
    public function __construct()
    {
        // Google API client
        $nlConfig = Yii::app()->params['nganluong'];
        Yii::import($this->nusoapLocation, true);//Nusoap service client
        Yii::import($this->nlLocation, true);//Nganluong location
        Yii::import($this->nlLocationv3, true);//Nganluong location v3
        Yii::import($this->nlMobiCardLocation, true);//Nganluong mobicard        
        //Setup config values
        $nganluongUrl = $nlConfig['nganluongUrl'];//Nganluong url
        $merchantSiteCode = $nlConfig['merchantSiteCode'];//merchantSiteCode
        $securePass = $nlConfig['securePass'];//securePass
        $this->receiver = $nlConfig['receiver'];//Receiver email
        if(isset($nlConfig['setExpressCheckout']) && $nlConfig['setExpressCheckout']){
        	$this->setExpressCheckout = true;//Main checkout Nganluong, setExpressCheckout
        }
        //Init some object from Nganluong class
       	$this->nlCheckout = new NL_Checkout($nganluongUrl, $merchantSiteCode, $securePass);
       	$this->nlCheckoutv3 = new NL_CheckOutV3($merchantSiteCode, $securePass, $this->receiver);
       	$this->nlMobicard = new MobiCard($merchantSiteCode, $securePass, $this->receiver);
    }
    
    /**
     * Generate nganluong checkout url
     */
    public function generateCheckoutUrl($preCourse)
    {
    	if($preCourse->payment_type==PreregisterCourse::PAYMENT_TYPE_NOT_FREE 
    		&& $preCourse->payment_status==PreregisterCourse::PAYMENT_STATUS_PENDING && $preCourse->final_price>0)
    	{
    		$remainAmount = $preCourse->getRemainPaymentAmount();//Remain amount has payment
    		$returnUrl = Yii::app()->getRequest()->getBaseUrl(true).'/student/payment/nganluong/id/'.$preCourse->id;
	        $orderCode = 'PC'.$preCourse->id.'-ST'.$preCourse->student_id;
	        $transactionInfo = "Trả tiền cho khóa học có mã ".$orderCode;
	        $checkoutUrl = $this->nlCheckout->buildCheckoutUrl($returnUrl, $this->receiver, $transactionInfo,  $orderCode, $remainAmount);
	        return $checkoutUrl;
    	}
    	return NULL;    	
    }
    
    /**
     * Generate checkout by Nganluong
     */
    public function generateCheckoutv3($user, $preCourse, $paymentMethod='NL', $bankCode='')
    {
    	$nlResult = NULL;
    	$baseUrl = Yii::app()->getRequest()->getBaseUrl(true);//Base url with protocol
    	$returnUrl = $baseUrl.'/student/payment/nganluong/id/'.$preCourse->id;
    	$remainAmount = $preCourse->getRemainPaymentAmount();//Remain amount has payment
    	$arrayItems[0] = array('item_name1'=>$preCourse->title, 'item_quantity1'=>1, 'item_amount1'=>$remainAmount, 'item_url1'=>$baseUrl);
    	//Check nl result
    	if($preCourse->payment_type==PreregisterCourse::PAYMENT_TYPE_NOT_FREE 
    		&& $preCourse->payment_status==PreregisterCourse::PAYMENT_STATUS_PENDING && $remainAmount>0)
    	{
	    	$orderCode = 'PC'.$preCourse->id.'-ST'.$preCourse->student_id;
	    	$orderDescription = "Trả tiền cho khóa học có mã ".$orderCode;
	    	//Update Preregister course
	    	$preCourse->order_code = $orderCode;
	    	$preCourse->save();//Save order course
	    	if($paymentMethod == "VISA")
	    	{
				$nlResult = $this->nlCheckoutv3->VisaCheckout($orderCode, $remainAmount, 1, $orderDescription, 0,
					 0,0,$returnUrl, $returnUrl, $user->fullName(), $user->email, $user->phone, $user->address, $arrayItems);
			}elseif($paymentMethod == "NL")
			{
				$nlResult = $this->nlCheckoutv3->NLCheckout($orderCode,$remainAmount,1,$orderDescription,0,
					0,0,$returnUrl,$returnUrl,$user->fullName(), $user->email, $user->phone, $user->address, $arrayItems);
													
			}elseif(($paymentMethod=="ATM_ONLINE" || $paymentMethod=="NH_OFFLINE") && $bankCode !='')
			{
				$nlResult = $this->nlCheckoutv3->BankCheckout($orderCode,$remainAmount,$bankCode,1,$orderDescription,0,
					0,0,$returnUrl,$returnUrl,$user->fullName(),$user->email,$user->phone, $user->address,$arrayItems, $paymentMethod) ;
			}
    	}
    	return $nlResult;
    }
    
    /**
     * Checkout payment by Mobicard
     */
    public function mobiCardCheckout($user, $preCourse, $soseri, $sopin, $typeCard)
    {
   		$nlResult = new Result();//Nganluong result
   		$orderCode = 'PC'.$preCourse->id.'-ST'.$preCourse->student_id;
   		$nlResult = $this->nlMobicard->CardPay($sopin,$soseri,$typeCard,$orderCode,$user->fullName(),$user->phone,$user->email);
   		return $nlResult;
    }
    
    /**
     * BankCode array to select
     */
    public static function bankCodeOptions()
    {
    	return array(
    		'VCB' => 'Vietcombank - Ngân hàng TMCP Ngoại Thương Việt Nam',
    		'AGB' => 'Agribank - Ngân hàng Nông nghiệp và Phát triển nông thôn',
    		'SCB' => 'Sacombank - Ngân hàng Sài Gòn Thương tín',
    		'ACB' => 'ACB - Ngân hàng Á Châu',
    		'TCB' => 'Techcombank - Ngân hàng Kỹ Thương',
    		'VPB' => 'VPBank - Ngân Hàng Việt Nam Thịnh Vượng',
    		'BIDV' => 'BIDV - Ngân hàng Đầu tư và Phát triển Việt Nam',
    		'ICB' => 'VietinBank - Ngân hàng Công Thương Việt Nam',
	    	'DAB' => 'DongA Bank - Ngân hàng Đông Á',	    	
	    	'MB' => 'MBBank - Ngân hàng Quân Đội',
	    	'SHB' => 'Saigon-Hanoi Bank - Ngân hàng Sài Gòn - Hà Nội',
	    	'VIB' => 'VIB - Ngân hàng Quốc tế',	    	
	    	'EXB' => 'Eximbank - Ngân hàng Xuất Nhập Khẩu',	    	
	    	//'HDB' => 'HDBank - Ngân hàng Phát triển Nhà TPHCM',
	    	'MSB' => 'Maritime Bank - Ngân hàng Hàng Hải',
	    	//'NVB' => 'Navibank - Ngân hàng Nam Việt',
	    	//'VAB' => 'VAB - Ngân hàng Việt Á',
    		'OJB' => 'OceanBank - Ngân hàng Đại Dương',
    		'PGB' => 'PG Bank - Ngân hàng Xăng dầu Petrolimex',
    		//'GPB' => 'GP Bank - Ngân hàng TMCP Dầu khí Toàn Cầu',    		
    		//'SGB' => 'Saigon Bank - Ngân hàng Sài Gòn Công Thương',
    		//'NAB' => 'Nam A Bank - Ngân hàng Nam Á',
    		'BAB' => 'Bac A Bank - Ngân hàng Bắc Á',
    	);
    }
    
    /**
     * Mobi card payment options
     */
    public static function mobiCardOptions()
    {
    	return array(
	    	'VMS' => 'Thẻ cào Mobifone',
	    	'VNP' => 'Thẻ cào Vinaphone',
	    	'VIETTEL' => 'Thẻ cào Viettel',
    	);	
    }

}
