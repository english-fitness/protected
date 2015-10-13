<?php


class TeacherTimeslots extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_teacher_timeslots';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('teacher_id', 'required'),
			array('teacher_id', 'numerical', 'integerOnly'=>true),
			array('week_start', 'value'=>date('Y-m-d')),
			array('timeslots', 'match', 'pattern'=>'^([0-9]*\s*,*\s*)+$'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('teacher_id', 'safe', 'on'=>'search'),
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
			'teacher' => array(self::BELONGS_TO, 'User', 'teacher_id'),
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

		$criteria->compare('teacher_id',$this->teacher_id);
		
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
	
	//quick insert function
	public static function saveSchedule($teacherId, $weekStart, $timeslots){
		$validTimeslot = preg_match('/^([0-9]*\s*){1}$|^([0-9]*\s*,*\s*)+$/', $timeslots);
		if ($validTimeslot === 0 || $validTimeslot === false){
			return false;
		}
		$query = "INSERT INTO tbl_teacher_timeslots (teacher_id, week_start, timeslots) " . 
				 "VALUES(" . $teacherId . ", '" . $weekStart . "', '" . $timeslots . "')" . " " .
				 "ON DUPLICATE KEY UPDATE timeslots = VALUES(timeslots)";
		
		try {
			Yii::app()->db->createCommand($query)->query();
			$success = true;
		} catch (Exception $ex){
			$success = false;
		}
		
		return $success;
	}
	
	//quick get functions
	public static function getSchedule($teacherId, $weekStart){
		$query = "SELECT timeslots FROM tbl_teacher_timeslots " .
				 "WHERE teacher_id = " . $teacherId . " " .
				 "AND week_start = '" . $weekStart . "'";
		$result = Yii::app()->db->createCommand($query)->queryRow();
		
		return $result['timeslots'];
	}
	
	public static function getMultipleSchedules($teachers, $start, $end=null){
		if ($end != null){
			$query = "SELECT teacher_id AS teacher, week_start AS weekStart, timeslots FROM tbl_teacher_timeslots " .
					 "WHERE teacher_id IN (" . implode(', ', $teachers) . ") " .
					 "AND week_start BETWEEN '" . $start . "' AND '" . $end . "'";
		}else {
			$query = "SELECT teacher_id AS teacher, week_start AS weekStart, timeslots FROM tbl_teacher_timeslots " .
					 "WHERE teacher_id IN (" . implode(', ', $teachers) . ") " .
					 "AND week_start = '" . $start . "'";
		}
		
		return Yii::app()->db->createCommand($query)->queryAll();
	}
}
