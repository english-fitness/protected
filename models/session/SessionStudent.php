<?php

/**
 * This is the model class for table "tbl_session_student".
 *
 * The followings are the available columns in table 'tbl_session_student':
 * @property integer $id
 * @property integer $student_id
 * @property integer $session_id
 * @property string $attended_time
 * @property string $left_time
 */
class SessionStudent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_session_student';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, session_id', 'required'),
			array('student_id, session_id', 'numerical', 'integerOnly'=>true),
			array('left_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, student_id, session_id, attended_time, left_time', 'safe', 'on'=>'search'),
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

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'student_id' => 'Student',
			'session_id' => 'Session',
			'attended_time' => 'Attended Time',
			'left_time' => 'Left Time',
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
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('session_id',$this->session_id);
		$criteria->compare('attended_time',$this->attended_time,true);
		$criteria->compare('left_time',$this->left_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    /**
     * check Student Permission
     */
    public function checkPermission($userId,$session)
    {
        $sessionStudent = SessionStudent::model()->find(array("condition"=>"student_id = $userId and session_id ='$session'"));
        if(isset($sessionStudent->session_id))
            return Session::model()->find(array("condition"=>"id = '$session' and deleted_flag=0"));
        return false;
    }
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SessionStudent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Display enter time of students in session
	 */
	public function getAttendedTimeArr($sessionId, $emptyTime='--:--')
	{
		$sessionStudents = SessionStudent::model()->findAll(array('condition'=>'session_id='.$sessionId));
		$attendedTimeArr = array();//Attended time array
		if(count($sessionStudents)>0){
			foreach($sessionStudents as $sessionStudent){
				$attendedTime = $emptyTime;//Attended time
				if($sessionStudent->attended_time){
					$attendedTime = date('d/m/Y, H:i', strtotime($sessionStudent->attended_time));
				}
				$attendedTimeArr[$sessionStudent->student_id] = $attendedTime;
			}
		}
		return $attendedTimeArr;
	}
}
