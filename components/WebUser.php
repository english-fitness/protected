<?php

class WebUser extends CWebUser
{
	// Store model to not repeat query.
	private $_model;

	public $loginDuration;

    function init(){
        parent::init();
        
        $this->_model = User::model()->findByPk($this->id);
    }
    
    function getModel(){
        return $this->_model;
    }
	// Return first name.
	// access it by Yii::app()->user->first_name
	function getFirstName()
	{
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->firstname;
	}
    function getProfilePicture($userId=null)
    {
        if($userId==null){
            $user = $this->loadUser(Yii::app()->user->id);
        }else{
            $user = User::model()->findByPk($userId);
        }
        $dir = "media/uploads/profiles";
        $profilePictureDefault = Yii::app()->baseurl."/media/images/photo.jpg";
        $profilePicture = $dir."/".$user->profile_picture;
        $profilePictureDir =Yii::app()->baseurl."/".$profilePicture;
        if(!(file_exists($profilePicture) && strlen($user->profile_picture)>3))
        {
            $profilePictureDir = $profilePictureDefault;
        }
        return $profilePictureDir;
    }
	function getFullName(){
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->fullName();
	}
    function getFullNameById($id, $userViewLink=''){
        $user = User::model()->findByPk($id);
		$fullName = $user->fullName();
		
		if ($userViewLink !== ''){
			return '<a href="' . $userViewLink . '/' . $id . '">'.$fullName.'</a>';
		} else {
			return $user->fullName();
		}
    }
	function getData(){
		$user = $this->loadUser(Yii::app()->user->id);
		return $user;
	}
	function getRole(){
		if(isset(Yii::app()->user->id)) {
			$user = $this->loadUser(Yii::app()->user->id);
			if(isset($user->role)) return $user->role;	
		}
		return null;
	}
	function getStatus(){
		$user = $this->loadUser(Yii::app()->user->id);
		return $user->status;
	}
	// This is a function that checks the field 'role'
	// in the User model to be equal to constant defined in our User class
	// that means it's admin
	// access it by Yii::app()->user->isAdmin()
	function isAdmin(){
		$user = $this->loadUser(Yii::app()->user->id);
		if ($user!==null)
			return $user->role == User::ROLE_ADMIN;
		else return false;
	}
	
	// Load user model.
	protected function loadUser($id=null) {
		// if($this->_model===null)
		// {
			// if($id!==null)
				// $this->_model=User::model()->findByPk($id);
		// }
		return $this->_model;
	}

	public function login($identity, $duration=0)
	{
		if($duration == 0) $duration = $this->loginDuration;
		parent::login($identity, $duration);
	}
}