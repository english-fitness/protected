<?php

// change the following paths if necessary
$yiic=dirname(__FILE__).'/../framework/yiic.php';
$environment = getenv('ENV') ? getenv('ENV') : 'development';
// $config=dirname(__FILE__)."/config/console.$environment.php";

$default = require(dirname(__FILE__).'/config/console.default.php');
$config=require(dirname(__FILE__).'/config/console.'.$environment.'.php');
$config = array_replace_recursive($default, $config);

require_once($yiic);
