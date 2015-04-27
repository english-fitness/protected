<?php
class TestConditions extends Browser {

    /* Supported browsers*/
    public $browsersSupport = array(
        array(
            'name'=>'Firefox',
            'version'=>27
        ),
        array(
            'name'=>'Chrome',
            'version'=>29
        )
    );
    public static $IO = array(
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
    );
    
    public static  function  getOS() {
        $agent =   $_SERVER['HTTP_USER_AGENT'];
        $result = null;
        foreach (self::$IO as $regex => $value) {
            if (preg_match($regex, $agent)) {
                $result    =   $value;
            }
        }
        return $result;
    }
    //Get browser support
    public function getBrowserSupport() {
        $browser = null;
        foreach($this->browsersSupport as $item) {
            if($item['name']==$this->getBrowser()) {
                $browser = $item;
            }
        }
        return $browser;
    }
    //Get notice
	public  function getNotice() {
       $browser = $this->getBrowserSupport();
       $view = null;
       if($browser===null) {
           $view['content'] = '<span class="error">Trình duyệt của bạn không được hỗ trợ!</span>';
       }else if($browser['version'] > $this->getVersion()) {
           $view['content'] = '<span class="error">Phiên bản trình duyệt của bạn không được hỗ trợ, xin vui lòng nâng cấp phiên bản cao hơn!</span>';
       }
       return $this->tempNotification($view);
	}
	//Temp notification
    public  function  tempNotification($view) {
        if($view)
        return '<div class="row-notice">'.$view["content"].'</div>';
    }
    //Render Json update
    public function  renderJsonUpdate(){
        $browser = $this->getBrowser();
        $version = $this->getVersion();
        $browserName = isset($browser)?$browser:"Không xác định";
        $browserVersion = isset($version)?$version:"Không xác định";
        $ip = $_SERVER['REMOTE_ADDR'];
        $os = self::getOS();
        $object = array(
            "browser"=>$browserName,
            "version"=>$browserVersion,
            "ip"=>$ip,
            "os"=>$os
        );
        return json_encode($object);
    }

	//Check valid browser version    
    public function validBrowserVersion(){
    	$browser = $this->getBrowserSupport();
    	if($browser===null) return false;//Not checked with other browser
    	if(isset($browser['version'])){//If browser is Chrome or Firefox
    		if($this->getVersion()>=$browser['version']){
    			return true;
    		}
    	}
    	return false;
    }

    /* valid Browser Support */
    public function validBrowserSupport() {
        $browser = $this->getBrowserSupport();
        if($browser===null)
            return false;
        return true;
    }

    public static function app($class = __CLASS__) {
        return new $class();
    }
}