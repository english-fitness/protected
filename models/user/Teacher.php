<?php

/**
 * This is the model class for table "tbl_teacher".
 *
 * The followings are the available columns in table 'tbl_teacher':
 * @property integer $user_id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property string $created_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Teacher extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_teacher';
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
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('title, short_description', 'length', 'max'=>256),
			array('short_description, description, modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('user_id, title, short_description, description, created_date, modified_date', 'safe', 'on'=>'search'),
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
		);
	}
	
	//Strip tag before save teacher profile
	public function beforeSave()
	{
		//Remove html tags of some fields before save Teacher
		$stripTagFields = array('title', 'short_description');
		foreach($stripTagFields as $textField){
			$this->$textField = strip_tags($this->$textField);
		}
		$this->description = strip_tags($this->description, Common::allowHtmlTags());
		return true;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => 'Mã giáo viên',
			'title' => 'Tiêu đề',
			'short_description' => 'Mô tả ngắn',
			'description' => 'Mô tả',
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
		$criteria->compare('title',$this->title,true);
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
	 * @return Teacher the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Generate subject ability by teacher
	 * @return array abilitySubjects
	 */
	public function abilitySubjects($teacherId=NULL)
	{
		$criteria = new CDbCriteria();
		$subjectIds = array();//Ability subject ids
		$userId = ($teacherId!==NULL)? $teacherId: $this->user_id;
		if($userId>0){
			$criteria->condition = "teacher_id = $userId";
			$abilitySubjects = TeacherAbility::model()->findAll($criteria);
			if(count($abilitySubjects)>0){
				foreach($abilitySubjects as $abilitySubject){
					$subjectIds[] = $abilitySubject->subject_id;
				}
			}
		}
		return $subjectIds;
	}
	
	/**
	 * Create & Update ability subject for Teacher
	 */
	public function saveAbilitySubjects($abilitySubjects)
	{
		//Delete all ability subject of Teacher before update
		$criteria = new CDbCriteria();
		$criteria->condition = "teacher_id = $this->user_id";
		TeacherAbility::model()->deleteAll($criteria);
		//Save & Update ability for teacher
		if(count($abilitySubjects)>0)
		{
			foreach($abilitySubjects as $subjId){
				$teacherAbility = new TeacherAbility;
				$teacherAbility->teacher_id = $this->user_id;
				$teacherAbility->subject_id = $subjId;
				$teacherAbility->save();
			}
		}
	}
	
	/**
	 * Generate teacher by ability subject
	 * @return array teachers
	 */
	public function availableTeachers($subject_id)
	{
		$query = "SELECT teacher_id FROM tbl_teacher_ability WHERE subject_id=".$subject_id;
		$teacherIds = Yii::app()->db->createCommand($query)->queryColumn();
		if(count($teacherIds)==0) $teacherIds = array(0);
		$criteria = new CDbCriteria();
		$criteria->condition = "id IN(".implode(",", $teacherIds).") AND (role='".User::ROLE_TEACHER."') AND (deleted_flag=0)";
		$teachers = User::model()->findAll($criteria);
		return $teachers;
	}
	
	/**
	 * Count course of a teacher to display
	 */
	public function displayCourseLink($teacherId)
	{
		$displayLink = "Chưa gán";
		$count = Course::model()->countByAttributes(array('teacher_id'=>$teacherId));
		if($count>0){
			$displayLink = CHtml::link($count." khóa học", Yii::app()->createUrl("admin/course?teacher_id=$teacherId"));
		}
		return $displayLink;
	}
	
	/**
	 * Display ability subjects of teacher
	 */
	public function displayAbilitySubjects($teacherId=NULL)
	{
		$subjectIds = $this->abilitySubjects($teacherId);
		if(count($subjectIds)==0) return NULL;
		$criteria = new CDbCriteria();
        $criteria->condition = "id IN(".implode(",", $subjectIds).")";
        $subjects = Subject::model()->findAll($criteria);
        $displaySubjectStr = "";
        if(count($subjects>0)){
        	foreach($subjects as $subject){
        		$displaySubjectStr .= $subject->class->name.' - '.$subject->name.', ';
        	}
        	if(strlen($displaySubjectStr)>0) $displaySubjectStr = substr($displaySubjectStr, 0, -2);
        }
        return $displaySubjectStr;
	}
	
	/**
	 * Delete teacher profile by UserId
	 */
	public function deleteTeacherByUser($userId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "user_id = $userId";
		Teacher::model()->deleteAll($criteria);
	}
	
	/**
	 * Remove forever a teacher
	 */
	public function deleteForeverTeacher($userId)
	{
		//Delete session comments of teacher
		SessionComment::model()->deleteCommentsByUser($userId);
		//Delete course comments of teacher
		CourseComment::model()->deleteCommentsByUser($userId);
		//Delete course prefer teacher of teacher
		CoursePreferredTeacher::model()->deleteCoursePreferredTeacherByUser($userId);
		//Remove assigned sessions of teacher
		Session::model()->deleteAssignedSessionsByUser($userId, 'teacher');
		//Remove assigned courses of teacher
		Course::model()->deleteAssignedCoursesByUser($userId, 'teacher');
		//Delete preregister course by user
		PreregisterCourse::model()->deletePreCourseByUser($userId);
		//Delete notifications of teacher
		Notification::model()->deleteNotificationsByUser($userId);
		//Delete messages of student
		Message::model()->deleteSentMessagesByUser($userId);
		//Delete Facebook account profile of teacher
		UserFacebook::model()->deleteFacebookByUser($userId);
		//Delete Google account profile of teacher
		UserGoogle::model()->deleteGoogleByUser($userId);
		//Delete Hocmai account profile of teacher
		UserHocmai::model()->deleteHocmaiByUser($userId);
		//Delete subjects ability of teacher
		TeacherAbility::model()->deleteAbilitySubjectsByUser($userId);
		//Delete student profile of teacher
		Teacher::model()->deleteTeacherByUser($userId);
		//Delete user after delete all profiles
		$teacherUser = User::model()->findByPk($userId);
		$teacherUser->delete();//Delete last info of User
	}
}
