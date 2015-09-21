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
			array('using_platform', 'required'),
			array('using_platform', 'numerical', 'integerOnly'=>true),
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
				 "WHERE course_id = " . $courseId;
				 
		return Yii::app()->db->createCommand($query)->queryScalar();
	}
	
	public static function countCompletedSessionByCourse($courseId, $usingPlatform = null){
		if ($usingPlatform !== null){
			$query = "SELECT count(*) FROM tbl_session_note JOIN tbl_session " .
					 "ON tbl_session_note.session_id = tbl_session.id " .
					 "WHERE tbl_session.course_id = " . $courseId . " " .
					 "AND status = " . Session::STATUS_ENDED . " " .
					 "AND tbl_session_note.using_platform = " . $usingPlatform;
		} else {
			$query = "SELECT count(*) FROM tbl_session " .
					 "WHERE course_id = " . $courseId . " " .
					 "AND status = " . Session::STATUS_ENDED;
		}
		
		return Yii::app()->db->createCommand($query)->queryScalar();
	}
	
	public static function countCancelledSessionByCourse($courseId){
		$query = "SELECT count(*) FROM tbl_session " .
				 "WHERE course_id = " . $courseId . " ".
				 "AND status = " . Session::STATUS_CANCELED;
				 
		return Yii::app()->db->createCommand($query)->queryScalar();
	}
    
    private static function getDateConstraint($requestParams, $columnName){
        $type= $requestParams['type'];
        switch ($type){
            case 'date':
                $dateTimestamp =  strtotime($requestParams['date']);
                $date = date('Y-m-d 00:00:00', $dateTimestamp);
                $dateAfter = date('Y-m-d 00:00:00', strtotime('+1 days', $dateTimestamp));
                $dateConstraint = $columnName . " >= '" . $date . "' AND " . $columnName . " < '" . $dateAfter . "'";
                break;
            case 'week':
                $week = $requestParams['week'];
                $year = date('Y');
                if ($week < 10){
                    $week = "0" . $week;
                }
                $dateStartTimestamp = strtotime($year . 'W' . $week);
                $dateStart = date('Y-m-d 00:00:00', $dateStartTimestamp);
                $dateEnd = date('Y-m-d 00:00:00', strtotime('+7 days', $dateStartTimestamp));
                $dateConstraint = $columnName . " >= '" . $dateStart . "' AND " . $columnName . " < '" . $dateEnd . "'";
                break;
            case 'month':
                $month = $requestParams['month'];
                $year = $requestParams['year'];
                if ($month < 10){
                    $month = "0" . $month;
                }
                $monthStart = $year . '-' . $month . '-01 00:00:00';
                $monthEnd = date($year . '-' . $month . '-t 00:00:00');
                $dateConstraint = $columnName . " >= '" . $monthStart . "' AND " . $columnName . " < '" . $monthEnd . "'";
                break;
            case 'range':
                $dateFrom = date('Y-m-d 00:00:00', strtotime($requestParams['dateFrom']));
                $dateTo = date('Y-m-d 00:00:00', strtotime($requestParams['dateTo']));
                $dateConstraint = $columnName . " >= '" . $dateFrom . "' AND " . $columnName . " < '" . $dateTo . "'";
                break;
            default:
                break;
        }
        
        return $dateConstraint;
    }
    
    public static function getSessionNote($requestParams){
        $dateConstraint = self::getDateConstraint($requestParams, 's.plan_start');
        
        $countQuery = " SELECT COUNT(s.id)
                        FROM (
                            tbl_session s LEFT JOIN tbl_session_note n ON s.id = n.session_id
                        ) JOIN tbl_session_student ss ON s.id = ss.session_id
                        WHERE " . $dateConstraint;
                        
        $count = Yii::app()->db->createCommand($countQuery)->queryScalar();
        
        $query = "  SELECT  s.id,
                            subject,
                            teacher_id,
                            plan_start,
                            plan_duration,
                            status,
                            CASE 
                                WHEN status = " . Session::STATUS_CANCELED . " THEN status_note
                                ELSE note
                            END AS note,
                            using_platform,
                            teacher_paid
                    FROM (
                        tbl_session s LEFT JOIN tbl_session_note n ON s.id = n.session_id
                    ) JOIN tbl_session_student ss ON s.id = ss.session_id
                    WHERE " . $dateConstraint . " " .
                    "ORDER BY s.plan_start DESC";
                    
        return new CSqlDataProvider($query, array(
            'totalItemCount'=>$count,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'keyField'=>'id',
        ));
    }
	
	public static function getSessionNoteByCourse($courseId, $usingPlatform = null, $ended=false){
		if ($usingPlatform !== null){
			$joinType = "JOIN";
		} else {
			$joinType = "LEFT JOIN";
		}
		
		$otherFilter = '';
		if ($usingPlatform !== null){
			$otherFilter .= " AND tbl_session_note.using_platform = " . $usingPlatform;
		}
		if ($ended){
			$otherFilter .= " AND tbl_session.status = " . Session::STATUS_ENDED;
		}
		
		$countQuery = "SELECT COUNT(tbl_session.id) " .
					  "FROM (tbl_session " . $joinType . " tbl_session_note " .
					  "ON tbl_session.id = tbl_session_note.session_id) " .
					  "JOIN tbl_session_student ON tbl_session.id = tbl_session_student.session_id " .
					  "WHERE tbl_session.course_id = " . $courseId . $otherFilter;
					  
		$count = Yii::app()->db->createCommand($countQuery)->queryScalar();
		
		$query = "SELECT tbl_session.id, subject, teacher_id, plan_start, plan_duration, status, note, using_platform, teacher_paid " .
				 "FROM (tbl_session " . $joinType . " tbl_session_note " .
				 "ON tbl_session.id = tbl_session_note.session_id) " .
				 "JOIN tbl_session_student ON tbl_session.id = tbl_session_student.session_id " .
				 "WHERE tbl_session.course_id = " . $courseId . $otherFilter . " " .
				 "ORDER BY plan_start ASC";
		
		return new CSqlDataProvider($query, array(
			'totalItemCount'=>$count,
			'pagination'=>array(
				'pageSize'=>20,
			),
			'keyField'=>'id',
		));
	}
}
