<?php

/**
 * This is the model class for table "tbl_course".
 *
 * The followings are the available columns in table 'tbl_course':
 * @property integer $id
 * @property integer $created_user_id
 * @property integer $teacher_id
 * @property integer $subject_id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property string $created_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property Subject $subject
 * @property User $createdUser
 * @property CourseComment[] $courseComments
 * @property CoursePreferredTeacher[] $coursePreferredTeachers
 * @property User[] $tblUsers
 * @property Session[] $sessions
 */
class Course extends CActiveRecord
{
	const STATUS_PENDING = 0;//Pending status
	const STATUS_APPROVED = 1;//Approved status
	const STATUS_WORKING = 2;//On Working status
	const STATUS_ENDED = 3;//Ended status	
	//Const for type of course
    const TYPE_COURSE_TESTING = 0;//Type testing course
    const TYPE_COURSE_NORMAL = 1;//Type normal course
    const TYPE_COURSE_TIMER = 2;//Type normal timer
    const TYPE_COURSE_TRAINING = 3;//Type normal training
    const TYPE_COURSE_PRESET = 4;//Type normal preset
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_course';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('subject_id', 'required'),
			array('created_user_id, teacher_id, subject_id, status, type, payment_type, payment_status, final_price, total_of_student, modified_user_id, deleted_flag', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>256),
			array('content, modified_date, payment_type, payment_status, final_price, total_of_student, deleted_flag, teacher_form_url, student_form_url', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, created_user_id, teacher_id, subject_id, title, content, type, status, total_of_student, created_date, modified_date, modified_user_id', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
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
			'subject' => array(self::BELONGS_TO, 'Subject', 'subject_id'),
			'createdUser' => array(self::BELONGS_TO, 'User', 'created_user_id'),
			'courseComments' => array(self::HAS_MANY, 'CourseComment', 'course_id'),
			'coursePreferredTeachers' => array(self::HAS_MANY, 'CoursePreferredTeacher', 'course_id'),
			'tblUsers' => array(self::MANY_MANY, 'User', 'tbl_course_student(course_id, student_id)'),
			'sessions' => array(self::HAS_MANY, 'Session', 'course_id'),
			'teacher' => array(self::BELONGS_TO, 'User', 'teacher_id'),
		);
	}

	//Before save course
	public function beforeSave()
	{
		//Remove html tags of some fields before save Course
		$this->title = strip_tags($this->title);
		$this->content = strip_tags($this->content, Common::allowHtmlTags());
		return true;
	}
	
	//After save course
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete course
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
			'created_user_id' => 'Người tạo',
			'teacher_id' => 'Giáo viên',
			'subject_id' => 'Môn học',
			'title' => 'Chủ đề',
			'content' => 'Nội dung',
			'type' => 'Kiểu khóa học',
			'total_of_student' => 'Kiểu lớp',
			'status' => 'Trạng thái',
			'payment_status' => 'Trạng thái thanh toán',
			'payment_type' => 'Kiểu thu phí',
			'final_price' => 'Học phí khóa học (VND)',
			'number_of_sessions'=>'Số buổi',
			'student_form_url' => 'Học sinh feedback url',
			'teacher_form_url' => 'Giáo viên feedback url',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
			'modified_user_id' => 'Người sửa',
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
	public function search($order=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('teacher_id',$this->teacher_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		$criteria->compare('type',$this->type);
		$criteria->compare('total_of_student',$this->total_of_student);
		$criteria->compare('payment_status',$this->payment_status);
		$criteria->compare('final_price',$this->final_price, true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		if($order!==null) $criteria->order = $order;
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}
	
	public function searchByStudent($studentId, $order=null){
		$criteria = new CDbCriteria;
		
		$criteria->alias = "tbl_course";
		$criteria->join = "JOIN tbl_course_student ON tbl_course.id = tbl_course_student.course_id";
		$criteria->condition = "tbl_course_student.student_id = $studentId";
		
		$criteria->compare('id',$this->id);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('teacher_id',$this->teacher_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		$criteria->compare('type',$this->type);
		$criteria->compare('total_of_student',$this->total_of_student);
		$criteria->compare('payment_status',$this->payment_status);
		$criteria->compare('final_price',$this->final_price, true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		if($order!==null) $criteria->order = $order;
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
	 * @return Course the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Course label statuses
	 */
	public static function statusOptions()
	{
		return array(
			self::STATUS_PENDING => 'Đang chờ',
			self::STATUS_APPROVED => 'Đã xác nhận',
			self::STATUS_WORKING => 'Đang diễn ra',
			self::STATUS_ENDED => 'Kết thúc',
		);
	}
	
	/**
	 * Course type options
	 */
	public function typeOptions()
	{
		return array(
			self::TYPE_COURSE_TESTING => 'Khóa học test',
			self::TYPE_COURSE_NORMAL => 'Khóa học thường',
			self::TYPE_COURSE_TIMER => 'Khóa học đo thời gian',
			self::TYPE_COURSE_TRAINING => 'Khóa học thử',
			self::TYPE_COURSE_PRESET => 'Khóa học tạo trước',	
		);
	}
	/**
	 * Get status of Course
	 */
	public function getStatus()
	{
		$statusOptions = self::statusOptions();
		if(isset($statusOptions[$this->status])){
			return $statusOptions[$this->status];
		}else{
			return 'Chưa xác định';
		}
	}
	//Get payment status
	public function getPaymentStatus(){
		$paymentStatusOpts = ClsCourse::paymentStatuses();
		if(isset($paymentStatusOpts[$this->payment_status])){
			return $paymentStatusOpts[$this->payment_status];
		}
		return null;
    }
    
	/**
	 * Get assigned Teacher of Course
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
	 * Get all session of Course
	 * if inComing = true, only get future sessions
	 */
	public function getSessions($inComing=null, $teacherId=null)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "(course_id = $this->id) AND deleted_flag=0";
		if($inComing){
			$criteria->condition .= " AND (plan_start>'".date('Y-m-d H:i:s')."')";
		}
		if($teacherId!==null){
			$criteria->condition .= " AND (teacher_id=".$teacherId.")";
		}
		$criteria->order = "plan_start ASC";
		$courseSessions = Session::model()->findAll($criteria);
		return $courseSessions;
	}
	
	/**
	 * Get date of first session of Course
	 */
	public function getFirstDateInList($order='ASC', $dateFormat='d/m/Y')
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "(course_id = $this->id) AND (deleted_flag=0)";
		$criteria->order = "plan_start $order";
		$firstSession = Session::model()->find($criteria);
		if(isset($firstSession->id)){
			return date($dateFormat, strtotime($firstSession->plan_start));
		}
		return NULL;
	}
	
	/**
	 * count all session of Course
	 */
	public function countSessions($teacherId=null, $countAll=false)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "course_id = $this->id";
		if(!$countAll) $criteria->condition .= " AND deleted_flag=0";
		if($teacherId!==null){
			$criteria->condition .= " AND (teacher_id=".$teacherId.")";
		}
		$countSessions = Session::model()->count($criteria);
		return $countSessions;
	}
    /**
     * check Student Permission
     */
    public function checkStudentPermission($userId)
    {
       $courserStudent = CourseStudent::model()->find(array("condition"=>"student_id = $userId and course_id =$this->id"));
       if(isset($courserStudent->course_id))
            return true;
       return false;
    }
	/**
	 * Delete all Assigned Course Students
	 */
	public function deleteAssignedCourseStudents()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "course_id = $this->id";
		CourseStudent::model()->deleteAll($criteria);//Delete Assigned students
		CourseComment::model()->deleteAll($criteria);//Delete all course comments
		CoursePreferredTeacher::model()->deleteAll($criteria);//Delete course prefer Teachers		
	}
	
	/**
	 * Delete all Assigned Course Students
	 */
	public function deleteAssignedSessionStudents()
	{
		$criteria = new CDbCriteria();
		$query = "SELECT id FROM tbl_session WHERE course_id=".$this->id;
		$sessionIds = Yii::app()->db->createCommand($query)->queryColumn();
		if(count($sessionIds)==0) $sessionIds = array(0);
		$criteria->condition = "session_id IN (".implode(",", $sessionIds).")";
		SessionStudent::model()->deleteAll($criteria);//Delete assigned session students
		SessionComment::model()->deleteAll($criteria);//Delete session comments		
	}
	
	/**
	 * Delete all Course Sessions
	 */
	public function deleteCourseSessions()
	{
		$query = "SELECT whiteboard FROM tbl_session WHERE course_id=".$this->id." AND whiteboard<>''";
		$whiteboards = Yii::app()->db->createCommand($query)->queryColumn();
		if(count($whiteboards)>0){
			foreach($whiteboards as $whiteboard){
				try {
					Yii::app()->board->removeBoard($whiteboard);//Delete board
				}catch(Exception $e){
					//Display error message here
				}
			}
		}
		$criteria = new CDbCriteria();
		$criteria->condition = "course_id = $this->id";
		Session::model()->deleteAll($criteria);
	}
	
	/**
	 * Get assigned students of Course
	 */
	public function assignedStudents()
	{
		$query = "SELECT student_id FROM tbl_course_student WHERE course_id=".$this->id;
		$studentIds = Yii::app()->db->createCommand($query)->queryColumn();
		return $studentIds;
	}
	
 	/** 
 	 * Generate assigned student as array key=>name(email)
	 */
	public function getAssignedStudentsArrs($studentViewLink=null)
	{
		$assignedStudents = $this->assignedStudents();
		$courseStudentArrs = array();//Generate course student        
		if(count($assignedStudents)>0){
        	$criteria = new CDbCriteria();
			$criteria->condition = "id IN(".implode(",", $assignedStudents).")";
			$students = User::model()->findAll($criteria);
			foreach($students as $student){
				$fullname = $student->lastname.' '.$student->firstname;
				if($studentViewLink!=null){
					$courseStudentArrs[$student->id] = '<a href="'.$studentViewLink.'/'.$student->id.'">'.$fullname.'</a>';
				}else{
					$courseStudentArrs[$student->id] = $fullname;
				}
			}
		}
		return $courseStudentArrs;
	}
	
	/**
	 * Assign teacher for Course & Session of Course
	 */
	public function assignTeacherToCourseSession()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "(course_id = $this->id) AND (plan_start>'".date('Y-m-d H:i:s')."')";
		Session::model()->updateAll(array('teacher_id'=>$this->teacher_id), $criteria);
	}
	
	/**
	 * Assign students for Course & Session of Course
	 */
	public function assignStudentsToCourseSession($studentIds)
	{
		//Save & assign student for Course & Session
		$courseStudentSql=""; $sessionStudentSql = "";
		if(count($studentIds)>0){
			//Read all sessions of Course
			$courseSessions = $this->getSessions(true);
			foreach($studentIds as $studentId){
				//Check & update user status when create new course
				$user = User::model()->findByPk($studentId);//Get student user
				if($user->status<User::STATUS_OFFICIAL_USER && $this->type==self::TYPE_COURSE_NORMAL){
					$user->status = User::STATUS_OFFICIAL_USER;
					$user->save();//Save user status
				}elseif($user->status<User::STATUS_TRAINING_SESSION && $this->type==self::TYPE_COURSE_TRAINING){
					$user->status = User::STATUS_TRAINING_SESSION;
					$user->save();//Save user status
				}
				//Save & assign new student for Course
				$courseStudentSql .= "INSERT INTO tbl_course_student(`student_id`, `course_id`) VALUES($studentId, $this->id);\n";
				//Save & assign new student for all session in Course
				if(count($courseSessions)>0){
					foreach($courseSessions as $session){
						$sessionStudentSql .= "INSERT INTO tbl_session_student(`student_id`, `session_id`) VALUES($studentId, $session->id);\n";
					}
				}
			}
			//Save & assign new student for Course
			if($courseStudentSql!=""){
				$assignCourseStudents = Yii::app()->db->createCommand($courseStudentSql)->execute();
			}
			//Save & assign new student for all session in Course
			if($sessionStudentSql!=""){
				$assignSessionStudents = Yii::app()->db->createCommand($sessionStudentSql)->execute();
			}
		}
	}
	
	/**
	 * Display prefer Teachers for Course
	 */
	public function priorityTeachers()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "course_id = $this->id";
		$preferTeachers = CoursePreferredTeacher::model()->findAll($criteria);
		$priorityTeachers = array();//Prefer Teacher
		if(count($preferTeachers)>0){
			foreach($preferTeachers as $row){
				$priorityTeachers[$row->teacher_id] = $row->priority;
			}
		}
		return $priorityTeachers;
	}
	
	/**
	 * Generate Course's session schedules as array 
	 */
	public function generateSessionSchedules()
	{
		$sessions = $this->getSessions();
		$schedules = array();//Generate session schedule from db
		if(count($sessions)>0){
			foreach($sessions as $key=>$session){
				$schedules[$session->id] = array(
					'course_id'=>$this->id,
					'teacher_id'=>$session->teacher_id,
					'plan_start'=>$session->plan_start,
                    'plan_duration' => $session->plan_duration,
				);
			}
		}
		return $schedules;
	}
	
	/**
	 * Set status of all session to the status of Course
	 */
	public function resetStatusSessions($changeStatus=true)
	{
		$criteria = new CDbCriteria();
		//Not change or reset status of deleted/ended sessions
		$condition = "(course_id = $this->id) AND (plan_start>'".date('Y-m-d H:i:s')."')";
		$condition .= " AND (status=".Session::STATUS_PENDING." OR status=".Session::STATUS_APPROVED.")";
		$criteria->condition = $condition;
		$attributes = array(
			'type'=>$this->type,
			'payment_status'=>$this->payment_status,
			'payment_type'=>$this->payment_type,
			'total_of_student'=>$this->total_of_student,
		);
		if($changeStatus && $this->status==self::STATUS_APPROVED){
			$attributes['status'] = $this->status;
		}
		//Update deleted_flag=1 for incoming sessions when ended course
		if($changeStatus && $this->status==self::STATUS_ENDED){
			$attributes['deleted_flag'] = 1;//Deleted flag = 1
		}
		Session::model()->updateAll($attributes, $criteria);
	}
	
	/**
	 * UnAssign student from Course & Session of Course
	 */
	public function unassignStudents($studentIds=array())
	{
		if(count($studentIds)>0){
			$whereStudentIds = '('.implode(',', $studentIds).')';
			$courseCondition = "(course_id = $this->id) AND (student_id IN $whereStudentIds)";
			//Unassign Student from Course
			CourseStudent::model()->deleteAll(array("condition"=>$courseCondition));
			//Unassign students from all future sessions of Course
			$courseSessions = $this->getSessions(true);
			$sessionIds = array_keys(CHtml::listData($courseSessions, 'id', 'subject'));
			if(count($sessionIds)>0){//If course has session in future
				$whereSessionIds = '('.implode(',', $sessionIds).')';
				$sessionCondition = "(session_id IN $whereSessionIds) AND (student_id IN $whereStudentIds)";
				SessionStudent::model()->deleteAll(array("condition"=>$sessionCondition));
			}
		}
	}
	
	/**
	 * Check existed free trial course
	 */
	public function checkExistedTrialCourse()
	{
		$trialCourse = Course::model()->findByAttributes(array('type'=>0, 'deleted_flag'=>0));
		if(isset($trialCourse->id)){
			return true;
		}
		return false;
	}
	
	/**
	 * Check existed course of student
	 */
	public function checkRegisteredCourseStudent($studentId)
	{
		$count = CourseStudent::model()->countByAttributes(array('student_id'=>$studentId));
		if($count>0) return true;
		return false;
	}
	
	/**
	 * Delete all assigned Courses by UserId
	 */
	public function deleteAssignedCoursesByUser($userId, $userType='student')
	{
		$criteria = new CDbCriteria();
		if($userType=='student'){
			$criteria->condition = "student_id = $userId";
			CourseStudent::model()->deleteAll($criteria);
		}elseif($userType=='teacher'){
			$criteria->condition = "(teacher_id = $userId)";
			Course::model()->updateAll(array('teacher_id'=>NULL), $criteria);
		}
		$criteria->condition = "(created_user_id = $userId)";
		Course::model()->updateAll(array('created_user_id'=>1), $criteria);
	}
	
	/**
	 * Get assigned request course from this course 
	 */
	public function displayConnectedPreCourses()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "course_id=$this->id";
		$preCourses = PreregisterCourse::model()->findAll($criteria);
		$displayPreCourses = array();//Display pre course
		if(count($preCourses)>0){
			foreach($preCourses as $key=>$preCourse){
				$displayPreCourses[] = '<b>('.($key+1).') - </b><a href="/admin/preregisterCourse/view/id/'.$preCourse->id.'">'.Common::truncate($preCourse->title,40).'</a>';
			}
		}
		return implode('&nbsp;&nbsp;', $displayPreCourses);
	}
	
	public function changeSchedule($schedule)
	{
		$sessions = $this->getSessions(true);
		
		$schedule['numberOfSession'] = count($sessions);
		$schedule['startDate'] = date('Y-m-d', strtotime('-1 day'));
		
		$newSchedule = ClsCourse::generateSchedules($schedule);
		
		//apply new schedule
		for ($i = 0; $i < count($sessions); $i++)
		{
			$sessions[$i]->plan_start = $newSchedule[$i+1]['plan_start'];
			$sessions[$i]->plan_duration = $schedule['plan_duration'];
			$sessions[$i]->save();
		}
	}
	
	public static function findByStudent($student){
		$query = "SELECT id, title, type FROM tbl_course JOIN tbl_course_student " .
				 "ON tbl_course.id = tbl_course_student.course_id " .
				 "WHERE tbl_course_student.student_id = " . $student . " " .
				 "AND (tbl_course.status = " . Course::STATUS_WORKING . " " .
				 "OR tbl_course.status = " . Course::STATUS_APPROVED . ") " .
				 "AND deleted_flag = 0 " . " " .
				 "ORDER BY course_id DESC";
		return Course::model()->findAllBySql($query);
	}
	
	public function getNumberOfSessionsAvailable(){
		$query = "SELECT sum(number_of_sessions) FROM tbl_course_payment " . 
				 "WHERE course_id = " . $this->id;
		$additionalSessions = Yii::app()->db->createCommand($query)->queryScalar();
		
		return $this->number_of_sessions + $additionalSessions;
	}
}
