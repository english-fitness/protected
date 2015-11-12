<?php

class SessionNote extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_session_note';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('using_platform, session_id', 'required'),
			array('using_platform, session_id', 'numerical', 'integerOnly'=>true),
			array('note', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('session_id, using_platform', 'safe', 'on'=>'search'),
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
			'session' => array(self::BELONGS_TO, 'Session', 'session_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'session_id' => 'Buổi học',
			'using_platform'=>'Sử dụng hệ thống',
			'note' => 'Ghi chú',
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

		$criteria->compare('session_id',$this->session_id);
		$criteria->compare('using_platform',$this->using_platform);

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
	 * @return SessionNote the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function countSessionByCourse($courseId){
		$query = "SELECT count(*) FROM tbl_session " .
				 "WHERE course_id = :course_id";
				 
		return Yii::app()->db->createCommand($query)->bindValue(':course_id', $courseId, PDO::PARAM_INT)->queryScalar();
	}
	
	public static function countCompletedSessionByCourse($courseId, $usingPlatform = null){
		if ($usingPlatform !== null){
			$query = "SELECT count(*) FROM tbl_session_note JOIN tbl_session " .
					 "ON tbl_session_note.session_id = tbl_session.id " .
					 "WHERE tbl_session.course_id = :course_id " .
					 // "AND status = " . Session::STATUS_ENDED . " " .
					 "AND tbl_session_note.using_platform = :using_platform";
		} else {
			$query = "SELECT count(*) FROM tbl_session " .
					 "WHERE course_id = :course_id " .
					 "AND status = " . Session::STATUS_ENDED;
		}

		$queryCommand = Yii::app()->db->createCommand($query);
		$queryCommand->bindValue(':course_id', $courseId, PDO::PARAM_INT);
		if ($usingPlatform !== null){
			$queryCommand->bindValue(':using_platform', $usingPlatform, PDO::PARAM_INT);
		}

		return $queryCommand->queryScalar();
	}
	
	public static function countCancelledSessionByCourse($courseId){
		$query = "SELECT count(*) FROM tbl_session " .
				 "WHERE course_id = :course_id ".
				 "AND status = " . Session::STATUS_CANCELED;
				 
		return Yii::app()->db->createCommand($query)->bindValue(':course_id', $courseId, PDO::PARAM_INT)->queryScalar();
	}
    
	public static function getSessionNoteByCourse($courseId, $usingPlatform = null, $ended=false, $paid=false){
		if ($usingPlatform !== null){
			$joinType = "JOIN";
		} else {
			$joinType = "LEFT JOIN";
		}

		$condition = "course_id = :course_id";
		$queryParams = array(":course_id"=>$courseId);

		$otherFilter = '';

		if ($ended){
			$otherFilter .= " AND status = " . Session::STATUS_ENDED;
		}

		if ($paid){
			$otherFilter .= " AND teacher_paid = 1";
		}

		$condition .= $otherFilter;

		$sessionNoteCriteria = array(
			"joinType"=>$joinType,
		);
		if ($usingPlatform !== null){
			$sessionNoteCriteria["on"] = "using_platform = :using_platform";
			$sessionNoteCriteria["params"] = array(":using_platform"=>$usingPlatform);
		}

		return new CActiveDataProvider('Session', array(
			"criteria"=>array(
				"condition"=>$condition,
				"params"=>$queryParams,
				"order"=>"plan_start ASC",
				"with"=>array(
					"note"=>$sessionNoteCriteria,
					"teacher"=>array(
						'select'=>array('firstname', 'lastname'),
					)
				),
			),
			'pagination'=>array('pageVar'=>'page', 'pageSize'=>20),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}
}
