<?php

/**
 * This is the model class for table "tbl_user_sales_history".
 *
 * The followings are the available columns in table 'tbl_user_sales_history':
 * @property integer $id
 * @property integer $user_id
 * @property integer $preregister_user_id
 * @property string $sale_date
 * @property string $next_sale_date
 * @property string $sale_note
 * @property string $sale_status
 * @property string $sale_question
 * @property string $user_answer
 * @property integer $created_user_id
 * @property integer $modified_user_id
 * @property integer $deleted_flag
 * @property string $created_date
 * @property string $modified_date
 */
class UserSalesHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_sales_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('sale_date,sale_note', 'required'),
			array('id, user_id, preregister_user_id, created_user_id, modified_user_id, deleted_flag', 'numerical', 'integerOnly'=>true),
			array('sale_note, sale_question, user_answer', 'length', 'max'=>256),
			array('sale_status', 'length', 'max'=>80),
			array('next_sale_date', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			array('sale_date, next_sale_date, created_date, modified_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, preregister_user_id, sale_date, next_sale_date, sale_note, sale_status, sale_question, user_answer, created_user_id, modified_user_id, deleted_flag, created_date, modified_date', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert')
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
			'user_id' => 'Mã người dùng',
			'preregister_user_id' => 'Mã đăng ký',
			'sale_date' => 'Ngày tư vấn',
			'next_sale_date' => 'Ngày hẹn tư vấn',
			'sale_note' => 'Ghi chú',
			'sale_status' => 'Trạng thái Sale',
			'sale_question' => 'Nội dung tư vấn',
			'user_answer' => 'Kết quả tư vấn',
			'created_user_id' => 'Người tạo',
			'modified_user_id' => 'Người sửa',
			'deleted_flag' => 'Trạng thái xóa',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('preregister_user_id',$this->preregister_user_id);
		$criteria->compare('sale_date',$this->sale_date,true);
		$criteria->compare('next_sale_date',$this->next_sale_date,true);
		$criteria->compare('sale_note',$this->sale_note,true);
		$criteria->compare('sale_status',$this->sale_status,true);
		$criteria->compare('sale_question',$this->sale_question,true);
		$criteria->compare('user_answer',$this->user_answer,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('modified_user_id',$this->modified_user_id);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);

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
	 * @return UserSalesHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//Remove html tags of some fields before save Sale history
	public function beforeSave()
	{
		//Set null for empty field in table
		if($this->sale_date=='') $this->sale_date = NULL;
		if($this->next_sale_date=='') $this->next_sale_date = NULL;
		return true;
	}
	
	//After save Sale History
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete Sale History
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
	/**
	* Get sale user history of user
	*/
	public function getSaleHistory($userId=null, $preregisterUserId=null)
	{
		$criteria=new CDbCriteria;
		if($userId!==null){
			$criteria->compare('user_id',$userId);
		}
		if($preregisterUserId!==null){
			$criteria->compare('preregister_user_id',$preregisterUserId);
		}
		$criteria->order = 'sale_date DESC';
		$criteria->compare('deleted_flag', 0);
		$saleHistory = UserSalesHistory::model()->findAll($criteria);
		return $saleHistory;
	}
	
	/**
	 * Get sale student of sale history
	 */
	public function getStudent($studentViewLink=null){
		$student = User::model()->findByPk($this->user_id);
		if(isset($student->id)){
			if($studentViewLink!=null){
				$link = '<a href="'.$studentViewLink.'/'.$student->id.'" title="Điện thoại: '.$student->phone.'">'.$student->fullName().'</a>';
				return $link;
			}
			return $student->fullName();
		}
        return NULL;
	}
	
}
