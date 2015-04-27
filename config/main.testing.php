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
        'facebook' => array(
            'appId'  => '258034594348524',
            'secret' => 'eb63f2e4ea6971016374ec40209c3a70',
        ),
        'googleOauth' => array(
            'clientId'  => '581312982977-e8h34gl924n2ve6d3jjsp1m87ikqia5a.apps.googleusercontent.com',
            'secret' => '6y3lSF0NYZ9fF8XyZ5Au9tt2',
            'developerKey' => "AIzaSyCx7KFNIUUrV0a1wdML5n0F1yauYdF3XZI",
        ),
        'nganluong' => array(
			'nganluongUrl' => 'http://beta.nganluong.vn/checkout.php',
			'merchantSiteCode' => '15873',
			'securePass' => '12345678',
        	'receiver' => 'phuonglh@peacesoft.net',
        	'setExpressCheckout' => false,
		),
    ),
);