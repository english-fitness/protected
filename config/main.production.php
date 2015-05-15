<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(

    // application components
    'components'=>array(
        'board'=>array(
            'licodeUrl'=>'http://'.$_SERVER['SERVER_NAME'].'/vsr',
            'trialUrl'=>'http://'.$_SERVER['SERVER_NAME'].'/trial',
            'licodeCurl'=>'http://127.0.0.1:8000',
            'trialCurl'=>'http://127.0.0.1:3000',
        ),
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=daykem11',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => 'xh163157',
            'charset' => 'utf8',
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
    	'googleTagCode'=>'GTM-KFNXRL',
    	'allowFacebookTracking'=>true,
        'facebook' => array(
			'appId'  => '',
		    'secret' => '',
		),
		'googleOauth' => array(
			'clientId'  => '602696541864-bodhm85vubhmvujn1rii0qls5dhjn7ng.apps.googleusercontent.com',
			'secret' => '',
			'developerKey' => "",
		),
		'nganluong' => array(
			'nganluongUrl' => 'https://www.nganluong.vn/checkout.api.nganluong.post.php',
			'merchantSiteCode' => '',
			'securePass' => '',
			'receiver' => 'paypal@tutor.vn',
			'setExpressCheckout' => true,
		),
		'recordDir'=>"/home/administrator/records/",
    ),
);
