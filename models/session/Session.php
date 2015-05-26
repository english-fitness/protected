<?php

/**
 * This is the model class for table "tbl_session".
 *
 * The followings are the available columns in table 'tbl_session':
 * @property integer $id
 * @property integer $course_id
 * @property integer $teacher_id
 * @property string $subject
 * @property string $content
 * @property string $plan_start
 * @property integer $plan_duration
 * @property string $actual_start
 * @property string $actual_end
 * @property integer $status
 * @property string $created_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property Course $course
 * @property SessionComment[] $sessionComments
 */
class Session extends CActiveRecord
{
	const DEFAULT_DURATION = 30;//Default plan duration
	const DEFAULT_PRESET_DURATION = 30;//Default preset plan duration
	const STATUS_PENDING = 0;//Pending status
	const STATUS_APPROVED = 1;//Approved status
	const STATUS_WORKING = 2;//On Working status
	const STATUS_ENDED = 3;//Ended status
	const STATUS_CANCELED = 4;//Canceled status
	//Const for type of session
    const TYPE_SESSION_TESTING = 0;//Type testing session
    const TYPE_SESSION_NORMAL = 1;//Type normal session
    const TYPE_SESSION_TIMER = 2;//Type normal timer
    const TYPE_SESSION_TRAINING = 3;//Type normal training
    const TYPE_SESSION_PRESET = 4;//Type normal preset

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_session';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('course_id, subject, plan_start', 'required'),
			array('course_id, teacher_id, plan_duration, status, type, payment_type, payment_status, final_price, total_of_student, record', 'numerical', 'integerOnly'=>true),
			array('subject', 'length', 'max'=>256),
			array('content, whiteboard, actual_start, actual_end, actual_duration, modified_date, payment_status, final_price, total_of_student, deleted_flag, teacher_entered_time, status_note,created_user_id,modified_user_id', 'safe'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, course_id, teacher_id, subject, whiteboard, content, type, payment_type, plan_start, plan_duration, actual_start, actual_end, actual_duration, status, status_note, created_date, modified_date,created_user_id,modified_user_id', 'safe', 'on'=>'search'),
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
			'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
			'sessionComments' => array(self::HAS_MANY, 'SessionComment', 'session_id'),
			'teacher' => array(self::BELONGS_TO, 'User', 'teacher_id'),
		);
	}

	//Before save session
	public function beforeSave()
	{
		//Remove html tags of some fields before save Course
		$this->subject = strip_tags($this->subject);
		$this->content = strip_tags($this->content);
		return true;
	}
	
	//After save session
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete session
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
			'id' => 'Mã buổi học',
			'course_id' => 'Khóa học',
			'teacher_id' => 'Giáo viên',
			'subject' => 'Chủ đề',
			'content' => 'Nội dung',
			'total_of_student' => 'Kiểu lớp',
			'whiteboard' => 'Mã lớp học ảo',
			'plan_start' => 'Tgian bắt đầu',
			'plan_duration' => 'Thời lượng',
			'actual_start' => 'Tgian bắt đầu thực tế',
			'actual_end' => 'Tgian kết thúc thực tế',
			'actual_duration' => 'Thời lượng thực tế',
			'payment_type' => 'Kiểu thu phí',
			'payment_status' => 'Trạng thái thanh toán',
			'status' => 'Trạng thái',
			'status_note' => 'Ghi chú trạng thái',
			'final_price' => 'Giá cuối cùng',
			'type' => 'Kiểu buổi học',
			'teacher_entered_time'=>'Giờ GV vào lớp',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
			'created_user_id' => 'Người tạo',
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
	public function search($status=null, $order=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('course_id',$this->course_id);
		$criteria->compare('teacher_id',$this->teacher_id);
		$criteria->compare('subject',$this->subject,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('plan_start',$this->plan_start,true);
		$criteria->compare('plan_duration',$this->plan_duration);
		$criteria->compare('actual_start',$this->actual_start,true);
		$criteria->compare('actual_end',$this->actual_end,true);
		$cmpStatus = ($status!==null)? $status: $this->status;
		$criteria->compare('status',$cmpStatus, true);
		$criteria->compare('type',$this->type);
		$criteria->compare('total_of_student',$this->total_of_student);
		$criteria->compare('deleted_flag',$this->deleted_flag);
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
	 * @return Session the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Session label statuses
	 */
	public static function statusOptions()
	{
		return array(
			self::STATUS_PENDING => 'Đang chờ',
			self::STATUS_APPROVED => 'Đã xác nhận',
			self::STATUS_WORKING => 'Đang diễn ra',
			self::STATUS_ENDED => 'Đã kết thúc',
			self::STATUS_CANCELED => 'Đã hoãn/hủy',
		);
	}

	/**
	 * Course type options
	 */
	public function typeOptions()
	{
		return array(
			self::TYPE_SESSION_TESTING => 'Buổi học test',
			self::TYPE_SESSION_NORMAL => 'Buổi học thường',
			self::TYPE_SESSION_TIMER => 'Buổi học đo thời gian',
			self::TYPE_SESSION_TRAINING => 'Buổi học thử',
			self::TYPE_SESSION_PRESET => 'Buổi học tạo trước',	
		);
	}

	/**
	 * Default duration options
	 */
	public function planDurationOptions()
	{
		return array('5'=>'5', '10'=>'10', '20' => '20', '30'=>'30', '60'=>'60', '90'=>'90', '120'=>'120', '150'=>'150', '180'=>'180');
	}

	/**
	 * Create nearset session data provider
	 */
	public function searchNearestSession($limitDays=7)
	{
	    $criteria= $this->search()->criteria;
	    //Next 7 days from current date time
	    $planTo = date('Y-m-d H:i:s', time('now')+$limitDays*86400);
	    $criteria->compare('plan_start',"<=$planTo",true);
	    $criteria->compare('deleted_flag', 0);//Not deleted
	    $criteria->compare('status',"<>".self::STATUS_ENDED,true);//Status <> ended status
	    //30 minutes ago from current time
		$planFrom = date('Y-m-d H:i:s', time('now')-30*60);
		$criteria->compare('DATE_ADD(plan_start,INTERVAL plan_duration MINUTE)',">=$planFrom",true);
		$criteria->compare('(SELECT status FROM tbl_course WHERE id=course_id)',"<>".Course::STATUS_PENDING,true);
		$criteria->order = "plan_start asc";//Order by plan start asc

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}

	/**
	 * Create search active session data provider
	 */
	public function searchActiveSession()
	{
	    $criteria = $criteria= $this->search(null, "actual_start desc")->criteria;
		$currentTime = date('Y-m-d H:i:s');//1 day ago from current time
		$criteria->addCondition("actual_start IS NOT NULL AND actual_start<='".$currentTime."'");
		$criteria->addCondition("status=".self::STATUS_WORKING);
		$criteria->compare('deleted_flag', 0);//Not deleted
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}
	
	public function searchRecordedSession()
	{
		$criteria = $this->search(null, "plan_start desc")->criteria;
		$criteria->addCondition("record = 1");
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}

	/**
	 * Get assigned Teacher of Course
	 */
	public function getTeacher($teacherViewLink=null, $displayEnterTime=false)
	{
		$teacher = User::model()->findByPk($this->teacher_id);
		$titleEnterTime = "";//Display enter time as title
		if($displayEnterTime && $this->teacher_entered_time){
			$titleEnterTime = 'Giờ vào lớp: '.date('d/m/Y, H:i', strtotime($this->teacher_entered_time)).", ";
		}
		if(isset($teacher->id)){
			$titleEnterTime .= 'ĐT: '.$teacher->phone.'"';
			if($teacherViewLink!=null){
				$link = '<a href="'.$teacherViewLink.'/'.$teacher->id.'" title="'.$titleEnterTime.'">'.$teacher->fullName().'</a>';
				return $link;
			}
			return $teacher->fullName();
		}
		return NULL;
	}

	/**
	 * Get status of Session
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

	/**
	 * Display actual time (start, end) of session
	 */
	public function displayActualTime($showActualTime=false, $isActive=false)
	{
		if($this->actual_start && $this->actual_end && $showActualTime){
			$actualTime = date("H:i", strtotime($this->actual_start))." - ".date("H:i", strtotime($this->actual_end));
		}elseif($this->actual_start && $showActualTime && $isActive){
			$actualTime = date("H:i", strtotime($this->actual_start))." - --:--";
		}else{
			$actualTime = date("H:i", strtotime($this->plan_start))." - ".date("H:i", strtotime($this->plan_start)+$this->plan_duration*60);
		}
		return $actualTime;
	}

	/**
	 * Check & display enter session whiteboard
	 */
	public function checkDisplayBoard($beforeMinute=30)
	{
		$planStartTime = strtotime($this->plan_start);//Plan start time
		$planEndTime = $planStartTime + $this->plan_duration*60;//Plan end time
		$enterStartTime = $planStartTime - $beforeMinute*60;//Display enter button before 30min
		if(trim($this->whiteboard)!="" && $planEndTime>=(time('now')-3600) && $enterStartTime<=time('now')
			&& ($this->status==self::STATUS_APPROVED || $this->status==self::STATUS_WORKING))
		{
			return true;
		}
		return false;
	}

	/**
	 * Display remain time of future session
	 */
	public function displayRemainTime()
	{
		$now = new DateTime();
		$planStart = new DateTime($this->plan_start);
		$strDisplayTime = "";//Str display time
		if($this->plan_start > date('Y-m-d H:i:s')){
			if (Yii::app()->language == 'vi'){
				$interval = $planStart->diff($now);
				$year = $interval->format('%Y');//number of Year
				$month = $interval->format('%m');//number of Month
				$day = $interval->format('%d');//number of Date
				$hour = $interval->format('%h');//number of Hour
				if($year>0) $strDisplayTime .= $year.' năm, ';
				if($month>0) $strDisplayTime .= $month.' tháng, ';
				if($day>0) $strDisplayTime .= $day.' ngày, ';
				if($hour>0) $strDisplayTime .= $hour.' giờ, ';
				$strDisplayTime .= $interval->format('%i phút');//number of Min
				return "Còn ".$strDisplayTime;
			} else {
				$interval = $planStart->diff($now);
				$year = $interval->format('%Y');//number of Year
				$month = $interval->format('%m');//number of Month
				$day = $interval->format('%d');//number of Date
				$hour = $interval->format('%h');//number of Hour
				$minutes = $interval->format('%i');
				if($year>0) {
					if ($year == 1)
						$strDisplayTime .= $year.' year, ';
					else
						$strDisplayTime .= $year.' years, ';
				}
				if($month>0) {
					if ($month == 1)
						$strDisplayTime .= $month.' month, ';
					else
						$strDisplayTime .= $month.' months, ';
				}
				if($day>0) {
					if ($day == 1)
						$strDisplayTime .= $day.' day, ';
					else
						$strDisplayTime .= $day.' days, ';
				}
				if($hour>0) {
					if ($hour == 1)
						$strDisplayTime .= $hour.' hour, ';
					else
						$strDisplayTime .= $hour.' hours, ';
				}
				if ($minutes <= 1)
					$strDisplayTime .= $minutes.' minute ';
				else
					$strDisplayTime .= $minutes.' minutes ';
				return $strDisplayTime." until class begins";
			}
		}
		return $strDisplayTime;
	}

	/**
	 * Assign student to session when create new single Session of Course
	 */
	public function assignStudentsToSession($assignedStudentIds=array())
	{
		if(count($assignedStudentIds)>0){
			foreach($assignedStudentIds as $studentId){
				$sessionStudent = new SessionStudent;
				$sessionStudent->attributes = array('student_id'=>$studentId, 'session_id'=>$this->id);
				$sessionStudent->save();
			}
		}
	}

	/**
	 * UnAssign student from Course & Session of Course
	 */
	public function unassignStudents($studentIds=array())
	{
		if(count($studentIds)>0){
			$whereStudentIds = '('.implode(',', $studentIds).')';
			$sessionCondition = "(session_id = $this->id) AND (student_id IN $whereStudentIds)";
			//Unassign student from session
			SessionStudent::model()->deleteAll(array("condition"=>$sessionCondition));
		}
	}

	/**
	 * Save schedule session to db
	 */
	public function saveSessionSchedules($values)
	{
		$sessionSchedules = ClsCourse::generateSchedules($values);
		$sessionIndex = 1;//Init session index subject
		foreach($sessionSchedules as $key=>$sessionValues){
			$session = new Session;
			$session->attributes=$sessionValues;
			if(trim($sessionValues['subject'])==""){
				$session->subject = 'Session '.$sessionIndex;
			}
			$session->save();
			$sessionIndex++;
		}
	}
	/**
	 * Delete all Assigned Session Students
	 */
	public function deleteAssignedStudents()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "session_id = $this->id";
		SessionStudent::model()->deleteAll($criteria);//Delete assigned session students
		SessionComment::model()->deleteAll($criteria);//Delete session comments
	}

	/**
	 * Get assigned students of Course
	 */
	public function assignedStudents()
	{
		$query = "SELECT student_id FROM tbl_session_student WHERE session_id=".$this->id;
		$studentIds = Yii::app()->db->createCommand($query)->queryColumn();
		return $studentIds;
	}

	public function getAttendedUserIds()
	{
		$studentIds = $this->assignedStudents();
		if(!empty($this->teacher_id)) {
			$userIds = array($this->teacher_id);
			return array_merge($userIds, $studentIds);
		} else {
			return $studentIds;
		}
	}

 	/**
 	 * Generate assigned student as array key=>name(email)
	 */
	public function getAssignedStudentsArrs($studentViewLink=null, $displayPhone=false)
	{
		$assignedStudents = $this->assignedStudents();
		$sessionStudentArrs = array();//Generate course student
		if(count($assignedStudents)>0){
        	$criteria = new CDbCriteria();
			$criteria->condition = "id IN(".implode(",", $assignedStudents).")";
			$students = User::model()->findAll($criteria);
			foreach($students as $student){
				$fullname = $student->lastname.' '.$student->firstname;
				$displayPhoneStr = ($displayPhone)? '('.$student->phone.')': '';
				if($studentViewLink!=null){
					$sessionStudentArrs[$student->id] = '<a href="'.$studentViewLink.'/'.$student->id.'" title="Điện thoại: '.$student->phone.'">'.$fullname.'</a> '.$displayPhoneStr;
				}else{
					$sessionStudentArrs[$student->id] = $fullname.' '.$displayPhoneStr;
				}
			}
		}
		return $sessionStudentArrs;
	}

	/**
     * Get ended sessions of Student
     * @param $userId, $type="student" or "teacher"
     */
    public function getEndedStudentSessions($userId, $type="student")
    {
        $criteria = new CDbCriteria;
        $criteria->select = 't.*'; $condition = "";//Select fields & condition
        if($type=="student"){
	        $criteria->join = "INNER JOIN tbl_session_student ON t.id= tbl_session_student.session_id";
	        $condition = "(student_id = $userId) AND ";//Student id condition
        }elseif($type=="teacher"){
        	$condition = "(teacher_id = $userId) AND ";//Teacher id condition
        }
        $criteria->condition = $condition."(status=".self::STATUS_ENDED.") AND deleted_flag=0";
        $criteria->order = "plan_start DESC";
        $count = Session::model()->count($criteria);
	    $pages = new CPagination($count);
	    // results per page
	    $pages->pageSize=10;
	    $pages->applyLimit($criteria);
	    $sessions = Session::model()->findAll($criteria);
	    return array(
	    	'sessions' => $sessions,
	        'pages' => $pages,
	    );
    }

	/**
	 * Delete all assigned sessions by UserId
	 */
	public function deleteAssignedSessionsByUser($userId, $userType='student')
	{
		$criteria = new CDbCriteria();
		if($userType=='student'){
			$criteria->condition = "student_id = $userId";
			SessionStudent::model()->deleteAll($criteria);
		}elseif($userType=='teacher'){
			$criteria->condition = "(teacher_id = $userId)";
			Session::model()->updateAll(array('teacher_id'=>NULL), $criteria);
		}
	}

	/**
	 * Display attended time of students in session
	 */
	public function getAttendedTimeOfStudents($studentViewLink=null, $displayPhone=false)
	{
		$assignedStudentsArrs = $this->getAssignedStudentsArrs($studentViewLink, $displayPhone);
		$attendedTimeOfStudents = array();//Assigned student html
		if(count($assignedStudentsArrs)>0){
			$attendedTimeArr = SessionStudent::model()->getAttendedTimeArr($this->id, '');
			foreach($assignedStudentsArrs as $key=>$value){
				if($attendedTimeArr[$key]==''){
					$attendedTimeOfStudents[$key] = $value. ' <span class="clrRed">(Giờ vào lớp: --:--)</span>';
				}else{
					$attendedTimeOfStudents[$key] = $value. ' (Giờ vào lớp: '.$attendedTimeArr[$key].')';
				}
			}
		}
		return $attendedTimeOfStudents;
	}
	
	/**
	 * Create new pending session after course
	 */
	public function addSessionEndOfCourse($planStart=NULL)
	{
		$course = Course::model()->findByPk($this->course_id);
		$lastPlanStart = $course->getFirstDateInList("DESC", 'Y-m-d H:i:s');
		$sessionPlanStart = ($planStart)? $planStart: date('Y-m-d H:i:s', strtotime($lastPlanStart)+7*86400);
		$newSession = new Session();//Create new session
		$newSession->attributes = array(
			'course_id' => $this->course_id,
			'teacher_id' => $this->teacher_id,
			'subject' => $this->subject.' (buổi học bù)',
			'content' => $this->content,
			'type' => $this->type,
			'total_of_student' => $this->total_of_student,
			'plan_start' => $sessionPlanStart,
			'status' => Session::STATUS_PENDING,
		);
		if($newSession->save()){
			$assignedStudentIds = $course->assignedStudents();
            //Assign course's students to session
            $newSession->assignStudentsToSession($assignedStudentIds);
            return $newSession;
		}
		return NULL;
	}

	public function getRecordInfo()
	{
		$criteria=new CDbCriteria();
		$criteria->condition='session_id = '.$this->id;
		
		$record_file = SessionRecord::model()->findAll($criteria);
		
		if ($record_file)
		{
			$html = "";
			foreach ($record_file as $file)
				$html .= $file->record_file. '<br><a href="'. '/admin/session/getRecordFile?id='. $file->id .'">Tải xuống</a>&nbsp&nbsp&nbsp' . 
						'<a href="'. '/admin/session/removeRecordFile?id='. $file->id .'" onclick="return confirm(\'Xóa file ghi âm của buổi học này?\')">Xóa  </a><br>';
		}
		else
		{
			return "Chưa có";
		}
		
		return $html;
	}
}
