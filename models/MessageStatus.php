<?php

/**
 * This is the model class for table "tbl_message_status".
 *
 * The followings are the available columns in table 'tbl_message_status':
 * @property integer $id
 * @property integer $message_id
 * @property integer $recipient_id
 * @property integer $read_flag
 * @property string $read_date
 */
class MessageStatus extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_message_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('message_id, recipient_id', 'required'),
			array('message_id, recipient_id, read_flag', 'numerical', 'integerOnly'=>true),
			array('read_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, message_id, recipient_id, read_flag, read_date', 'safe', 'on'=>'search'),
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
	
	//After save message status
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete message status
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'message_id' => 'Mã tin nhắn',
			'recipient_id' => 'Người nhận',
			'read_flag' => 'Trạng thái đọc',
			'read_date' => 'Ngày đọc',
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
		$criteria->compare('message_id',$this->message_id);
		$criteria->compare('recipient_id',$this->recipient_id);
		$criteria->compare('read_flag',$this->read_flag);
		$criteria->compare('read_date',$this->read_date,true);
		$criteria->order = 'read_date ASC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}
	
    public function to($usersArr,$messageId) {
        if(is_array($usersArr)) foreach($usersArr as $userId){
        	$messageStatus = MessageStatus::model()->findByAttributes(array('message_id'=>$messageId, 'recipient_id'=>$userId));
        	if(!isset($messageStatus->id)){
	            $messageStatus = new MessageStatus();
	            $messageStatus->message_id = $messageId;
	            $messageStatus->recipient_id = $userId;
	            $messageStatus->read_flag = 0;
	            $messageStatus->save();
        	}
        }
        return $messageStatus;
    }

    public $userModel;
    /* get User*/
    public function getUser() {
        if(!$this->userModel) {
            $this->userModel = User::model()->findByPk($this->recipient_id);
        }
        return $this->userModel;
    }
    
	/**
	 * Display received user name
	 */
	public function displayReceivedUser()
	{
		if($this->recipient_id){
			$recipient = User::model()->findByPk($this->recipient_id);
			if(isset($recipient->id)){
				return $recipient->fullName();
			}
		}
		return NULL;
	}

    /* getStatusLabel */
    public function getStatusLabel() {
        $status = array(0=>"Chưa xem",1=>$this->getReadFlagDate());
        return $status[$this->read_flag];
    }

    public function getReadFlagDate() {
            return  'Đã xem';
    }

    /**
     * Create nearset session data provider
     */
    public function getInboxMessages($userId, $pageSize=5)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "recipient_id=$userId";
        $criteria->compare('(SELECT deleted_flag FROM tbl_message WHERE id=message_id)',"=0",true);
        $criteria->order = '(SELECT created_date FROM tbl_message WHERE id=message_id) DESC';
        $count = MessageStatus::model()->count($criteria);
        $pages = new CPagination($count);
        // results per page
        $pages->pageSize=$pageSize;
        $pages->applyLimit($criteria);
        $messages = MessageStatus::model()->findAll($criteria);
        return array(
            'messages' => $messages,
            'pages' => $pages,
        );
    }

    /*count Message Not Read Flag*/
    public function countMessageNotReadFlag($userId) {
        $criteria = new CDbCriteria;
        $criteria->condition = "read_flag=0 and recipient_id=$userId";
        $criteria->compare('(SELECT deleted_flag FROM tbl_message WHERE id=message_id)',"=0",true);
        return MessageStatus::model()->count($criteria);
    }


    /* getMessage*/
    public  $_message;
    public function getMessage() {
        if(!$this->_message) {
            $this->_message = Message::model()->findByPk($this->message_id);
        }
        return $this->_message;
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MessageStatus the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    public function getUserIdsByEmails($emailArr)
    {
        $whereInEmail = "('".implode("','", $emailArr)."')";
        $query = "SELECT id FROM tbl_user WHERE email IN ".$whereInEmail;
        $userIds = Yii::app()->db->createCommand($query)->queryColumn();
        return $userIds;
    }

}
