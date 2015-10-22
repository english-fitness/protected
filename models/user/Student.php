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
    //these values are to match with current value
    //may need changes for better coherent
    // const STATUS_NEW_REGISTER = 0;
    // const STATUS_TRIAL_TEST = 5;
    // const STATUS_TRIAL_TEACHER = 6;
    // const STATUS_TRIAL_COMPLETE = 4;
    // const STATUS_DISCONTINUED_STUDENT = 3;
    // const STATUS_NEW_STUDENT = 7;
    // const STATUS_OLD_STUDENT = 8;

    const STATUS_NEW_REGISTER = 0;
    const STATUS_TRIAL_TEST = 2;
    const STATUS_TRIAL_TEACHER = 3;
    const STATUS_TRIAL_COMPLETE = 4;
    const STATUS_DISCONTINUED_STUDENT = 5;
    const STATUS_NEW_STUDENT = 6;
    const STATUS_OLD_STUDENT = 7;
    
    public static function statusOptions($status=null){
        $statusOptions = array(
            self::STATUS_NEW_REGISTER => 'Mới đăng ký',
			self::STATUS_TRIAL_TEST => 'Đang học thử/Test',
			self::STATUS_TRIAL_TEACHER => 'Đang học thử/GV',
			self::STATUS_TRIAL_COMPLETE => 'Học viên/Quản lý',
            self::STATUS_DISCONTINUED_STUDENT => 'Học viên/Nghỉ',
			self::STATUS_NEW_STUDENT => 'Học viên mới',
			self::STATUS_OLD_STUDENT => 'Học viên VIP',
		);
		if($status==null){
			return $statusOptions;
		}elseif(isset($statusOptions[$status])){
			return $statusOptions[$status];
		}
		return null;
    }

    public static function filterOptions($status=null){
    	$filterOptions = array(
            self::STATUS_NEW_REGISTER => 'Mới đăng ký',
			self::STATUS_TRIAL_TEST => 'Đang học thử/Test',
			self::STATUS_TRIAL_TEACHER => 'Đang học thử/GV',
			self::STATUS_TRIAL_COMPLETE => 'Học viên/Quản lý',
            self::STATUS_DISCONTINUED_STUDENT => 'Học viên/Nghỉ',
            '>='.self::STATUS_NEW_STUDENT=>'Học viên',
			self::STATUS_NEW_STUDENT => 'Học viên mới',
			self::STATUS_OLD_STUDENT => 'Học viên VIP',
		);
		if($status==null){
			return $filterOptions;
		}elseif(isset($filterOptions[$status])){
			return $filterOptions[$status];
		}
		return null;
    }
    
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
			array('user_id, preregister_id', 'numerical', 'integerOnly'=>true),
			array('contact_phone', 'match', 'pattern'=>'/^\+{0,1}[0-9\-\s]{8,16}$/'),
			array('contact_email', 'email'),
            array('sale_status, contact_name, contact_phone, contact_email', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, official_start_date, created_date, modified_date', 'safe', 'on'=>'search'),
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
			'preregisterUser'=>array(self::BELONGS_TO, 'PreregisterUser', 'preregister_id'),
		);
	}
	
	//Strip tag before save student profile
	public function beforeSave()
	{
		return true;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Mã học sinh',
            'preregister_id'=>'Tư vấn',
            'official_start_date'=>'Thành viên chính thức từ ngày',
            'sale_status'=>'Trạng thái sale',
            'contact_name'=>'Người liên hệ',
            'contact_phone'=>'Số điện thoại liên hệ',
            'contact_email'=>'Email liên hệ',
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
        $criteria->compare('official_start_date',$this->official_start_date,true);
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
	 * Load & filter student from user
	 */
	public function filterModelUser($modelUser, $params=array())
	{
		$criteria = new CDbCriteria();
        $criteria->compare('role',User::ROLE_STUDENT);
        $criteria->compare('deleted_flag', 0);
        if(isset($params['fullname'])){
        	$criteria->addCondition("CONCAT(`lastname`,' ',`firstname`) LIKE '%".$params['fullname']."%'");
        }
        $modelUser->setDbCriteria($criteria);
        return $modelUser;
	}
	
	
	/**
	 * Get assigned courses by student id
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
			$displayLink = CHtml::link($count." ".$shortLabel, Yii::app()->createUrl("admin/course?student_id=$studentId"));
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
    
    public static function findByFullname($fullname, $returnAttributes=array()){
		if (empty($returnAttributes)){
			$returnModels = true;
		} else {
			$returnModels = false;
		}
		
		if ($returnModels){
			$query = "SELECT * FROM tbl_user u " .
					 "WHERE CONCAT(u.`lastname`, ' ', u.`firstname`) LIKE '%".$fullname."%'
                     AND u.`role` = 'role_student'";
		} else {
			$query = "SELECT " . implode(',', $returnAttributes) . " FROM tbl_user u " .
					 "WHERE CONCAT(u.`lastname`, ' ', u.`firstname`) LIKE '%".$fullname."%'
                     AND u.`role` = 'role_student'";
		}
        
		if ($returnModels){
			return self::model()->findAllBySql($query);
		} else {
			return Yii::app()->db->createCommand($query)->queryAll();
		}
	}
}
