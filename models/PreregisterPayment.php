<?php

/**
 * This is the model class for table "tbl_preregister_payment".
 *
 * The followings are the available columns in table 'tbl_preregister_payment':
 * @property integer $id
 * @property integer $precourse_id
 * @property integer $total_of_session
 * @property double $amount
 * @property double $paid_amount
 * @property integer $status
 * @property string $note
 * @property string $created_date
 * @property string $modified_date
 */
class PreregisterPayment extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_preregister_payment';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('precourse_id, paid_amount, payment_method', 'required'),
			array('precourse_id, status', 'numerical', 'integerOnly'=>true),
			array('paid_amount', 'numerical'),
			array('transaction_id, note, modified_date, payment_method, payment_date, created_user_id,modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, precourse_id, paid_amount, status, note, created_date, modified_date, payment_method, payment_date,modified_user_id', 'safe', 'on'=>'search'),
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		//Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
			$modelRules[] = array('created_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert');
			$modelRules[] = array('modified_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'update');
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'precourse_id' => 'Đơn xin học',
			'transaction_id' => 'Mã giao dịch',
			'paid_amount' => 'Tiền đã trả',
			'payment_method' => 'Phương thức thanh toán',
			'payment_date' => 'Ngày thanh toán',
			'status' => 'Trạng thái',
			'note' => 'Ghi chú',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
			'created_user_id' => 'Người tạo',
			'modified_user_id' => 'Người sửa',
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
		$criteria->compare('precourse_id',$this->precourse_id);
		$criteria->compare('transaction_id',$this->transaction_id);
		$criteria->compare('paid_amount',$this->paid_amount);
		$criteria->compare('payment_date',$this->payment_date, true);
		$criteria->compare('payment_method',$this->payment_method);
		$criteria->compare('status',$this->status);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}
	
	//After save Preregister Payment
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete Preregister Payment
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PreregisterPayment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Display preregister course from payment
	 */
	public function displayPreregisterCourse()
	{
		$preCourse = PreregisterCourse::model()->findByPk($this->precourse_id);
		if($preCourse){
			return CHtml::link($preCourse->title, Yii::app()->createUrl("admin/preregisterCourse/view/id/$preCourse->id"));
		}
		return NULL;
	}
	
	/**
	 * Get preregister course from preset
	 */
	public function getStudent($studentViewLink=null)
	{
		$preCourse = PreregisterCourse::model()->findByPk($this->precourse_id);
		if(isset($preCourse->id)){
			return $preCourse->getStudent($studentViewLink);
		}
		return NULL;
	}
	
}
