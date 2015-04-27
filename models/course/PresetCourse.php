<?php

/**
 * This is the model class for table "tbl_preset_course".
 *
 * The followings are the available columns in table 'tbl_preset_course':
 * @property integer $id
 * @property integer $subject_id
 * @property integer $teacher_id
 * @property string $title
 * @property string $note
 * @property double $price_per_student
 * @property integer $min_student
 * @property integer $max_student
 * @property integer $total_of_session
 * @property string $start_date
 * @property string $session_per_week
 * @property integer $status
 * @property integer $course_id
 * @property string $created_date
 * @property string $modified_date
 * @property integer $deleted_flag
 */
class PresetCourse extends CActiveRecord
{
	const STATUS_PENDING = 0;//Pending status
	const STATUS_APPROVED = 1;//Approved status
	const STATUS_REGISTERING = 2;//On Registering status
	const STATUS_END_REGISTERING = 3;//On End Registering status
	const STATUS_ACTIVATED = 4;//Activated status	
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_preset_course';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('subject_id, teacher_id, title, price_per_student, min_student, max_student, total_of_session, start_date, session_per_week', 'required'),
			array('subject_id, teacher_id, min_student, max_student, total_of_session, status, course_id, created_user_id, deleted_flag,', 'numerical', 'integerOnly'=>true),
			array('price_per_student', 'numerical'),
			array('title', 'length', 'max'=>256),
			array('total_of_session', 'numerical', 'integerOnly'=>true, 'min'=>4, 'max'=>100),
			array('short_description, description, modified_date, price_rules, note,modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, subject_id, teacher_id, title, short_description, description, price_per_student, price_rules, note, min_student, max_student, total_of_session, start_date, session_per_week, status, course_id, created_user_id,modified_user_id, created_date, modified_date, deleted_flag', 'safe', 'on'=>'search'),
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
	
	//Before save Preset course
	public function beforeSave()
	{
		//Remove html tags of some fields before save Course
		$this->title = strip_tags($this->title);
		$this->short_description = strip_tags($this->short_description);
		$this->description = strip_tags($this->description, Common::allowHtmlTags());
		return true;
	}
	
