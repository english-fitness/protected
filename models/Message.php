<?php

/**
 * This is the model class for table "tbl_message".
 *
 * The followings are the available columns in table 'tbl_message':
 * @property integer $id
 * @property integer $sender_id
 * @property integer $receiver_id
 * @property string $title
 * @property string $content
 * @property integer $receiver_status
 * @property string $created_date
 *
 * The followings are the available model relations:
 * @property User $receiver
 * @property User $sender
 */
class Message extends CActiveRecord
{
    /* user model */
    public $userModel = null;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_message';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('sender_id, title, content', 'required'),
			array('sender_id, deleted_flag', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>256),
			array('recipient_email, deleted_flag, modified_user_id, modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sender_id, title, content, recipient_email, created_date, modified_user_id, modified_date', 'safe', 'on'=>'search'),
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		//Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
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
            'recipient' => array(self::HAS_MANY, 'MessageStatus', 'message_id'),
			'sender' => array(self::BELONGS_TO, 'User', 'sender_id'),
		);
	}

	//Strip tag before save Message
	public function beforeSave()
	{
		//Remove html tags of some fields before save Message
		$this->title = strip_tags($this->title);
		$this->content = strip_tags($this->content, Common::allowHtmlTags());
		return true;
	}
	
	//After save message
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete message
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
			'id' => 'Mã',
			'sender_id' => 'Người gửi',
			'recipient_email' => 'Người nhận',
			'title' => 'Tiêu đề',
			'content' => 'Nội dung',
			'created_date' => 'Ngày gửi',
			'modified_date' => 'Ngày sửa',
			'deleted_flag' => 'Trạng thái xóa',
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
		$criteria->compare('sender_id',$this->sender_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		$criteria->order = 'created_date DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}

    /**
     * Create nearset session data provider
     */
    public function getSentMessage($userId, $pageSize=5)
    {
        $criteria = new CDbCriteria;
        $criteria->condition = "sender_id=$userId AND deleted_flag=0";
        $criteria->order = 'created_date DESC';
        $count = Message::model()->count($criteria);
        $pages = new CPagination($count);
        // results per page
        $pages->pageSize=$pageSize;
        $pages->applyLimit($criteria);
        $messages = Message::model()->findAll($criteria);
        return array(
            'messages' => $messages,
            'pages' => $pages,
        );
    }

    /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Message the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /*sent*/
    public function send($form,$to,$value) {
        $model = new Message();
        $model->attributes = $value;
        $model->sender_id = $form;
        if($model->save())
            return MessageStatus::model()->to($to,$model->id);
        return $model;

    }

    /* get User*/
    public function getUser() {
        if(!$this->userModel)
            return $this->userModel = User::model()->findByPk($this->sender_id);
        return $this->userModel;
    }

    public $getAllRecipient;
    /* get User*/
    public function getAllRecipient() {
        if(!$this->getAllRecipient)
            return $this->getAllRecipient = MessageStatus::model()->findAllByAttributes(array('message_id'=>$this->id));
        return $this->getAllRecipient;
    }
    /* get link user*/
    public  function getLinkUserByAdminPage($displayPhone=false) {
    	$sender = User::model()->findByPk($this->sender_id);
    	if(isset($sender->id)){
    		$phoneStr = ($displayPhone && $sender->phone)? ' <span class="fsNormal">('.$sender->phone.')</span>':"";
    		if($sender->role==User::ROLE_TEACHER){
    			$senderRoleLabel = '<span class="clrRed">Giáo viên: </span>';
    			$phoneStr = ($displayPhone && $sender->phone)? ' <span class="fsNormal">('.$sender->phone.')</span>':"";
    		}elseif($sender->role==User::ROLE_STUDENT){
    			$senderRoleLabel = '<span>Học sinh: </span>';
    		}
        	return $senderRoleLabel.CHtml::link($sender->fullName(), Yii::app()->createUrl("admin/user/view/id/".$sender->id."")).$phoneStr;
    	}
    	return NULL;
    }

    /*readFlagUpdate*/
    public function readFlagUpdate($uid) {
        $model = MessageStatus::model()->findByAttributes(array("recipient_id"=>$uid,"message_id"=>$this->id));
        if(isset($model) && $model->read_flag==0) {
            $model->read_flag =1;
            $model->read_date =date('Y-m-d H:i:s');
            if($model->save()){
            	$this->modified_user_id = Yii::app()->user->id;
            	$this->save();
            }
        }
    }
    /**
     * count all session of Course
     */
    public function countRecipient($readFlag = null)
    {
        $criteria = new CDbCriteria();
        if($readFlag) {
            $readFlag = "read_flag = $readFlag and ";
        }
        $criteria->condition = $readFlag." message_id = $this->id";
        $count = MessageStatus::model()->count($criteria);
        return $count;
    }
    
	/**
	 * Delete all messages by UserId
	 */
	public function deleteSentMessagesByUser($userId) {
		$criteria = new CDbCriteria();
		$criteria->condition = "sender_id = $userId";
		Message::model()->deleteAll($criteria);
	}
	
	/**
	 * Display number of recipients of message
	 */
	public static function displayMessageRecipients($messageId, $count=0, $senderId=1)
	{
		$sender = User::model()->findByPk($senderId);
		//Send Id is not admin, Admin is recipient
		if(isset($sender) && in_array($sender->role, array(User::ROLE_STUDENT, User::ROLE_TEACHER))){
			return "DạyKèm123";
		}else{
			$readLink = '/admin/message/recipients?id='.$messageId;
			return "<a href='".$readLink."'>".$count." người nhận</a>";
		}		
	}
	
 	/**
 	 * Generate assigned student as array key=>name(email)
	 */
	public function getRecipientsInMessage($userViewLink=null)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('message_id', $this->id);
        $recipients = MessageStatus::model()->findAll($criteria);
        $messageRecipients = array();//Generate message user
        if(count($recipients)>0){
        	foreach($recipients as $recipient){
        		$user = User::model()->findByPk($recipient->recipient_id);
        		if(isset($user->id)){
	        		$readFlagStyle = "class='clrBlack' title='Chưa đọc'";
	        		if($recipient->read_flag==1){
	        			$readFlagStyle = "class='clrLink' title='Ngày, giờ đọc: ".date('H:i, d/m/Y', strtotime($recipient->read_date))."'";
	        		}
					if($userViewLink!=null){
						$messageRecipients[$user->id] = '<a href="'.$userViewLink.'/'.$user->id.'" '.$readFlagStyle.'>'.$user->fullName().'</a>';
					}else{
						$messageRecipients[$user->id] = '<span '.$readFlagStyle.'>'.$user->fullName().'</span>';
					}
        		}
        	}
        }
		return $messageRecipients;
	}

}
