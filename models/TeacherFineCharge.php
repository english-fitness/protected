<?php

class TeacherFineCharge extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('id, teacher_id, points, created_date', 'safe'),
			array('teacher_id, points', 'required'),
			array('created_date', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, teacher_id, created_date', 'safe', 'on'=>'search'),
			array('created_date', 'default', 'value'=>date('Y-m-d'), 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		return $modelRules;//Return model rules
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
		return 'tbl_teacher_fine_charge';
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'teacher_id' => 'Giáo viên',
			'points' => 'Số điểm phạt',
			'created_date' => 'Ngày tạo'
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
	public function search($teacherId=null, $order=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$teacher_id = ($teacherId != null) ? $teacherId : $this->teacher_id;
		$criteria->compare('teacher_id',$teacher_id);
		$criteria->compare('points',$this->points);
		$criteria->compare('created_date',$this->created_date);
		
		if ($order != null){
			$criteria->order = $order;
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page', 'pageSize'=>20),
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
	
	public static function getNumberOfSessionDeducted($points){
		$points_to_be_charged = self::getNumberOfPointsToCharge($points);
		
		$sessionDeduct = array(
			10=>1,
			20=>2,
			25=>3,
			30=>4,
			35=>5,
			40=>6,
			45=>7,
			50=>8,
		);
		if (isset($sessionDeduct[$points_to_be_charged])){
			return $sessionDeduct[$points_to_be_charged];
		} else {
			return null;
		}
	}
	
	public static function chargeFine($teacher, $points){
		$query = "SELECT tbl_teacher_fine.* FROM tbl_teacher_fine " .
				 "JOIN tbl_session ON tbl_session.id = tbl_teacher_fine.session_id " .
				 "WHERE tbl_teacher_fine.teacher_id = " . $teacher . " " .
				 "AND points_to_be_fined > 0 " .
				 "ORDER BY tbl_session.plan_start ASC";
		$unfinedRecords = TeacherFine::model()->findAllBySql($query);
		
		$remainingPoints = $points;
		
		$transaction = Yii::app()->db->beginTransaction();
		try{
			foreach($unfinedRecords as $record){
				if($remainingPoints != 0){
					$pointsToBeFined = $record->points_to_be_fined;
					if ($remainingPoints >= $pointsToBeFined){
						$remainingPoints -= $pointsToBeFined;
						$record->points_to_be_fined = 0;
					}else {
						$record->points_to_be_fined -= $remainingPoints;
						$remainingPoints = 0;
					}
					$record->save();
				}
			}
			$teacherFineCharge = new TeacherFineCharge();
			$teacherFineCharge->teacher_id = $teacher;
			$teacherFineCharge->points = $points;
			$teacherFineCharge->save();
			
			$transaction->commit();
		} catch(Exception $e){
			$transaction->rollback();
		}
		
		//send fine charge notice?
	}
	
	public static function getNumberOfPointsToCharge($points){
		$pointsArray = array(10, 20, 25, 30, 35, 40, 45, 50);
		
		for ($i = count($pointsArray) - 1; $i >= 0; $i--){
			if ($points >= $pointsArray[$i]){
				return $pointsArray[$i];
			}
		}
		
		return 0;
	}
	
	public static function getTeachersToBeCharged(){
		$countQuery = "SELECT count(*) FROM(".
						 "SELECT tbl_teacher_fine.teacher_id FROM tbl_teacher_fine " .
						 "JOIN tbl_session ON tbl_session.id = tbl_teacher_fine.session_id " .
						 "WHERE tbl_session.plan_start > CURRENT_DATE - INTERVAL '2' MONTH " .
						 "GROUP BY teacher_id " .
						 "HAVING sum(points_to_be_fined) >= 10".
					  ") results";
		$count = Yii::app()->db->createCommand($countQuery)->queryScalar();
		
		$query = "SELECT tbl_teacher_fine.teacher_id, sum(points_to_be_fined) AS total_points FROM tbl_teacher_fine " .
				 "JOIN tbl_session ON tbl_session.id = tbl_teacher_fine.session_id " .
				 "WHERE tbl_session.plan_start > CURRENT_DATE - INTERVAL '2' MONTH " .
				 "GROUP BY teacher_id " .
				 "HAVING total_points >= 10";
				 
		return new CSqlDataProvider($query, array(
			'totalItemCount'=>$count,
			'sort'=>array(
				'attributes'=>array(
					 'total_points',
				),
			),
			'pagination'=>array(
				'pageSize'=>10,
			),
			'keyField'=>'teacher_id',
		));
	}
	
	public static function getAllTeachersFine(){
		$countQuery = "SELECT count(*) FROM(".
						 "SELECT tbl_teacher_fine.teacher_id FROM tbl_teacher_fine " .
						 "JOIN tbl_session ON tbl_teacher_fine.session_id = tbl_session.id " .
						 "WHERE tbl_session.plan_start > CURRENT_DATE - INTERVAL '2' MONTH " .
						 "GROUP BY teacher_id" .
					  ") results";
		$count = Yii::app()->db->createCommand($countQuery)->queryScalar();
		
		$query = "SELECT tbl_teacher_fine.teacher_id, sum(points_to_be_fined) AS total_points FROM tbl_teacher_fine " .
				 "JOIN tbl_session ON tbl_teacher_fine.session_id = tbl_session.id " .
				 "WHERE tbl_session.plan_start > CURRENT_DATE - INTERVAL '2' MONTH " .
				 "GROUP BY teacher_id ";

		return new CSqlDataProvider($query, array(
			'totalItemCount'=>$count,
			'pagination'=>array(
				'pageSize'=>10,
			),
			'sort'=>array(
				'attributes'=>array(
					 'total_points',
				),
			),
			'keyField'=>'teacher_id',
		));
	}
}
