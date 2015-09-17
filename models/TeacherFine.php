<?php

class TeacherFine extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
            array('session_id, points', 'numerical', 'integerOnly'=>true),
            array('notes', 'length', 'max'=>256),
			array('teacher_id', 'safe'),
			array('teacher_id, points, session_id', 'required'),
			array('created_date', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, teacher_id, created_date', 'safe', 'on'=>'search'),
		);
		return $modelRules;//Return model rules
	}
	
	public function beforeSave()
	{
		parent::beforeSave();
		$this->notes = strip_tags($this->notes);
		
		return true;
	}
	
	//After save User
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete User
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
	public function tableName()
	{
		return 'tbl_teacher_fine';
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'session' => array(self::BELONGS_TO, 'Session', 'session_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'teacher_id' => 'Giáo viên',
			'points' => 'Số điểm phạt',
			'created_date' => 'Ngày',
			'notes' => 'Ghi chú',
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
	public function search($order='created_date desc')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$teacher_id = $this->teacher_id;
		$criteria->compare('teacher_id',$teacher_id);
		$criteria->compare('points',$this->points);
		$criteria->compare('created_date',$this->created_date);
		
		$criteria->order = $order;
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
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function getCurrentPoint($teacher){
		$query = "SELECT points_to_be_fined FROM tbl_teacher_fine " .
				 "WHERE teacher_id = " . $teacher . " " .
				 "AND points_to_be_fined > 0";
		$results = Yii::app()->db->createCommand($query)->queryColumn();

		$points = 0;
		if (!empty($results)){
			foreach ($results as $item){
				$points += $item;
			}
		}
		
		return $points;
	}
	
	public static function getPointOptions(){
		return array(0=>0,2=>2,4=>4);
	}
}
