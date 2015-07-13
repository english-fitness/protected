<?php

class TeacherPayment extends CActiveRecord
{
	const STATUS_OPEN = 0;
	const STATUS_CLOSED = 1;
	
	const STATUS_UNPAID = 0;
	const STATUS_PAID = 1;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('id, report_status, payment_status, payment_date, report_date', 'safe'),
			array('teacher_id', 'required'),
			array('teacher_id', 'unique', 'criteria'=>array(
				'condition'=>'`month`=:month',
				'params'=>array(
					':month'=>$this->month
				)
			)),
			array('month, payment_date, report_date', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, teacher_id, month, report_status, payment_status, report_date, payment_date', 'safe', 'on'=>'search'),
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
		return 'tbl_teacher_payment';
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'payment' => array(self::HAS_MANY, 'TeachingDay', 'payment_id'),
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
			'month' => 'Tháng',
			'report_status' => 'Trạng thái',
			'total_platform_session'=>'Buổi dạy trên platform',
			'total_non_platform_session'=>'Buổi dạy ngoài platform',
			'payment_status' => 'Trạng thái thanh toán',
			'report_date' => 'Ngày tổng hợp',
			'report_user_id' => 'Người tổng hợp',
			'payment_date' => 'Ngày thanh toán',
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
	public function search($teacherId=null, $order='month desc')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$teacher_id = ($teacherId != null) ? $teacherId : $this->teacher_id;
		$criteria->compare('teacher_id',$teacher_id);
		$criteria->compare('month',$this->month);
		$criteria->compare('report_status',$this->report_status);
		$criteria->compare('payment_status',$this->payment_status);
		$criteria->compare('report_date',$this->report_date);
		$criteria->compare('report_user_id',$this->report_user_id);
		$criteria->compare('payment_date',$this->payment_date);
		
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
	
	public static function paymentStatusOptions()
	{
		return array(
			self::STATUS_PAID => "Đã thanh toán",
			self::STATUS_UNPAID => "Chưa thanh toán",
		);
	}
	
	public static function statusOptions()
	{
		return array(
			self::STATUS_OPEN => "Chưa tổng hợp",
			self::STATUS_CLOSED => "Đã tổng hợp",
		);
	}
	
	//getters
	public function getStatus(){
		$statusOptions = self::statusOptions();
		if (isset($statusOptions[$this->report_status])){
			return $statusOptions[$this->report_status];
		} else {
			return "Chưa xác định";
		}
	}
	
	public function getPaymentStatus(){
		$paymentStatusOptions = self::paymentStatusOptions();
		if (isset($paymentStatusOptions[$this->payment_status])){
			return $paymentStatusOptions[$this->payment_status];
		} else {
			return "Chưa xác định";
		}
	}
	
	//utilities
	public function countDays(){
		$query = "SELECT COUNT(id) FROM tbl_teachingday " .
				 "WHERE payment_id = " . $this->id;
		$result = Yii::app()->db->createCommand($query)->queryColumn();
		return $result[0];
	}
	
	public function getTeacherName(){
		$teacher = User::model()->findByPk($this->teacher_id);
		return $teacher->fullname();
	}
	
	public function getTeacherLink(){
		$teacher = User::model()->findByPk($this->teacher_id);
		return '<a href="' . Yii::app()->baseUrl . '/admin/teacher/view/id/' . $this->teacher_id . '">' . $teacher->fullname() . '</a>';
	}
}
