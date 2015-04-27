<?php
require_once('predis-0.8.4/autoload.php');

class Redis extends CComponent
{
    public $redisConnectionString;
    public $sharedDomain;

    public function init()
    {
    }

    public function writeUserData($data)
    {
        $redis = new Predis\Client($this->redisConnectionString);
        $phpSessionId = session_id();
        $redis->set("webapp:".$phpSessionId, json_encode($data));
        // valid only one day
        $redis->expire("webapp:".$phpSessionId, 86400);
    }

    public function clearUserData()
    {
        $redis = new Predis\Client($this->redisConnectionString);
        $phpSessionId = session_id();
        $redis->del("webapp:".$phpSessionId);
    }
}