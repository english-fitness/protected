<?php
/**
 * HocmaiComponent class
 */
class ClsHocmai extends CApplicationComponent
{
	public $nusoapLocation = 'application.vendor.nusoap.lib.nusoap';
	private $ipCall = '103.1.209.6';//Ip call webservice
	private $HmUidKey = '32+#H!TF+KBRckZ';//Hoc mai UID
		
	public function __construct()
    {
        // Attach nusoap location
        Yii::import($this->nusoapLocation, true);
    }
    
	//DecID secure function
	function decID($id, $ID = 900000000) {
	    $id_decode = hexdec($id) - $ID;
	    return $id_decode;
	}
	//HexID secure function
	function hexID($id, $ID = 900000000) {
	    $id_encode = dechex($ID + $id);
	    return strtoupper($id_encode);
	}
	//Hex to str secure function
	function hexToStr($hex) {
	    $string = '';
	    for ($i = 0; $i < strlen($hex) - 1; $i+=2) {
	        $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
	    }
	    return $string;
	}
	//str to hex secure function
	function strToHex($string) {
	    $hex = '';
	    for ($i = 0; $i < strlen($string); $i++) {
	        $hex .= dechex(ord($string[$i]));
	    }
	    return $hex;
	}
	
	/**
	 * 
	 * Connect to hocmai
	 * @param $username
	 * @param $tokenOrPass
	 * @param $connectType, password or token
	 */
	public function connectToHocmai($username, $tokenOrPass, $connectType='password')
	{
		$client = new nusoap_client('http://hocmai.vn/webservice/daykem/srvcs.php?wsdl', false, false, false, false, false, 5, 10);

		$usernameHex = $this->strToHex($username);
		$tokenOrPassHex = $this->strToHex(md5($tokenOrPass));
		$webServiceName = "userLogin";//Webservice name
		if($connectType=='token'){
			$webServiceName = 'userLoginToken';
			$tokenOrPassHex = $this->strToHex($tokenOrPass);
		}
		$loginParams = array(
			'username' => $usernameHex,
			"$connectType" => $tokenOrPassHex,
			'datasignal' => md5($usernameHex . $tokenOrPassHex . $this->ipCall),
		);
		$return =  $client->call($webServiceName, $loginParams);
		$params = array();//Init hocmai params
		$userData = array();//Init hocmai Userdata
		$returnFields = array('id','email','username','password','gender','fullname','province','phone','mobile','address');
		/***
		 * $return data (da ma hoa): hex cua chuoi co format id=email=username=pass=type=fullname=gender=province=address=phone=mobile
		 * 		id: user dang ky
		 * 		email: la email user dang ky
		 * 		username: user dang nhap
		 * 		pass: mat khau user dang ky
		 * 		type: 1=phu huynh, 2=hoc sinh, 3=giao vien
		 * 		fullname: ten day du cua user
		 * 		gender: gioi tinh
		 * 		province: tinh thanh dang alias, vd: ha-noi, hai-phong, ho-chi-minh...
		 * 		phone: dt co dinh
		 * 		mobile: dt di dong
		 * 		address: dia chi
		 ***/
		$responseStr = $this->hexToStr($return);
		$hmUser = json_decode($responseStr);
		if(isset($hmUser->username) && trim($hmUser->username)!=""){
			foreach($returnFields as $field){
				$userData[$field] = (isset($hmUser->$field) && trim($hmUser->$field)!="")? $hmUser->$field: NULL;
				if($field=='id' && isset($hmUser->userid)){//Hocmai user id
					$userData['id'] = $this->decID($hmUser->userid, $this->HmUidKey);
				}
			}
			if(isset($hmUser->pass)) $userData['password'] = $hmUser->pass;//Hocmai password
			if($userData['email']==NULL) $userData['email'] = $userData['username'].'@hocmai.vn';
			if($userData['gender']==NULL) $userData['gender'] = 1;//Default gender
			$params['userData'] = $userData;//Set hocmai user data
			$params['hocmaiConnected'] = ($userData['username']!=NULL)? true: false;//Hocmai connected
		}else{
			$params['hocmaiConnected'] = false;//Hocmai not connected
		}
		return $params;
	}
	
    
}
