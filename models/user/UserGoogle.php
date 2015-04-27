<?php

/**
 * This is the model class for table "tbl_user_google".
 *
 * The followings are the available columns in table 'tbl_user_google':
 * @property integer $user_id
 * @property string $google_id
 * @property string $google_name
 * @property string $google_email
 * @property integer $google_connected
 * @property string $google_link
 * @property string $google_picture_link
 * @property string $created_date
 * @property string $modified_date
 */
class UserGoogle extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_google';
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
			array('user_id, google_connected', 'numerical', 'integerOnly'=>true),
			array('google_id', 'length', 'max'=>25),
			array('google_name, google_email, google_link, google_picture_link', 'length', 'max'=>256),
			array('google_access_token', 'length', 'max'=>128),
			array('modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, google_id, google_name, google_email, google_access_token, google_connected, google_link, google_picture_link, created_date, modified_date', 'safe', 'on'=>'search'),
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
			'google_id' => 'Gmail ID',
			'google_name' => 'Tên Gmail',
			'google_email' => 'Gmail',
			'google_connected' => '0: not connected; 1: connected',
			'google_access_token' => 'Google Access Token',
			'google_link' => 'Google Link',
			'google_picture_link' => 'Google Picture Link',
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
		$criteria->compare('google_id',$this->google_id,true);
		$criteria->compare('google_name',$this->google_name,true);
		$criteria->compare('google_email',$this->google_email,true);
		$criteria->compare('google_connected',$this->google_connected);
		$criteria->compare('google_link',$this->google_link,true);
		$criteria->compare('google_picture_link',$this->google_picture_link,true);
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
	 * @return UserGoogle the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
     * Connect to google & save data to db
     */
    public function saveGoogleUser($userId, $gData)
    {
    	$googleUser = UserGoogle::model()->findByPk($userId);
    	if(!isset($googleUser->user_id)){
    		$googleUser =  new UserGoogle();
    	}
        $googleUser->attributes = array(
        	'user_id' => $userId,
        	'google_id'=>$gData['id'],
        	'google_connected'=>1,
        	'google_name'=>$gData['name'],
        	'google_email'=>$gData['email'],
        	'google_access_token'=>$gData['token'],
        );
        $googleUser->save();
    }
    
	/**
     * Check connected to google of an User
     */
    public function checkConnectedGoogle($userId)
    {
    	$googleUser = UserGoogle::model()->findByPk($userId);
    	if(isset($googleUser->google_email)){
    		return $googleUser;
    	}
    	return NULL;
    }
    
	/**
     * Check Google connected to an other User
     */
    public function checkConnectedGoogleByOtherUser($userId, $googleId)
    {
    	$googleUser = UserGoogle::model()->findByAttributes(array('google_id'=>$googleId));
    	if(isset($googleUser->user_id) && $googleUser->user_id!=$userId){
    		return true;
    	}
    	return false;
    }
    
	/**
	 * Delete connected Google by UserId
	 */
	public function deleteGoogleByUser($userId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "user_id = $userId";
		UserGoogle::model()->deleteAll($criteria);
	}
	
	/**
	 * Get connected user to Gmail
	 */
	public function displayConnectedUser()
	{
		$user = User::model()->findByPk($this->user_id);
		ClsAdminHtml::displayConnectedUser($user);
	}
	
}
