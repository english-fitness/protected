<?php

/**
 * This is the model class for table "tbl_user_hocmai".
 *
 * The followings are the available columns in table 'tbl_user_hocmai':
 * @property integer $user_id
 * @property string $hocmai_id
 * @property string $hocmai_email
 * @property string $hocmai_username
 * @property string $hocmai_password
 * @property integer $hocmai_user_type
 * @property string $hocmai_fullname
 * @property string $hocmai_access_token
 * @property integer $hocmai_connected
 * @property integer $hocmai_gender
 * @property string $hocmai_province
 * @property string $hocmai_phone
 * @property string $hocmai_mobile
 * @property string $hocmai_address
 * @property string $created_date
 * @property string $modified_date
 */
class UserHocmai extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_hocmai';
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
			array('user_id, hocmai_user_type, hocmai_connected, hocmai_gender', 'numerical', 'integerOnly'=>true),
			array('hocmai_id', 'length', 'max'=>25),
			array('hocmai_email, hocmai_username, hocmai_password, hocmai_fullname, hocmai_province, hocmai_address', 'length', 'max'=>256),
			array('hocmai_access_token', 'length', 'max'=>128),
			array('hocmai_phone, hocmai_mobile', 'length', 'max'=>20),
			array('modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, hocmai_id, hocmai_email, hocmai_username, hocmai_password, hocmai_user_type, hocmai_fullname, hocmai_access_token, hocmai_connected, hocmai_gender, hocmai_province, hocmai_phone, hocmai_mobile, hocmai_address, created_date, modified_date', 'safe', 'on'=>'search'),
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
			'user_id' => 'User',
			'hocmai_id' => 'Hocmai ID',
			'hocmai_email' => 'Email',
			'hocmai_username' => 'Username',
			'hocmai_password' => 'Password',
			'hocmai_user_type' => '1: phu huynh; 2: hoc sinh; 3: giao vien',
			'hocmai_fullname' => 'Tên đầy đủ',
			'hocmai_access_token' => 'Access Token',
			'hocmai_connected' => '0: not connected; 1: connected',
			'hocmai_gender' => '1: male; 0: female',
			'hocmai_province' => 'Mã tỉnh',
			'hocmai_phone' => 'Điện thoại',
			'hocmai_mobile' => 'Mobile',
			'hocmai_address' => 'Địa chỉ',
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
		$criteria->compare('hocmai_id',$this->hocmai_id,true);
		$criteria->compare('hocmai_email',$this->hocmai_email,true);
		$criteria->compare('hocmai_username',$this->hocmai_username,true);
		$criteria->compare('hocmai_password',$this->hocmai_password,true);
		$criteria->compare('hocmai_user_type',$this->hocmai_user_type);
		$criteria->compare('hocmai_fullname',$this->hocmai_fullname,true);
		$criteria->compare('hocmai_access_token',$this->hocmai_access_token,true);
		$criteria->compare('hocmai_connected',$this->hocmai_connected);
		$criteria->compare('hocmai_gender',$this->hocmai_gender);
		$criteria->compare('hocmai_province',$this->hocmai_province,true);
		$criteria->compare('hocmai_phone',$this->hocmai_phone,true);
		$criteria->compare('hocmai_mobile',$this->hocmai_mobile,true);
		$criteria->compare('hocmai_address',$this->hocmai_address,true);
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
	 * @return UserHocmai the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
     * Connect to hocmai & save data to db
     */
    public function saveHocmaiUser($userId, $hmData)
    {
    	$hmUser = UserHocmai::model()->findByPk($userId);
    	if(!isset($hmUser->user_id)){
    		$hmUser =  new UserHocmai();
    	}
    	$hmValues = array();//Init hocmai values
    	foreach($hmData as $key=>$value){
    		$hmValues['hocmai_'.$key] = $value;
    	}
        $hmUser->attributes = $hmValues;
        $hmUser->user_id = $userId;//Daykem UserId
        $hmUser->hocmai_connected = 1;
        $hmUser->save();
    }
    
    /**
     * Check connected to facebook of an User
     */
    public function checkConnectedHocmai($userId)
    {
    	$hmUser = UserHocmai::model()->findByPk($userId);
    	if(isset($hmUser->user_id)){
    		return $hmUser;
    	}
    	return NULL;
    }
    
    /**
     * Check hocmai connected to an other User
     */
    public function checkConnectedHocmaiByOtherUser($userId, $hmUsername)
    {
    	$hmUser = UserHocmai::model()->findByAttributes(array('hocmai_username'=>$hmUsername));
    	if(isset($hmUser->user_id) && $hmUser->user_id!=$userId){
    		return true;
    	}
    	return false;
    }
    
	/**
	 * Delete connected hocmai by UserId
	 */
	public function deleteHocmaiByUser($userId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "user_id = $userId";
		UserHocmai::model()->deleteAll($criteria);
	}
	
	/**
	 * Get connected user to Hocmai
	 */
	public function displayConnectedUser()
	{
		$user = User::model()->findByPk($this->user_id);
		ClsAdminHtml::displayConnectedUser($user);
	}
}
