<?php

/**
 * This is the model class for table "tbl_subject".
 *
 * The followings are the available columns in table 'tbl_subject':
 * @property integer $id
 * @property integer $class_id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Course[] $courses
 * @property Class $class
 * @property User[] $tblUsers
 */
class Subject extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_subject';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('class_id, name', 'required'),
			array('class_id, allow_to_teach', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, class_id, name, allow_to_teach', 'safe', 'on'=>'search'),
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
			'courses' => array(self::HAS_MANY, 'Course', 'subject_id'),
			'class' => array(self::BELONGS_TO, 'Classes', 'class_id'),
			'tblUsers' => array(self::MANY_MANY, 'User', 'tbl_teacher_ability(subject_id, user_id)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Mã môn học',
			'class_id' => 'Tên lớp',
			'name' => 'Tên môn học',
			'allow_to_teach' => 'Có giáo viên dạy?',
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
		$criteria->compare('class_id',$this->class_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('allow_to_teach',$this->allow_to_teach);
		$criteria->order = 'name ASC';

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
	 * @return Subject the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Generate all class subjects
	 */
	public function generateSubjects()
	{
		$allClasses = Classes::model()->getAll();
		$classSubjects = array();//Generate subjects
		if(count($allClasses)>0){
			foreach($allClasses as $class){				
				$classSubjects[$class->id]['name'] = $class->name;
				$classSubjects[$class->id]['subject'] = array();
				$criteria = new CDbCriteria();
				$criteria->condition = "class_id = $class->id";
				$subjects_by_class = Subject::model()->findAll($criteria);
				if(count($subjects_by_class)>0){
					foreach($subjects_by_class as $subject){
						$classSubjects[$class->id]['subject'][] = array(
							'id' => $subject->id,
							'name' => $subject->name,
						);
					}
				}
			}
		}
		return $classSubjects;
	}
	
	/**
	 * Display class & subject
	 */
	public function displayClassSubject($subjectId, $type='')
	{
		$subject = Subject::model()->findByPk($subjectId);
		if(!isset($subject->id)){
			return NULL;
		}
		if($type=='class') return $subject->class;//Return class
		if($type=='subject') return $subject;//Return subject
		//Return class - subject name
		return $subject->class->name.' - '.$subject->name;
	}
	
	/**
	 * Generate subject to filter
	 */
	public function generateSubjectFilters($sort='ASC', $limitSubjectIds=false)
	{
		$criteria=new CDbCriteria;
		$criteria->order = "(SELECT name FROM tbl_class WHERE id=class_id) $sort, name $sort";
		$subjects = Subject::model()->findAll($criteria);
		$filterSubjects = array();
		if(count($subjects)>0){
			foreach($subjects as $subject){
				if($limitSubjectIds!==false && is_array($limitSubjectIds)){
					if(in_array($subject->id, $limitSubjectIds)){
						$filterSubjects[$subject->id] = $subject->class->name.' - '.$subject->name;
					}
				}else{
					$filterSubjects[$subject->id] = $subject->class->name.' - '.$subject->name;
				}
			}
		}
		return $filterSubjects;
	}
	
	/**
     * Count approved topic in subject
     */
    public function countApprovedParentTopic()
    {
    	$criteria = new CDbCriteria;
    	$criteria->compare('parent_id', 0);//Parent id
    	$criteria->compare('status', QuizTopic::STATUS_APPROVED);
    	$criteria->compare('deleted_flag', 0);
    	$criteria->compare('subject_id', $this->id);
    	$count = QuizTopic::model()->count($criteria);
    	return $count;//Count approve topic
    }
    
    /**
     * Get available subject by class with at least 1 approved parent topic
     */
    public function getAvailableSubjectsToQuiz($classId="")
    {
    	$criteria = new CDbCriteria;
    	$criteria->compare('class_id', $classId);//Class id
    	$criteria->order = "name ASC";
    	$criteria->addCondition('id IN (SELECT subject_id FROM tbl_quiz_topic WHERE status='.QuizTopic::STATUS_APPROVED.')');
    	return $this->findAll($criteria);
    }

}
