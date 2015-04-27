<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    // application components
    'components'=>array(
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=daykem11',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ),
    ),

    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
    	'googleTagCode'=>'GTM-KN8TPZ',
        'facebook' => array(
			'appId'  => '238143936353775',
		    'secret' => '4697667b3359fb1e349e802b22575cfd',
		),
		'googleOauth' => array(
			'clientId'  => '581312982977-2sabm7q3l229u3830jpes3pqgf016s68.apps.googleusercontent.com',
			'secret' => '1r9ac_5B6-C1FR5bZ0rkCOU0',
			'developerKey' => "AIzaSyC87cxGBE4YIXD3bwXY_0ehTLFGSWiQ6BM",
		),
		'nganluong' => array(
			'nganluongUrl' => 'https://www.nganluong.vn/checkout.api.nganluong.post.php',
			'merchantSiteCode' => '33074',
			'securePass' => 'Daykem!@#',
			'receiver' => 'accounting@hocmai.com.vn',
			'setExpressCheckout' => true,
		),
    ),
);