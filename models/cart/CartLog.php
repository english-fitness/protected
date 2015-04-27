<?php

/**
 * This is the model class for table "tbl_cart_log".
 *
 * The followings are the available columns in table 'tbl_cart_log':
 * @property integer $id
 * @property integer $type
 * @property integer $user_id
 * @property integer $cart_id
 * @property string $log_value
 * @property integer $created_time
 * @property integer $ip_address
 */
class CartLog extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_cart_log';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, cart_id, log_value, created_time, ip_address', 'required'),
			array('type, user_id, cart_id, created_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, user_id, cart_id, log_value, created_time, ip_address', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
            'cart' => array(self::BELONGS_TO, 'Cart', 'cart_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type' => 'Type',
			'user_id' => 'User',
			'cart_id' => 'Cart',
			'log_value' => 'Log Value',
			'created_time' => 'Create Time',
			'ip_address' => 'Ip Address',
		);
	}

    /**
     * @param $cart Cart
     * @param  string $value
     */
    public function log($cart,$value)
    {
        $model = new CartLog();
        $model->user_id = Yii::app()->user->id;
        $model->type = 1;
        $model->cart_id = $cart->cart_id;
        $model->created_time = time();
        $model->log_value = $value;
        $model->ip_address = Yii::app()->request->userHostAddress;
        $model->save();
        return $model;
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
        $criteria->order = "created_time DESC";
		$criteria->compare('id',$this->id);
		$criteria->compare('type',$this->type);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('cart_id',$this->cart_id);
		$criteria->compare('log_value',$this->log_value,true);
		$criteria->compare('created_time',$this->created_time);
		$criteria->compare('ip_address',$this->ip_address);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CartLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
