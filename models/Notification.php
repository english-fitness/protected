<?php

/**
 * This is the model class for table "tbl_notification".
 *
 * The followings are the available columns in table 'tbl_notification':
 * @property integer $id
 * @property integer $receiver_id
 * @property string $content
 * @property string $link
 * @property integer $notification_type
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property User $receiver
 */
class Notification extends CActiveRecord
{
	const ALL_STUDENT_EMAIL = 'all_students@daykem123.vn';
	const ALL_TEACHER_EMAIL = 'all_teachers@daykem123.vn';
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_notification';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('receiver_id, content', 'required'),
			array('receiver_id, notification_type, deleted_flag', 'numerical', 'integerOnly'=>true),
			array('link', 'length', 'max'=>256),
			array('deleted_flag, notification_type,created_user_id,modified_date,modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, receiver_id, content, link, confirmed_ids, notification_type, created_date,created_user_id,modified_date,modified_user_id', 'safe', 'on'=>'search'),
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

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'receiver' => array(self::BELONGS_TO, 'User', 'receiver_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'receiver_id' => 'Người nhận',
			'content' => 'Nội dung',
			'link' => 'Link',
			'notification_type' => 'Kiểu thông báo',
			'created_date' => 'Ngày tạo',
			'deleted_flag' => 'Trạng thái xóa',
			'created_user_id' => 'Người tạo',
			'modified_date' => 'Ngày sửa',
			'modified_user_id' => 'Người sửa',
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
		$criteria->compare('receiver_id',$this->receiver_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('notification_type',$this->notification_type);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		$criteria->order = 'created_date DESC';
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}
	
	//Strip tag before save Notification
	public function beforeSave()
	{
		//Remove html tags of some fields before save Message
		$this->content = strip_tags($this->content, Common::allowHtmlTags());
		return true;
	}
	
	//After save Notification
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete Notification
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
    public function  getContent($column)
    {
        $data = json_decode($this->content);
        if(isset($data->$column)){
            return $data->$column;
        }else{
            return null;
        }
    }
    public function getUser(){
        $user = User::model()->findByPk($this->getContent("user_id_post"));
        if($user){
            return $user;
        }else{
            return null;
        }
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Notification the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	/**
	 * Delete all notifications by UserId
	 */
	public function deleteNotificationsByUser($userId) {
		$criteria = new CDbCriteria();
		$criteria->condition = "receiver_id = $userId";
		Notification::model()->deleteAll($criteria);
	}
	
	/**
	 * Get received user
	 */
	public function getReceivedUser(){
        $user = User::model()->findByPk($this->receiver_id);
        return $user;
    }
    
    /**
     * Get notification by user
     */
    public function getNotifications($user, $limit=30, $confirmed=null, $count=false)
    {
    	$allReceiverEmail = Notification::ALL_STUDENT_EMAIL;
    	$user = User::model()->findByPk($user->id);
    	$condition = "(receiver_id=$user->id)";
    	if($user->role==User::ROLE_TEACHER){
    		$allReceiverEmail = Notification::ALL_TEACHER_EMAIL;
    	}
    	$commonUser = User::model()->findByAttributes(array('email'=>$allReceiverEmail));
    	if(isset($commonUser->id)){
    		$condition = "(receiver_id=$user->id OR receiver_id=$commonUser->id)";
    	}
    	//Get not/or not confirmed notifications
    	if($confirmed!==null){
    		$operation = ($confirmed)? 'LIKE': 'NOT LIKE';
    		$condition .= " AND (CONCAT(',', `confirmed_ids`, ',') ".$operation." '%,".$user->id.",%'";
    		$condition .= (!$confirmed)? " OR confirmed_ids IS NULL)": ")";
    	}
    	$condition .= " AND (created_date>'".$user->created_date."') AND (deleted_flag=0)";
    	$criteria = new CDbCriteria();
		$criteria->condition = $condition;
		if(!$count){//Not count, return all results
			if($limit!=null) $criteria->limit = $limit;
			$criteria->order = "created_date DESC";
	    	$notifications = Notification::model()->findAll($criteria);
	    	return $notifications;
		}else{
			return Notification::model()->count($criteria);
		}
    }
    
	/**
	 * Get received user notification
	 */
	public function displayReceivedUser()
	{
		$user = User::model()->findByPk($this->receiver_id);
		ClsAdminHtml::displayConnectedUser($user);
	}
	
	/**
	 * Check is unread notification
	 */
	public function isConfirmed($userId)
	{
		if(trim($this->confirmed_ids)!=""){
			$confirmedIds = explode(',', $this->confirmed_ids);
			if(in_array($userId, $confirmedIds)){
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Display confirmed notification from users
	 * 0=>'Thông báo riêng', 1=>'Tất cả HS', 2=>'Tất cả GV' 
	 */
	public static function displayConfirmedUsers($confirmedIds)
	{
		$strDisplay = "Chưa đọc";//Display html to list notice
		if(trim($confirmedIds)!=""){
			$readIds = explode(',', $confirmedIds);
			if(trim($readIds[count($readIds)-1])==""){
				unset($readIds[count($readIds)-1]);
			}
			$strDisplay = count($readIds).' người đã đọc';
		}
		echo $strDisplay;
	}
}
