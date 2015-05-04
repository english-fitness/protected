<?php

class Board extends CComponent
{
    public $licodeUrl;
    public $licodeCurl;
    public $trialUrl;
    public $trialCurl;

    public function init()
    {
    }

    /**
     * Create a new board on the Collaboration
     * @return false|session object
     * @author Son Nguyen
     */
    public function createBoard($session, $trial = 0, $p2p = 0, $nuve=0, $mode = 1 )
    {
        if(!$session instanceof Session) {
        	$session = Session::model()->findByPk($session);
        }

        if($session == NULL) {
            return false;
        }

        $url = $trial ? $this->trialCurl : $this->licodeCurl;
        $url = $url.'/api/create';
        $params = array('p2p' => $p2p, 'nuve' => $nuve, 'sessionType' => $session->type, 'sessionId' => $session->id,'mode'=>$mode);
        $userIds = $session->getAttendedUserIds();
        $params['userIds'] = json_encode($userIds);
        $params['sessionPlanStart'] = strtotime($session->plan_start);
        $users = array();
        foreach($userIds as $userId) {
            if(!empty($userId)) {
                $user = User::model()->findByPk($userId);
                $name = $user->lastname.' '.$user->firstname;
                $users[$user->id] = array('name' => $name, 'role' => $user->role);
            }
        }
        $params['users'] = json_encode($users);
        try {
        	$response = Yii::app()->curl->get($url, $params);
        } catch(Exception $e) {
        	$response = false;
        }
        if($response) {
            $response = json_decode($response, true);
            if($response['success'] == true) {
                $boardId = $response['boardId'];
                if($trial) {
                    $boardId = '$'.$boardId;
                } else if($p2p) {
                    $boardId = '@'.$boardId;
                } else if($nuve) {
                    $boardId = '&'.$boardId;
                }
                $session->whiteboard = $boardId;
                $session->save();
                return $session;
            } else {
                return false;
            }
        }
        return false;
    }

    /**
     * Update information of a board
     * @param $sessionId sessionId or session object
     * @return false|session object
     * @author Son Nguyen
     */
    public function updateBoard($session)
    {
    	if(!$session instanceof Session) {
        	$session = Session::model()->findByPk($session);
        }

        if($session == NULL) {
            return false;
        }

        $boardId = $session->whiteboard;
        if(empty($boardId)) return;
        $trial = 0; $p2p = 0;
        if(strpos($boardId, '$') !== FALSE) {
            $boardId = substr($boardId, 1);
            $trial = 1;
        } else if(strpos($boardId, '@') !== FALSE) {
            $boardId = substr($boardId, 1);
            $p2p = 1;
        } else if(strpos($boardId, '&') !== FALSE) {
            $boardId = substr($boardId, 1);
        }

        $url = $trial ? $this->trialCurl : $this->licodeCurl;
        $url = $url.'/api/update';
        $params = array('boardId' => $boardId, 'p2p' => $p2p,'sessionType' => $session->type, 'sessionId' => $session->id);
        $userIds = $session->getAttendedUserIds();
        $params['userIds'] = json_encode($userIds);

        $params['sessionPlanStart'] = strtotime($session->plan_start);

        $users = array();
        foreach($userIds as $userId) {
            $user = User::model()->findByPk($userId);
            $name = $user->lastname.' '.$user->firstname;
            $users[$user->id] = array('name' => $name, 'role' => $user->role);
        }
        $params['users'] = json_encode($users);

        try {
        	$response = Yii::app()->curl->get($url, $params);
        } catch(Exception $e) {
        	$response = false;
        }

        if($response) {
            $response = json_decode($response, true);
            return $response['success'] ? true : false;
        }
        return false;
    }

    /**
     * Remove a board on the Collaboration
     * @param string $boardId
     * @return boolean
     * @author Son Nguyen
     */
    public function removeBoard($boardId)
    {
        $trial = false;
        if(strpos($boardId, '$') !== FALSE) {
            $boardId = substr($boardId, 1);
            $trial = true;
        } else if(strpos($boardId, '@') !== FALSE) {
            $boardId = substr($boardId, 1);
        } else if(strpos($boardId, '&') !== FALSE) {
            $boardId = substr($boardId, 1);
        }
        $url = $trial ? $this->trialCurl : $this->licodeCurl;
        $url = $url.'/api/remove';

        $params = array('boardId' => $boardId);
        try {
        	$response = Yii::app()->curl->get($url, $params);
        } catch(Exception $e) {
        	$response = false;
        }

        if($response) {
            $response = json_decode($response, true);
            if($response['success'] == true) {
                return true;
            } else {
                if(isset($response['reason']) && $response['reason'] == 'not found') {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    public function generateUrl($boardId)
    {
        if(strpos($boardId, '$') !== FALSE) {
            $boardId = substr($boardId, 1);
            return $this->trialUrl.'/login/'.$boardId;
        } else {
            if(strpos($boardId, '@') !== FALSE || strpos($boardId, '&') !== FALSE) $boardId = substr($boardId, 1);
            return $this->licodeUrl.'/login/'.$boardId;
        }
    }
}