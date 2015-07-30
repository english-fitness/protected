<?php

/**
 * This is the model class for table "tbl_student".
 *
 * The followings are the available columns in table 'tbl_student':
 * @property integer $user_id
 * @property integer $class_id
 * @property string $short_description
 * @property string $description
 * @property string $created_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Class $class
 */
class Student extends CActiveRecord
{
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
		return 'tbl_student';
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
			array('user_id, class_id', 'numerical', 'integerOnly'=>true),
			array('short_description,care_status', 'length', 'max'=>256),
			array('last_sale_date', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			array('class_id, short_description, description, modified_date, father_name, mother_name, father_phone, mother_phone,care_status,sale_status,sale_note,sale_user_id,last_sale_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, class_id,sale_status,sale_note,sale_user_id,last_sale_date,short_description, description, created_date, modified_date,care_status', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
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
			'user' => array(self::BELONGS_TO, 'User', 'user_id'),
			'class' => array(self::BELONGS_TO, 'Class', 'class_id'),
		);
	}
	
	//Strip tag before save student profile
	public function beforeSave()
	{
		//Remove html tags of some fields before save Student
		$stripTagFields = array('short_description', 'father_name', 'mother_name', 'father_phone', 'mother_phone');
		foreach($stripTagFields as $textField){
			$this->$textField = strip_tags($this->$textField);
		}
		$this->description = strip_tags($this->description, Common::allowHtmlTags());
		//Set null for empty field in table
		if($this->last_sale_date=='') $this->last_sale_date = NULL;
		return true;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Mã học sinh',
			'class_id' => 'Lớp học',
			'short_description' => 'Mô tả ngắn',
			'description' => 'Mô tả đầy đủ',
			'father_name' => 'Họ tên bố',
			'mother_name' => 'Họ tên mẹ',
			'father_phone' => 'Số điện thoại của bố',
			'mother_phone' => 'Số điện thoại của mẹ',
			'care_status' => 'Trạng thái chăm sóc',
			'sale_status' => 'Trạng thái Sale',
			'sale_note' => 'Ghi chú tư vấn',
			'sale_user_id' => 'Người tư vấn',
			'last_sale_date' => 'Ngày tư vấn cuối',
			'created_date' => 'Ngày tạo',
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
		$criteria->compare('class_id',$this->class_id);
		$criteria->compare('short_description',$this->short_description,true);
		$criteria->compare('description',$this->description,true);
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
	 * @return Student the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
	
	/**
	 * Display step follow for new student
	 */
	public function getMainFollowingSteps()
	{
		$userId = Yii::app()->user->id;
    	$user = User::model()->findByPk($userId);
		$followingSteps = array(
			User::STATUS_ENOUGH_PROFILE => array(
				'title'=>'Bước 2: Cập nhật thông tin cá nhân',
				'link'=>'/student/account/index',
			),
			User::STATUS_ENOUGH_AUDIO => array(
				'title'=>'Bước 3: Kiểm tra loa, mic',
				'link'=>'/student/testCondition/index',
			),
			User::STATUS_REGISTERED_COURSE => array(
				'title'=>'Bước 4: Đăng ký khóa học',
				'link'=>'/student/courseRequest/index',
			),
		);
		return $followingSteps;		
	}
	
	/**
	 * Load & filter student from user
	 */
	public function filterModelUser($modelUser, $params=array())
	{
		$criteria = new CDbCriteria();
        $criteria->compare('role',User::ROLE_STUDENT);
        $criteria->compare('deleted_flag', 0);
        if(isset($params['class_id'])){
        	if($params['class_id']==0){
	        	$criteria->addCondition('(SELECT class_id FROM tbl_student WHERE user_id=id) is NULL');
        	}else{
        		$criteria->compare('(SELECT class_id FROM tbl_student WHERE user_id=id)',"=".$params['class_id'],true);
        	}
        }
        if(isset($params['fullname'])){
        	$criteria->addCondition("CONCAT(`lastname`,' ',`firstname`) LIKE '%".$params['fullname']."%'");
        }
		if(isset($params['sale_status'])){
        	$criteria->addCondition("(SELECT sale_status FROM tbl_student WHERE user_id=id) LIKE '%".$params['sale_status']."%'");
        }
		if(isset($params['sale_user_id'])){
        	$criteria->addCondition("(SELECT sale_user_id FROM tbl_student WHERE user_id=id) = ".$params['sale_user_id']);
        }
		if(isset($params['care_status'])){
        	$criteria->addCondition("(SELECT care_status FROM tbl_student WHERE user_id=id) = ".$params['care_status']);
        }
        $modelUser->setDbCriteria($criteria);
        return $modelUser;
	}
	
	/**
	 * Get class by student id
	 */
	public function displayClass($userId)
	{
		$student = Student::model()->findByPk($userId);
		if(isset($student->class_id)){
			$userClass = Classes::model()->findByPk($student->class_id);
			if(isset($userClass->name)){
				return $userClass->name;
			}
		}
		return NULL;
	}
	
	/**
	 * Get class by student id
	 */
	public function assignedCourses($studentId=null)
	{
		if($studentId==null){
			$studentId = $this->class_id;
		}
		$query = "SELECT course_id FROM tbl_course_student WHERE student_id=".$studentId;
		$courseIds = Yii::app()->db->createCommand($query)->queryColumn();
		return $courseIds;
	}
	
	/**
	 * Count course of a student to display
	 */
	public function displayCourseLink($studentId, $shortLabel="")
	{
		$displayLink = "";
		$count = CourseStudent::model()->countByAttributes(array('student_id'=>$studentId));
		if($count>0){
			$displayLink = CHtml::link($count.$shortLabel, Yii::app()->createUrl("admin/course?student_id=$studentId"));
		}
		return $displayLink;
	}
	
	public function displayCourseMonitorLink($studentId, $shortLabel="", $type=null){
		$displayLink = "";
		
		if ($type == null){
			$count = CourseStudent::model()->countByAttributes(array('student_id'=>$studentId));
		} else {
			$query = "SELECT COUNT(*) FROM tbl_course JOIN tbl_course_student " .
					 "ON tbl_course.id = tbl_course_student.course_id " .
					 "WHERE tbl_course_student.student_id = " . $studentId . " " .
					 "AND tbl_course.type = " . $type;
			$count = Yii::app()->db->createCommand($query)->queryScalar();
		}
		
		if($count>0){
			$params = "sid=" . $studentId;
			if ($type != null){
				$params .= "&Course[type]=" . $type;
			}
			
			$displayLink = CHtml::link($count.$shortLabel, Yii::app()->createUrl("admin/sessionMonitor/courseView?" .  $params));
		}
		return $displayLink;
	}
	
	/**
	 * Count pre register course of a student to display
	 */
	public function displayPreCourseLink($studentId, $shortLabel="")
	{
		$displayLink = "";
		$count = PreregisterCourse::model()->countByAttributes(array('student_id'=>$studentId, 'deleted_flag'=>0));
		if($count>0){
			$displayLink = CHtml::link($count.$shortLabel, Yii::app()->createUrl("admin/preregisterCourse?student_id=$studentId"));
		}
		return $displayLink;
	}
	
	/**
	 * Delete student profile by UserId
	 */
	public function deleteStudentByUser($userId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "user_id = $userId";
		Student::model()->deleteAll($criteria);
	}
	
	/**
	 * Remove forever a student
	 */
	public function deleteForeverStudent($userId)
	{
		//Delete session comments of student
		SessionComment::model()->deleteCommentsByUser($userId);
		//Delete course comments of student
		CourseComment::model()->deleteCommentsByUser($userId);
		//Remove assigned sessions of student
		Session::model()->deleteAssignedSessionsByUser($userId, 'student');
		//Remove assigned courses of student
		Course::model()->deleteAssignedCoursesByUser($userId, 'student');
		//Delete preregister course by user
		PreregisterCourse::model()->deletePreCourseByUser($userId);
		//Delete notifications of student
		Notification::model()->deleteNotificationsByUser($userId);
		//Delete messages of student
		Message::model()->deleteSentMessagesByUser($userId);
		//Delete Facebook account profile of student
		UserFacebook::model()->deleteFacebookByUser($userId);
		//Delete Google account profile of student
		UserGoogle::model()->deleteGoogleByUser($userId);
		//Delete Hocmai account profile of student
		UserHocmai::model()->deleteHocmaiByUser($userId);
		//Delete student profile of student
		Student::model()->deleteStudentByUser($userId);
		//Delete user after delete all profiles
		$studentUser = User::model()->findByPk($userId);
		$studentUser->delete();//Delete last info of User
	}
	
	/**
	 * Get studentIds by student email strings
	 */
	public function getStudentIdsByEmails($strEmails)
	{
		$emailArrs = explode(',', str_replace(' ', '', $strEmails));
		if(count($emailArrs)>0){
			foreach($emailArrs as $key=>$val){
				$emailArrs[$key] = trim($val);//Trim email of student
			}
		}
		$whereInEmail = "('".implode("','", $emailArrs)."')";
		$query = "SELECT id FROM tbl_user WHERE email IN ".$whereInEmail;
		$studentIds = Yii::app()->db->createCommand($query)->queryColumn();
		return $studentIds; 
	}
	
	/**
	 * Check existed free trial course of student
	 */
	public function checkExistedTrialCourse($studentId)
	{
		$criteria = new CDbCriteria();
		$criteria->compare('(SELECT type FROM tbl_course WHERE id=course_id)',"=".Course::TYPE_COURSE_TRAINING,true);
		$criteria->compare('student_id',"=$studentId",true);
		$count = CourseStudent::model()->count($criteria);
		if($count>0) return true;
		//Check existed paid training course
		$existedPaidTrainingCourse = $this->checkExistedPaidTrainingPreCourse($studentId);
		return $existedPaidTrainingCourse;
	}
	
	/**
	 * Check existed training paid pre course
	 */
	public function checkExistedPaidTrainingPreCourse($studentId)
	{
		$attributes = array(
			'student_id' => $studentId,
			'course_type' => Course::TYPE_COURSE_TRAINING,
			'payment_status' => PreregisterCourse::PAYMENT_STATUS_PAID,
		);
		$count = PreregisterCourse::model()->countByAttributes($attributes);
		if($count>0) return true;
		return false;
	}
	
	/**
	 * Display filter by class option
	 */
	public function displayFilterClasses($selected)
	{
		$classes = CHtml::listData(Classes::model()->getAll(false), 'id', 'name');
		$classes = array(""=>"",0=>'Lớp -----') + $classes;
		return CHtml::dropDownList('Student[class_id]', $selected, $classes, array());
	}
	
	/**
	 * Display sale status of student
	 */
	public function displaySaleStatus($userId)
	{
		$student = Student::model()->findByPk($userId);
		if(isset($student->sale_status)){
			return $student->sale_status;
		}
		return NULL;
	}
	
	/**
	 * Display care status of student
	 */
	public function displayCareStatus($userId)
	{
		$student = Student::model()->findByPk($userId);
		return $this->careStatusOptions($student->care_status);
	}
	
	/**
	 * Display sale usef of student
	 */
	public function displaySaleUser($userId)
	{
		$student = Student::model()->findByPk($userId);
		if(isset($student->sale_user_id)){
			$saleUser = User::model()->findByPk($student->sale_user_id);
			if(isset($saleUser->id)) return $saleUser->fullName();
		}
		return NULL;
	}
	
	/**
	 * Get admin & monitor to sale student
	 */
	public function getSalesUserOptions($isShowEmail=true, $firstLabel="", $showFirst=true)
	{
		$criteria = new CDbCriteria();
        $criteria->condition = "role IN('".User::ROLE_ADMIN."','".User::ROLE_MONITOR."')";
        $criteria->compare('deleted_flag', 0);
        $users = User::model()->findAll($criteria);
        $saleUsers = array();//Init sale user
        if($showFirst) $saleUsers = array(""=>$firstLabel);//Sale user
        if(count($users)>0){
        	foreach($users as $key=>$user){
        		if($isShowEmail){
        			$saleUsers[$user->id] = $user->fullName().' ('.$user->email.')';
        		}else{
        			$saleUsers[$user->id] = $user->fullName();
        		}
        	}
        }
        return $saleUsers;
	}
	
}
