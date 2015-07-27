<?php

class TeachingDay extends CActiveRecord
{
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('day, payment_id, platform_session, non_platform_session', 'required'),
			array('platform_session, non_platform_session', 'numerical', 'integerOnly'=>true),
			array('payment_id', 'unique', 'criteria'=>array(
				'condition'=>'`day`=:day',
				'params'=>array(
					':day'=>$this->day
				)
			)),
			array('note, platform_session, non_platform_session', 'safe'),
			array('day', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, payment_id, day, platform_session, non_platform_session, created_date, created_user_id, last_modified_date, last_modified_user_id', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		//Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert');
			$modelRules[] = array('last_modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
			$modelRules[] = array('created_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert');
			$modelRules[] = array('last_modified_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'update');
		}
		return $modelRules;//Return model rules
	}

	public function beforeSave()
	{
		parent::beforeSave();
		$stripTagFields = array('platform_session', 'non_platform_session', 'note');
		foreach($stripTagFields as $textField){
			$this->$textField = strip_tags($this->$textField);
		}
		
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
		return 'tbl_teachingday';
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
			'payment_id' => 'Kỳ thanh toán',
			'teacher_id'=>'Giáo viên',
			'day' => 'Ngày',
			'platform_session' => 'Buổi học trên platform',
			'non_platform_session' => 'Buổi học ngoài platform',
			'note' => 'Ghi chú',
			'created_date' => 'Ngày tạo',
			'created_user_id' => 'Người tạo',
			'last_modified_date' => 'Ngày sửa',
			'last_modified_user_id' => 'Người sửa',
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
	public function search($paymentId=null, $order='day desc')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$payment_id = ($paymentId != null) ? $paymentId : $this->payment_id;
		$criteria->compare('payment_id',$payment_id);
		$criteria->compare('day',$this->day);
		$criteria->compare('platform_session',$this->platform_session);
		$criteria->compare('non_platform_session',$this->non_platform_session);
		$criteria->compare('created_date',$this->created_date);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('last_modified_date',$this->last_modified_date);
		$criteria->compare('last_modified_user_id',$this->last_modified_user_id);
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
	
	public function allowEdit(){
		$payment = TeacherPayment::model()->findByPk($this->payment_id);
		if ($payment->report_status == TeacherPayment::STATUS_OPEN){
			return true;
		} else {
			return false;
		}
	}
}
