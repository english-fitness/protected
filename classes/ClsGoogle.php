<?php
/**
 * GoogleComponent class
 */
class ClsGoogle extends CApplicationComponent
{
    public $apiLocation = 'application.vendor.google-oauth2.src.Google_Client';
    public $serviceLocation = 'application.vendor.google-oauth2.src.contrib.Google_Oauth2Service';
    public $gClient;
    
    public function __construct()
    {
        // Google API client
        Yii::import($this->apiLocation, true);
        Yii::import($this->serviceLocation, true);  
        $gConfig = Yii::app()->params['googleOauth'];
        if(is_null($this->gClient))
        {
	        $this->gClient = new Google_Client();
			$this->gClient->setApplicationName('Login Daykem');
			$this->gClient->setClientId($gConfig['clientId']);
			$this->gClient->setClientSecret($gConfig['secret']);
			$this->gClient->setDeveloperKey($gConfig['developerKey']);
			$this->gClient->setApprovalPrompt('auto');
			$this->gClient->setScopes(array(
	        	'https://www.googleapis.com/auth/userinfo.profile',
	        	'https://www.googleapis.com/auth/userinfo.email',
	        ));
	        $googleRedirectUri = Yii::app()->getRequest()->getBaseUrl(true).'/login/google';
			$this->gClient->setRedirectUri($googleRedirectUri);
        }
    }
    
	/**
     * Check & connect to Google
     */
    public function connectToGoogle()
    {
    	if (isset(Yii::app()->session['token'])){ 
			$this->gClient->setAccessToken(Yii::app()->session['token']);
		}
		$params = array();//User & Google data params
		//Check & login by google
        if ($this->gClient->getAccessToken())
		{
        	try {
        		$googleOauthV2 = new Google_Oauth2Service($this->gClient);
        		$params['userData'] = $googleOauthV2->userinfo->get();
        		$params['userData']['firstname'] = $params['userData']['given_name'];
        		$params['userData']['lastname'] = $params['userData']['family_name'];
        		$objToken = json_decode($this->gClient->getAccessToken());
        		$params['userData']['token'] = $objToken->access_token;
        		$params['googleConnected'] = true;//Google connected
	        	return $params;
        	}catch(Exception $e){
				//Display error message here
			}
        }
        $params['googleConnected'] = false;//Google not connected
        return $params;
    }

    
}
