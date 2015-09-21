<?php

class CoursePayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_course_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('package_option_id', 'required'),
			array('package_option_id', 'numerical', 'integerOnly'=>true),
			array('note', 'length', 'max'=>256),
            array('payment_date', 'default', 'setOnEmpty'=>true, 'value'=>null),
			array('note, payment_date, created_date, created_user_id, last_modified_date, last_modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('payment_date, created_user_id, last_modified_user_id', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
            array('created_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert'),
            array('last_modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
            array('last_modified_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		//Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('last_modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
            $modelRules[] = array('last_modified_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'update');
		}
		return $modelRules;//Return model rules
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
			'createdUser' => array(self::HAS_ONE, 'User', array('id'=>'created_user_id')),
            // why this ._.
			'modifiedUser' => array(self::HAS_ONE, 'User', array('id'=>'last_modified_user_id')),
            'packageOption' => array(self::HAS_ONE, 'CoursePackageOptions', array('id'=>'package_option_id')),
		);
	}

	//After save course
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete course
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'course_id'=>'Khóa học',
			// 'tuition'=>'Học phí',
			// 'number_of_sessions'=>'Số buổi học',
			'note'=>'Ghi chú',
            'payment_date'=>'Ngày nộp tiền',
			'created_user_id' => 'Người tạo',
			'created_date' => 'Ngày tạo',
			'last_modified_date' => 'Ngày sửa cuối cùng',
			'last_modified_user_id' => 'Người sửa cuối cùng',
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
	public function search($order=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
        $criteria->compare('course_id',$this->course_id);
		// $criteria->compare('tuition',$this->tuition);
		// $criteria->compare('number_of_sessions',$this->number_of_sessions);
		$criteria->compare('payment_date',$this->payment_date);
		$criteria->compare('created_date',$this->created_date);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('last_modified_date',$this->last_modified_date);
		$criteria->compare('last_modified_user_id',$this->last_modified_user_id);
		if($order!==null) $criteria->order = $order;
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
	 * @return CoursePayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	
}
