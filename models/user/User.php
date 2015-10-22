<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $email
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property string $birthday
 * @property integer $gender
 * @property string $address
 * @property string $phone
 * @property string $profile_picture
 * @property string $role
 * @property string $created_date
 * @property string $last_login_time
 * @property integer $status
 * @property string $activation_code
 * @property string $activation_expired
 *
 * The followings are the available model relations:
 * @property Course[] $courses
 * @property Course[] $tblCourses
 * @property CourseComment[] $courseComments
 * @property CoursePreferredTeacher[] $coursePreferredTeachers
 * @property Message[] $messages
 * @property Message[] $messages1
 * @property Notification[] $notifications
 * @property SessionAttendee[] $sessionAttendees
 * @property SessionComment[] $sessionComments
 * @property Subject[] $tblSubjects
 */
class User extends CActiveRecord
{
	public $source;

    /***
     * @var array
     */
    private $_user_meta = array();

	//Const for role of user
	const ROLE_USER = 'role_user';
	const ROLE_ADMIN = 'role_admin';//Admin user
	const ROLE_TEACHER = 'role_teacher';//Teacher user
	const ROLE_STUDENT = 'role_student';//Student user
	const ROLE_MONITOR = 'role_monitor';//Monitor user
    const ROLE_SUPPORT = 'role_support';//Support user
    //Const for status of user
    const STATUS_PENDING = 0;//Pending status
    const STATUS_APPROVED = 1;//Approved status
    const STATUS_ENOUGH_PROFILE = 2;//Enough profile
    const STATUS_ENOUGH_AUDIO = 3;//Enough Audio to study
    const STATUS_REGISTERED_COURSE = 4;//Registered course
    const STATUS_TRAINING_SESSION = 5;//Training status
    const STATUS_ENDED_TRAINING = 6;//Tested session or course
    const STATUS_OFFICIAL_USER = 7;//Official user

    /***
     * @var string $passwordSave
     */
    public $passwordSave;
    /***
     * @var string $repeatPassword
     */
    public $repeatPassword;

    public function getStatusNewOrOld()
    {
        if($this->status < self::STATUS_OFFICIAL_USER)
            return CoursePackageOptions::TYPE_STUDENT_NEW;
        return CoursePackageOptions::TYPE_STUDENT_OLD;
    }

    public function isTraining()
    {
        if($this->status < self::STATUS_TRAINING_SESSION)
            return  true;
        return false;
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user';
	}
	
	public function fullName()
	{
		$fullName = $this->lastname.' '.$this->firstname;
		return $fullName;
	}
	
