<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * Authenticate against our Database
	 * @return boolean whether authentication succeeds.
	 */
	private $_id;
	private $_username;
	public function getName()
	{
		return $this->_username;
	}
	public function getId()
	{
		return $this->_id;
	}
	public function authenticate($cryptPassword=NULL)
	{
		$user= User::model()->find('LOWER(username)=?', array(strtolower($this->username)));
		
		if($user === null)
		{
			$this->errorCode= self::ERROR_UNKNOWN_IDENTITY;
		}
		else{
			$password = ($cryptPassword)? $cryptPassword: crypt($this->password, $user->password);
			if($password !== $user->password)
			{
				$this->errorCode= self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				$this->_id = $user->id;
				$this->_username = $user->username;
				$user->last_login_time=new CDbExpression("NOW()");
                $user->last_login_note = TestConditions::app()->renderJsonUpdate();
				$user->save();
				$this->errorCode= self::ERROR_NONE;
			}
		}	
		return !$this->errorCode;
	}
}