<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
    'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
    'name'=>'Dạykèm123',

    'import'=>array(
        'application.models.*',
		'application.models.user.*',
		'application.models.course.*',
		'application.models.session.*',
		'application.models.quiz.*',
        'application.components.*',
        'application.vendor.*',
        'application.classes.*',
        'ext.YiiMailer.YiiMailer',
    ),

    // preloading 'log' component
    'preload'=>array('log'),

    // application components
    'components'=>array(
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=daykem11',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '123456',
            'charset' => 'utf8',
        ),
        'curl'=>array(
            'class' => 'Curl',
        ),
        'board'=>array(
            'class' => 'Board',
            'licodeUrl'=>'https://onschool.vn/vsr',
            'trialUrl'=>'https://onschool.vn/trial',
            'licodeCurl'=>'http://127.0.0.1:8000',
            'trialCurl'=>'http://127.0.0.1:3000'
        ),
        'redis'=>array(
            'class' => 'Redis',
            'redisConnectionString'=>'tcp://127.0.0.1:6379',
        ),
        'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'levels'=>'error, warning',
                ),
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params'=>array(
        // this is used in contact page
        'adminEmail'=>'admin@daykem11.com',
        'YiiMailer'=>array(
            'SMTPAuth'=>true,
            'SMTPSecure'=>'tls',
            'Host'=>'smtp.gmail.com',
            'Mailer'=>'smtp',
            'Port'=>587,
            'Username'=>'contact@daykem11.com',
            'Password'=>'12345678',
            'Timeout'=>30000,
        ),
         'mediaBasePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR,
    ),
);