	/**
	 * User status options
	 */
	public function statusOptions($status=null)
	{
		switch ($this->role){
			case self::ROLE_STUDENT:
				return Student::statusOptions($status);
				break;
			case self::ROLE_TEACHER:
				return Teacher::statusOptions($status);
				break;
			default:
				$statusOptions = array(
					self::STATUS_PENDING => 'Đang chờ',
					self::STATUS_APPROVED => 'Đã xác nhận',
					self::STATUS_ENOUGH_PROFILE => 'Đã đủ thông tin',
					self::STATUS_ENOUGH_AUDIO => 'Đã test loa mic',
					self::STATUS_REGISTERED_COURSE => 'Đã đăng ký K/H',
					self::STATUS_TRAINING_SESSION => 'Đang dạy/học thử',
					self::STATUS_ENDED_TRAINING => 'Đã dạy/học thử',
					self::STATUS_OFFICIAL_USER => 'GV/HS chính thức',
				);
				if($status==null){
					return $statusOptions;
				}elseif(isset($statusOptions[$status])){
					return $statusOptions[$status];
				}
				break;
		}
		
		return null;
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('username, email, password, firstname, lastname', 'required'),
			array('phone', 'checkPhone'),
			array('username', 'unique'),
			array('username', 'match' ,'pattern'=>'/^[A-Za-z0-9-@.-_]+$/u',
                            'message'=> 'Tên người dùng chỉ bao gồm các ký tự và số, không được chứa các ký tự đặc biệt nào khác.'),			
			array('email', 'email'),
			array('phone', 'match', 'pattern'=>'/^\+{0,1}[0-9\-\s]{8,16}$/'),
			//array('email', 'unique'),	
			array('gender, status', 'numerical', 'integerOnly'=>true),
			array('username', 'length', 'max'=>128),
			array('email, password, firstname, lastname, profile_picture, role, activation_code', 'length', 'max'=>128),
			array('password', 'length', 'min'=>6),
			array('address', 'length', 'max'=>256),
			array('phone', 'length', 'max'=>20),
			array('birthday, created_date, last_login_time, activation_expired, status_history, deleted_flag,created_user_id,modified_user_id', 'safe'),
			array('birthday', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('	id, username, email, password, firstname, lastname, birthday, gender, address, phone, profile_picture,
					role, created_date, last_login_time, status, activation_code, activation_expired, status_history, deleted_flag,
					created_user_id, modified_user_id, source', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		//Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
			$modelRules[] = array('created_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert');
			$modelRules[] = array('modified_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'update');
		}
		return $modelRules;//Return model rules
	}

	public function checkPhone(){
		if ($this->role == self::ROLE_STUDENT && empty($this->phone)){
			$this->addError("phone", "Số điện thoại không được phép trống");
		}
	}

	public function beforeSave()
	{
		parent::beforeSave();
		//Remove html tags of some fields before save User
		$stripTagFields = array('firstname', 'lastname', 'address');
		foreach($stripTagFields as $textField){
			$this->$textField = strip_tags($this->$textField);
            $this->$textField = trim($this->$textField);
		}

		//conditions are on the function itself
		$this->savePassword();
		
		//Update status change
		$historyStatuses = array();//Init history statuses
		if(trim($this->status_history)!=""){
			$historyStatuses = json_decode($this->status_history, true);
			if(is_array($historyStatuses) || is_object($historyStatuses)){
				$historyStatuses = (array)$historyStatuses;
				if(!isset($historyStatuses[$this->status])){
					$historyStatuses[$this->status] = date('Y-m-d H:i:s');
				}
			}
		}else{
			$historyStatuses[$this->status] = date('Y-m-d H:i:s');
		}
		$historyStatuses = array_unique($historyStatuses);
		$this->status_history = json_encode($historyStatuses);
		return true;
	}
	
	//After save User
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete User
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
	public function checkPasswordOlder($password)
    {
        if($this->password === crypt($password))
            return true;
        return false;
    }
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'courses' => array(self::HAS_MANY, 'Course', 'created_user_id'),
			'tblCourses' => array(self::MANY_MANY, 'Course', 'tbl_course_attendee(user_id, course_id)'),
			'courseComments' => array(self::HAS_MANY, 'CourseComment', 'user_id'),
			'coursePreferredTeachers' => array(self::HAS_MANY, 'CoursePreferredTeacher', 'teacher_id'),
			'messages' => array(self::HAS_MANY, 'Message', 'receiver_id'),
			'messages1' => array(self::HAS_MANY, 'Message', 'sender_id'),
			'notifications' => array(self::HAS_MANY, 'Notification', 'receiver_id'),
			'sessionAttendees' => array(self::HAS_MANY, 'SessionAttendee', 'user_id'),
			'sessionComments' => array(self::HAS_MANY, 'SessionComment', 'user_id'),
			'tblSubjects' => array(self::MANY_MANY, 'Subject', 'tbl_teacher_ability(user_id, subject_id)'),
            'student'=>array(self::HAS_ONE, 'Student', 'user_id', 'condition'=>'role="'.self::ROLE_STUDENT.'"'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'username' => 'Tài Khoản',
			'email' => 'Email',
			'password' => 'Mật khẩu',
			'firstname' => 'Tên',
			'lastname' => 'Họ đệm',
			'birthday' => 'Ngày sinh',
			'gender' => 'Giới tính',
			'address' => 'Địa chỉ',
			'phone' => 'Điện thoại',
			'profile_picture' => 'Ảnh đại diện',
			'role' => 'Vai trò',
			'created_date' => 'Ngày đăng ký',
			'modified_date' => 'Ngày sửa',
			'last_login_time' => 'Lần đăng nhập cuối',
			'status' => 'Trạng thái',
			'status_history' => 'Lịch sử trạng thái',
			'activation_code' => 'Mã kích hoạt',
			'activation_expired' => 'Kích hoạt hết hạn',
			'created_user_id' => 'Người tạo',
			'modified_user_id' => 'Người sửa',
			'deleted_flag' => 'Trạng thái xóa',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		//$criteria->compare('firstname',$this->firstname,true);
		//$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('profile_picture',$this->profile_picture,true);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('last_login_time',$this->last_login_time,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('activation_code',$this->activation_code,true);
		$criteria->compare('activation_expired',$this->activation_expired,true);
		//Column source is from tbl_preregister_user.
		//There is no other ambiguous columns so let's do this for simplicity
		$criteria->compare('source',$this->source, false);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page', 'pageSize'=>20),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}

	public function savePassword($repeatPassword=true){
		//add the password hash if it's a new record
		//or change the password if new password is set and repeat password match
		if (!$this->isNewRecord){
			$validPasswordSave = false;
			if (!empty($this->passwordSave)){
				if (($repeatPassword && !empty($this->repeatPassword)&&($this->passwordSave===$this->repeatPassword))
					|| !$repeatPassword){
					$validPasswordSave = true;
				}
			}

			if(!$validPasswordSave){
				return;
			}
		}
        $salt = "";

		for ($i = 0; $i < 16; $i++) {
			$salt .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
		}
		// sha256
		$salt = '$5$'.$salt.'$';
		$this->password = crypt($this->passwordSave, $salt);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Get date of current status of user
	 */
	public function getStatusDate()
	{
		$historyStatuses = json_decode($this->status_history, true);
		if(is_array($historyStatuses) || is_object($historyStatuses)){
			$historyStatuses = (array) $historyStatuses;
			if(isset($historyStatuses[$this->status])){
				return $historyStatuses[$this->status];
			}
		}
		return date('Y-m-d H:i:s');//Current date
	}
	
	/**
	 * Display history of status
	 */
	public function displayHistoryStatus()
	{
		$historyStatuses = json_decode($this->status_history, true);
		$displayHistoryStatus = "";//Str status history
		if(is_array($historyStatuses) || is_object($historyStatuses)){
			$historyStatuses = (array) $historyStatuses;
			$statusOptions = $this->statusOptions();
			foreach($statusOptions as $key=>$label){
				if(isset($historyStatuses[$key])){
					$displayHistoryStatus .= date("d/m/Y, H:i", strtotime($historyStatuses[$key]))." => ".$label."<br/>";
				}
			}
		}
		return $displayHistoryStatus;
	}
	
	//Authenticate by facebook or google
	public function UserApiIdentity($identityUserId=NULL)
	{
		if($identityUserId){
        	$user = User::model()->findByPk($identityUserId);
        	//Not allow login by FB, Google, Hocmai with Admin user
        	if(in_array($user->role, array(User::ROLE_ADMIN, User::ROLE_MONITOR, User::ROLE_USER))){
        		return false;
        	}
        	if(isset($user->id)){
        		//Auto login & redirect
        		$identity = new UserIdentity($user->username, $user->password);
				$identity->authenticate($user->password);
				if($identity->errorCode===UserIdentity::ERROR_NONE){
					Yii::app()->user->login($identity);
					return true;
				}
        	}
        }
        return false;
	}
	
	/**
	 * Login by facebook api
	 */
	public function loginByFacebook($fbUser)
	{
		$condition = array('facebook_id'=>$fbUser['id']);
    	$fbDkUser = UserFacebook::model()->findByAttributes($condition);
    	if(isset($fbDkUser->user_id)){
    		return $this->UserApiIdentity($fbDkUser->user_id);
    	}
    	return false;
	}
	
	/**
	 * Login by Google api
	 */
	public function loginByGoogle($googleUser)
	{
		$condition = array('google_id'=>$googleUser['id']);
    	$gDkUser = UserGoogle::model()->findByAttributes($condition);
    	if(isset($gDkUser->user_id)){
    		return $this->UserApiIdentity($gDkUser->user_id);
    	}
    	return false;
	}
	
	/**
	 * Login by hocmai
	 */
	public function loginByHocmai($hmUser)
	{
		$condition = array('hocmai_username'=>$hmUser['username']);
    	$hmDkUser = UserHocmai::model()->findByAttributes($condition);
    	if(isset($hmDkUser->user_id)){
    		return $this->UserApiIdentity($hmDkUser->user_id);
    	}
    	return false;
	}
	
	//Generate random password
	public function generatePassword($length = 8) {
	    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	    $count = strlen($chars); $result = "";
	    for ($i = 0; $i < $length; $i++) {
	        $index = rand(0, $count - 1);
	        $result .= substr($chars, $index, 1);
	    }
	    return $result;
	}
	
	/**
	 * Auto creating DK user when the first time User login by FB, Google
	 */
	public function createUserByGoogleFB($userData, $connectedType='facebook')
	{
		$genderOptions = array('male'=>1, 'female'=>0, 0=>0, 1=>1);
		$gender = isset($userData['gender'])? $genderOptions[$userData['gender']]: 1;
		$existedDkUser = User::model()->findByAttributes(array('email'=>$userData['email']));
		if(isset($existedDkUser->id)){
			$user = $existedDkUser;//Update & connnect to existed user
			$user->attributes = array();//Not update main user account
		}else{
			$user = new User();//New user
			$firstName = isset($userData['firstname'])? $userData['firstname']: NULL;//Set firstname
			$lastName = isset($userData['lastname'])? $userData['lastname']: NULL;//Set lastname
			if(isset($userData['fullname'])){
				if($connectedType=='facebook') $name = Common::parseName($userData['fullname'], 'last/first');
				if($connectedType=='hocmai') $name = Common::parseName($userData['fullname'], 'first/last');
				$firstName = $name['firstname'];//Firstname
				$lastName = $name['lastname'];//Firstname
			}
			$userRole = User::ROLE_STUDENT;//Default role Student
			if(isset($userData['user_type']) && $userData['user_type']==3){//Teacher from Hocmai
				$userRole = User::ROLE_TEACHER;//Role Teacher
			}
			$randomPass = $this->generatePassword();
			$user->attributes = array(
				'firstname' => $firstName,
				'lastname' => $lastName,
				'email' => $userData['email'],
				'gender' => $gender,
				'role' => $userRole,
				'password'=>$randomPass,
				'passwordSave'=>$randomPass,
			);
			//Get some data from Hocmai if user connect from hocmai
			if(isset($userData['phone'])) $user->phone = $userData['phone'];//User telephone number
			if(isset($userData['mobile']) && $userData['mobile']) $user->phone = $userData['mobile'];//User Mobile number
			if(isset($userData['address'])) $user->address = $userData['address'];//User address
			if(isset($userData['birthday'])) $user->birthday = $userData['birthday'];//User birthday
		}		
		if($user->save()){
			$existedStudent = Student::model()->findByPk($user->id);
			if(isset($existedStudent->user_id)){
				$student = $existedStudent;//Existed profile
				$student->attributes = array();//Not change any existed info
			}else{
				$student = new Student();//Create & save student
				$student->attributes = array('user_id'=>$user->id);//Set student profile
			}
			if($student->save()) {
				if($connectedType=='facebook'){//Save facebook user
					$facebook = new UserFacebook();
					$facebook->saveFacebookUser($user->id, $userData);
				}elseif($connectedType=='google'){//Save google user
					$google = new UserGoogle();
					$google->saveGoogleUser($user->id, $userData);
				}elseif($connectedType=='hocmai'){
					$hocmai = new UserHocmai();
					$hocmai->saveHocmaiUser($user->id, $userData);
				}
			}
			//Send activation email when user registered success
			$mailer = new ClsMailer();
			$emailQueue = $mailer->saveWelcomeEmailToQueue($user->email, $user->fullName());
		}
		return $user;		
	}
	
	/**
	 * Display social contact of User(Facebook, Google)
	 */
	public function displayContactIcons()
	{
		$fbUser = UserFacebook::model()->checkConnectedFacebook($this->id);
		$fbId = ($fbUser!=NULL)?$fbUser->facebook_id: NULL;
		$googleUser = UserGoogle::model()->checkConnectedGoogle($this->id);
		$hmUser = UserHocmai::model()->checkConnectedHocmai($this->id);
		$gmail = ($googleUser!=NULL)? $googleUser->google_email: NULL;
		$hmUsername = ($hmUser!=NULL)? $hmUser->hocmai_username: NULL;
		ClsAdminHtml::displayContactIcons($this->phone, $fbId, $gmail, $hmUsername);
	}
	
	/**
	 * Get students & filter by email or fullname
	 */
	public function searchUsersToAssign($keyword, $userRole=NULL)
	{
		$criteria = new CDbCriteria();
		$condition = "((username LIKE '%".$keyword."%') OR (CONCAT(`lastname`,' ',`firstname`) LIKE '%".$keyword."%'))";
		if($userRole!=NULL){
			$condition .= " AND (role='".$userRole."')";
		}
		$criteria->condition = $condition;
		$criteria->limit = 30;
		$users = User::model()->findAll($criteria);
        $UsersToAssign = array();
        if(count($users)>0){
        	foreach($users as $user){
        		$className = "";//Default class name
	            $UsersToAssign[] = array(
                    'role' => $user->role,
	           		'id' => $user->id,
	           		//'email' => $user->email,
					'username'=> $user->username,
	           		"fullName" => $user->fullName(),
	           		//"emailAndFullName" => $user->fullName()." (".$user->email.")".$className,
					"usernameAndFullName" => $user->fullName() . "(" . $user->username . ")",
	            );
        	}
        }
        return $UsersToAssign;
	}

    /***
     * @param $key
     * @return array|mixed|null
     */
    public function getUserMeta($key)
    {
        if(isset($this->_user_meta[$key]))
            return $this->_user_meta[$key];
        else{
            $userMeta = Usermeta::model()->findByAttributes(array('meta_key'=>$key,'user_id'=>$this->id));
            if($userMeta)
                return $this->_user_meta[$key] = $userMeta->meta_value;
        }
        return null;
    }


    /***
     * @param array $data
     * @param string $formName
     */
    public function updateFormUserMeta(array $data,$formName = 'Meta')
    {
        $data = isset($data[$formName])?$data[$formName]:array();
        foreach($data as $k=>$v) {
            $this->updateUserMeta($k,$v);
        }
    }

    /***
     * @param $key
     * @param $value
     */
    public function updateUserMeta($key,$value)
    {
        $userMeta = Usermeta::model()->findByAttributes(array('meta_key'=>$key,'user_id'=>$this->id));
        if(!$userMeta) { $userMeta = new Usermeta(); }
        $userMeta->meta_key = $key;
        $userMeta->meta_value = $value;
        $userMeta->user_id = $this->id;
        $userMeta->save();
    }

	/***
     * @param null $userId
     * @return null
     */
    public function displayUserById($userId=null)
	{
		if($userId==null) return null;
		$user = $this->findByPk($userId);
		if(isset($user->id)){
			return $user->fullName();
		}
		return $userId;
	}
	
	public static function findByFullname($fullname, $role=null, $returnAttributes=array()){
		if (empty($returnAttributes)){
			$returnModels = true;
		} else {
			$returnModels = false;
		}
        
        if (!is_string($role)){
            throw new Exception("User::findByFullname - role must be a string");
        }
        
        if ($role != null){
            $roleCondition = " AND role = '" . $role . "'";
        } else {
            $roleCondition = "";
        }
		
		if ($returnModels){
			$query = "SELECT * FROM tbl_user u " .
					 "WHERE CONCAT(u.`lastname`, ' ', u.`firstname`) LIKE '%".$fullname."%'" .
                     $roleCondition;
		} else {
			$query = "SELECT " . implode(',', $returnAttributes) . " FROM tbl_user u " .
					 "WHERE CONCAT(u.`lastname`, ' ', u.`firstname`) LIKE '%".$fullname."%'" .
                     $roleCondition;
		}
		
		if ($returnModels){
			return self::model()->findAllBySql($query);
		} else {
			return Yii::app()->db->createCommand($query)->queryAll();
		}
	}
	
	public function getProfilePictureHtml($htmlOptions = null, $externalLink = null, $caption = null){
		$dir = "media/uploads/profiles";
		$profilePictureDefault = Yii::app()->baseurl."/media/images/photo.jpg";
		$profilePicture = $dir."/".$this->profile_picture;
		if(!(file_exists($profilePicture) && strlen($this->profile_picture)>3)){
            $profilePictureDir = $profilePictureDefault;
        } else {
			$profilePictureDir =Yii::app()->baseurl."/".$profilePicture;
		}
		
		$attributes = '';
		if ($htmlOptions != null){
			foreach($htmlOptions as $key=>$value){
				$attributes .= ' ' . $key . '="' . $value . '"';
			}
		}
		
		$html = '<img src="' . $profilePictureDir . '"' . $attributes . '></img>';
		if ($caption != null){
			$html .= '<br>' . $caption;
		}
		if ($externalLink != null){
			$html = '<a href="' . $externalLink . '">' .
						$html .
					'</a>';
		}
		
		return $html;
	}
	
	public function getViewLink(){
		switch ($this->role){
			case self::ROLE_STUDENT:
				return '<a href="' . Yii::app()->baseUrl . '/admin/student/view/id/' . $this->id . '">' . $this->fullname() . '</a>';
				break;
			case self::ROLE_TEACHER:
				return '<a href="' . Yii::app()->baseUrl . '/admin/teacher/view/id/' . $this->id . '">' . $this->fullname() . '</a>';
				break;
			default:
				break;
		}
	}
}
