<?php

// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
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
        'board'=>array(
            'class' => 'Board',
            'licodeUrl'=>'http://dev.tutor.vn:8000',
            'licodeCurl'=>'http://127.0.0.1:8000',
        ),
    ),
);