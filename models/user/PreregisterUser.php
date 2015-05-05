<?php

/**
 * This is the model class for table "tbl_preregister_user".
 *
 * The followings are the available columns in table 'tbl_preregister_user':
 * @property integer $id
 * @property string $email
 * @property string $fullname
 * @property string $birthday
 * @property integer $gender
 * @property string $address
 * @property string $phone
 * @property string $class_name
 * @property string $parent_name
 * @property string $parent_phone
 * @property string $subject_note
 * @property string $objective
 * @property string $content_request
 * @property string $teacher_request
 * @property integer $user_type
 * @property string $sale_status
 * @property string $sale_note
 * @property integer $sale_user_id
 * @property string $last_sale_date
 * @property integer $refer_user_id
 * @property string $created_date
 * @property string $modified_date
 */
class PreregisterUser extends CActiveRecord
{
	//Const for status of PreUser
    const STATUS_PENDING = 0;//Pending status
    const STATUS_APPROVED = 1;//Approved status
    
	//Const for role of user
	const TYPE_USER_STUDENT = 0;//Type Student  user
	const TYPE_USER_TEACHER = 1;//Type teacher user
	const TYPE_USER_PARENT = 2;//Type  parent of user
    
	//Const for care status of user
    const CARE_STATUS_PENDING = 0;//Pending status
    const CARE_STATUS_APPROVED = 1;//Approved status
    const CARE_STATUS_WORKING = 2;//Working status
    const CARE_STATUS_DISABLED = 3;//Disabled status
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_preregister_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('fullname, phone, email', 'required'),
			array('gender, user_type, sale_user_id, refer_user_id, status', 'numerical', 'integerOnly'=>true),
			array('email', 'length', 'max'=>128),
			array('email', 'email'),
			array('fullname, address, parent_name, subject_note, objective, care_status', 'length', 'max'=>256),
			array('phone', 'length', 'max'=>20),
			array('class_name, parent_phone, sale_status', 'length', 'max'=>80),
			array('birthday, last_sale_date', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			array('birthday, content_request, teacher_request, status, care_status, sale_note, last_sale_date, created_user_id, modified_user_id, created_date, modified_date, deleted_flag', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, email, fullname, birthday, gender, address, phone, class_name, parent_name, parent_phone, subject_note, objective, content_request, teacher_request, user_type, status, care_status, sale_status, sale_note, sale_user_id, last_sale_date, refer_user_id, created_date, modified_date', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'fullname' => 'Tên đầy đủ',
			'birthday' => 'Ngày sinh',
			'gender' => 'Giới tính',
			'address' => 'Địa chỉ',
			'phone' => 'Số điện thoại',
			'class_name' => 'Khối lớp',
			'parent_name' => 'Tên phụ huynh',
			'parent_phone' => 'ĐT phụ huynh',
			'subject_note' => 'Môn muốn gia sư',
			'objective' => 'Mã Code',
			'content_request' => 'Yêu cầu học tập',
			'teacher_request' => 'Yêu cầu giáo viên',
			'user_type' => 'Người đăng ký',
			'status' => 'Trạng thái',
			'care_status' => 'Trạng thái chăm sóc',
			'sale_status' => 'Trạng thái Sale',
			'sale_note' => 'Ghi chú tư vấn',
			'sale_user_id' => 'Người tư vấn',
			'last_sale_date' => 'Ngày tư vấn cuối',
			'refer_user_id' => 'Mã thành viên (ID)',
			'created_user_id' => 'Người tạo',
			'modified_user_id' => 'Người sửa',
			'deleted_flag' => 'Trạng thái xóa',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('fullname',$this->fullname,true);
		$criteria->compare('birthday',$this->birthday,true);
		$criteria->compare('gender',$this->gender);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('class_name',$this->class_name,true);
		$criteria->compare('parent_name',$this->parent_name,true);
		$criteria->compare('parent_phone',$this->parent_phone,true);
		$criteria->compare('subject_note',$this->subject_note,true);
		$criteria->compare('objective',$this->objective,true);
		$criteria->compare('content_request',$this->content_request,true);
		$criteria->compare('teacher_request',$this->teacher_request,true);
		$criteria->compare('user_type',$this->user_type);
		$criteria->compare('status',$this->status);
		$criteria->compare('care_status',$this->care_status,true);
		$criteria->compare('sale_status',$this->sale_status,true);
		$criteria->compare('sale_note',$this->sale_note,true);
		$criteria->compare('sale_user_id',$this->sale_user_id);
		$criteria->compare('last_sale_date',$this->last_sale_date,true);
		$criteria->compare('refer_user_id',$this->refer_user_id);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('modified_user_id',$this->modified_user_id);
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
	 * @return PreregisterUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//Remove html tags of some fields before save Preregister User
	public function beforeSave()
	{
		$stripTagFields = array('fullname','address','phone','class_name','parent_name','parent_phone','subject_note','objective','content_request','teacher_request');
		foreach($stripTagFields as $textField){
			$this->$textField = strip_tags($this->$textField, Common::allowHtmlTags());
		}
		if($this->birthday=='') $this->birthday = NULL;
		if($this->last_sale_date=='') $this->last_sale_date = NULL;
		return true;
	}
		
	//After save Preregister User
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete Preregister User
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
	/**
	 * Preregister User type options
	 */
	public function userTypeOptions($type=null)
	{
		$typeOptions = array(
			self::TYPE_USER_STUDENT => 'Học sinh',
			self::TYPE_USER_TEACHER => 'Giáo viên',
			self::TYPE_USER_PARENT => 'Phụ huynh',
		);
		if($type==null){
			return $typeOptions;
		}elseif(isset($typeOptions[$type])){
			return $typeOptions[$type];
		}
		return null;
	}
	
	//Display Status options
	public function statusOptions($status=null)
	{
		$statusOptions = array(
			self::STATUS_PENDING => 'Đang chờ',
			self::STATUS_APPROVED => 'Đã xác nhận',
		);
		if($status==null){
			return $statusOptions;
		}elseif(isset($statusOptions[$status])){
			return $statusOptions[$status];
		}
		return null;
	}
	
	/**
	 * Care status label statuses
	 */
	public function careStatusOptions($careStatus=null)
	{
		$careStatusOptions = array(
			self::CARE_STATUS_PENDING => 'Chưa chăm sóc',
			self::CARE_STATUS_APPROVED => 'Hẹn chăm sóc',
			self::CARE_STATUS_WORKING => 'Đang chăm sóc',
			self::CARE_STATUS_DISABLED => 'Dừng chăm sóc',
		);
		if($careStatus==null){
			return $careStatusOptions;
		}elseif(isset($careStatusOptions[$careStatus])){
			return $careStatusOptions[$careStatus];
		}
		return null;
	}
	
}
