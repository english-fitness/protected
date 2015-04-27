<?php

/**
 * This is the model class for table "tbl_user_facebook".
 *
 * The followings are the available columns in table 'tbl_user_facebook':
 * @property integer $user_id
 * @property string $facebook_id
 * @property string $facebook_username
 * @property string $facebook_access_token
 * @property integer $facebook_connected
 * @property string $facebook_name
 * @property string $facebook_link_profile
 * @property string $facebook_email
 * @property string $created_date
 * @property string $modified_date
 */
class UserFacebook extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_facebook';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id, facebook_connected', 'numerical', 'integerOnly'=>true),
			array('facebook_id', 'length', 'max'=>20),
			array('facebook_username, facebook_name, facebook_link_profile, facebook_email', 'length', 'max'=>256),
			array('facebook_access_token', 'length', 'max'=>256),
			array('modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, facebook_id, facebook_username, facebook_access_token, facebook_connected, facebook_name, facebook_link_profile, facebook_email, created_date, modified_date', 'safe', 'on'=>'search'),
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
			array('modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Người dùng',
			'facebook_id' => 'Facebook ID',
			'facebook_username' => 'Facebook Username',
			'facebook_access_token' => 'Facebook Access Token',
			'facebook_connected' => '0: not connected; 1: connected',
			'facebook_name' => 'Tên Facebook',
			'facebook_link_profile' => 'Facebook Link Profile',
			'facebook_email' => 'Facebook Email',
			'created_date' => 'Ngày đăng ký',
			'modified_date' => 'Ngày sửa',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('facebook_id',$this->facebook_id,true);
		$criteria->compare('facebook_username',$this->facebook_username,true);
		$criteria->compare('facebook_access_token',$this->facebook_access_token,true);
		$criteria->compare('facebook_connected',$this->facebook_connected);
		$criteria->compare('facebook_name',$this->facebook_name,true);
		$criteria->compare('facebook_link_profile',$this->facebook_link_profile,true);
		$criteria->compare('facebook_email',$this->facebook_email,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserFacebook the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
     * Connect to facebook & save data to db
     */
    public function saveFacebookUser($userId, $fbData)
    {
    	$fbDkUser = UserFacebook::model()->findByPk($userId);
    	if(!isset($fbDkUser->user_id)){
    		$fbDkUser =  new UserFacebook();
    	}
        $fbDkUser->attributes = array(
        	'user_id' => $userId,
        	'facebook_id'=>$fbData['id'],
        	'facebook_access_token'=> $fbData['token'],
        	'facebook_connected'=>1,
        	'facebook_name'=>$fbData['name'],
        	'facebook_link_profile'=>$fbData['link'],
        	'facebook_email'=>$fbData['email'],
        );
        $fbDkUser->save();
    }
    
    /**
     * Check connected to facebook of an User
     */
    public function checkConnectedFacebook($userId)
    {
    	$fbUser = UserFacebook::model()->findByPk($userId);
    	if(isset($fbUser->facebook_id)){
    		return $fbUser;
    	}
    	return NULL;
    }
    
    /**
     * Check facebook connected to an other User
     */
    public function checkConnectedFbByOtherUser($userId, $fbId)
    {
    	$fbUser = UserFacebook::model()->findByAttributes(array('facebook_id'=>$fbId));
    	if(isset($fbUser->user_id) && $fbUser->user_id!=$userId){
    		return true;
    	}
    	return false;
    }
    
	/**
	 * Delete connected Facebook by UserId
	 */
	public function deleteFacebookByUser($userId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "user_id = $userId";
		UserFacebook::model()->deleteAll($criteria);
	}
	
	/**
	 * Get connected user to facebook
	 */
	public function displayConnectedUser()
	{
		$user = User::model()->findByPk($this->user_id);
		ClsAdminHtml::displayConnectedUser($user);
	}
    
}