	//After save Preset course
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete Preset course
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
			'id' => 'Mã khóa học',
			'subject_id' => 'Môn học',
			'teacher_id' => 'Giáo viên',
			'title' => 'Tiêu đề',
			'short_description' => 'Mô tả ngắn',
			'description' => 'Mô tả khóa học',
			'price_per_student' => 'Học phí/1 HS',
			'price_rules' => 'Ưu đãi học phí',
			'min_student' => 'Số học sinh tối thiểu',
			'max_student' => 'Số học sinh tối đa',
			'total_of_session' => 'Số buổi',
			'start_date' => 'Ngày bắt đầu',
			'session_per_week' => 'Lịch học trong tuần',
			'status' => 'Trạng thái',
			'course_id' => 'Khóa học',
			'note' => 'Ghi chú khóa học',
			'created_user_id' => 'Người tạo',
			'modified_user_id' => 'Người sửa',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
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
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('teacher_id',$this->teacher_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('short_description',$this->short_description,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('price_per_student',$this->price_per_student);
		$criteria->compare('min_student',$this->min_student);
		$criteria->compare('max_student',$this->max_student);
		$criteria->compare('total_of_session',$this->total_of_session);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('session_per_week',$this->session_per_week,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('course_id',$this->course_id);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->compare('deleted_flag',$this->deleted_flag);

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
	 * @return PresetCourse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//Status options
	public function statusOptions(){
        return array(
        	self::STATUS_PENDING => 'Đang chờ',
        	self::STATUS_APPROVED => 'Đã xác nhận',	
        	self::STATUS_REGISTERING => 'Đang tuyển sinh',
        	self::STATUS_END_REGISTERING => 'Lớp đã đóng (dừng tuyển sinh)',
        	self::STATUS_ACTIVATED => 'Đã khai giảng'
        );
    }
    
	/**
	 * Get status of PreregisterCourse
	 */
	public function getStatus()
	{
		$statusOptions = $this->statusOptions();
		if(isset($statusOptions[$this->status])){
			return $statusOptions[$this->status];
		}else{
			return 'Chưa xác định';
		}
	}
	
	/**
	 * Calcualte final_price_per_student amount by total of student
	 */
	public function final_price_per_student()
	{
		//return $this->price_per_student*$this->total_of_session;
		return $this->price_per_student;//final price per preset course
	}
	
	/**
	 * Get discount discription of preset course if has config
	 */
	public function getDiscountPriceDescription()
	{
		$clsCourse = new ClsCourse();
		if(isset($this->price_rules) && trim($this->price_rules)!=""){
			$stepPriceRules = $clsCourse->calculateStepFinalPrice(json_decode($this->price_rules, true));
			if($stepPriceRules!==false){
				return $stepPriceRules['description'].': <b>'.number_format($stepPriceRules['bank_price']).'</b>';
			}
		}
	    return NULL;
	}
	
	/**
	 * Generate price rule values for preset course
	 */
	public function generatePriceRules()
	{
		$clsCourse = new ClsCourse();
		$priceRules = (isset($this->price_rules) && trim($this->price_rules)!="")? $this->price_rules: NULL;
		$stepPriceRules = $clsCourse->generatePriceRules(json_decode($priceRules, true));
		return $stepPriceRules;
	}
	
	/**
	 * Get course of preregister
	 */
	public function displayActualCourse($label=NULL)
	{
		$course = Course::model()->findByPk($this->course_id);
		if(isset($course->id)){
			$title = ($label)? $label: $course->title;
			$courseLink = '<a href="/admin/session?course_id='.$course->id.'">'.$title.'</a>';
			return $courseLink;
		}
		return NULL;
	}
	
	/**
	 * Get assigned Teacher of presetCourse
	 */
	public function getTeacher($teacherViewLink=null)
	{
		$teacher = User::model()->findByPk($this->teacher_id);
		if(isset($teacher->id)){
			if($teacherViewLink!=null){
				$link = '<a href="'.$teacherViewLink.'/'.$teacher->id.'">'.$teacher->fullName().'</a>';
				return $link;
			}
			return $teacher->fullName();
		}
        return NULL;
	}
	
	/**
	 * Get created user of preset course
	 */
	public function getCreatedUser()
	{
		if($this->created_user_id){
			$createdUser = User::model()->findByPk($this->created_user_id);
			if(isset($createdUser->id)){
				return $createdUser->fullName();
			}
		}
		return NULL;
	}
	
	/**
	 * Count total student register preset course
	 */
	public function countRegisteredStudents($paymentStatus=false)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('preset_course_id', $this->id);
		$criteria->compare('deleted_flag', 0);//Deleted flag = 0
		if($paymentStatus!==false){
			$criteria->compare('payment_status', $paymentStatus);//Payment status
		}
		$count = PreregisterCourse::model()->count($criteria);
		return $count;
	}
	
	/**
	 * Check full paid student in preset course
	 */
	public function checkFullPaidStudents()
	{
		$countPaidPreCourse = $this->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID);
		if($countPaidPreCourse>=$this->max_student){
			return true;
		}
		return false;
	}
	
	//Get registered student id of preset course
	public function getAssignedStudents()
	{
		$query = "SELECT student_id FROM tbl_preregister_course WHERE (preset_course_id=".$this->id.")";
		$query .= " AND (deleted_flag=0) AND (status=".PreregisterCourse::STATUS_APPROVED.")";
		$studentIds = Yii::app()->db->createCommand($query)->queryColumn();
		return array_unique($studentIds);
	}
	
	/**
	 * Get suggest schedule from Preset course
	 */
	public function getSuggestSchedules()
	{
		$sessionPerWeek = json_decode($this->session_per_week);
		$sessionSchedules = array();
		if($sessionPerWeek){
			foreach($sessionPerWeek as $key=>$val){
				$sessionSchedules['dayOfWeek'][] = $key;
				$timeArr = explode('-', $val);
				$planStart = explode(':', trim($timeArr[0]));
				$sessionSchedules['startHour'][] = $planStart[0];
				$sessionSchedules['startMin'][] = $planStart[1];
			}
		}
		return $sessionSchedules;
	}
	
	/**
	 * Update course id & preregister course after create course
	 */
	public function updatePreCoursesOfPreset($courseId)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('preset_course_id', $this->id);
		$criteria->compare('deleted_flag', 0);
		$criteria->addCondition('status='.PreregisterCourse::STATUS_APPROVED);
		$attributes = array(
			'teacher_id' => $this->teacher_id,
			'course_id' => $courseId,
		);
		PreregisterCourse::model()->updateAll($attributes, $criteria);
	}
	
	/**
	 * Get all preset course in registering
	 */
	public function getRegisteringCourses()
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition('status='.self::STATUS_REGISTERING);
		$criteria->order = 'status ASC, start_date ASC';
		$criteria->compare('deleted_flag', 0);
		return $this->findAll($criteria);//Find all
	}
	
	/**
	 * Display session perweek
	 */
	public static function displaySessionPerWeek($jsonStr, $separator=",&nbsp;&nbsp;")
	{
		$sessionPerWeek = json_decode($jsonStr);
		$registration = new ClsRegistration();
		$daysOfWeek = $registration->daysOfWeek();//Session per week
		$displayStr = "";
		if(is_array($sessionPerWeek) || is_object($sessionPerWeek)){
			foreach($sessionPerWeek as $key=>$val){
				$displayStr .= "<b>".$daysOfWeek[$key]."</b>: ".$val.$separator;
			}
			return $displayStr;
		}
		return $jsonStr;
	}
	
	/**
	 * Check student registered preset course
	 */
	public function checkRegisteredByStudent($studentId)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('preset_course_id', $this->id);
		$criteria->compare('deleted_flag', 0);
		$criteria->compare('student_id', $studentId);
		$preCourse = PreregisterCourse::model()->find($criteria);
		if(isset($preCourse->id)) return $preCourse;
		return false;
	}

}
