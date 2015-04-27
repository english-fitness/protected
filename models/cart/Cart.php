<?php

/**
 * This is the model class for table "tbl_cart".
 *
 * The followings are the available columns in table 'tbl_cart':
 * @property integer $cart_id
 * @property integer $cart_type
 * @property integer $cart_code
 * @property integer $cart_price
 * @property integer $cart_status
 */
class Cart extends CActiveRecord
{
    CONST STATUS_UNUSED = 1;
    CONST STATUS_USED   = 2;

    CONST TYPE_CARD_PROMOTION =0;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_cart';
	}

    /**
     * @return array validation getStatusLabels() for model attributes.
     */
    public function getStatusLabels()
    {
        return array(self::STATUS_UNUSED=>'Chưa sử dụng',self::STATUS_USED=>'Đã sử dụng');
    }

    /**
     * @return string validation getStatusLabel() for model attributes.
     */
    public function getStatusLabel()
    {
        $statusArr = $this->getStatusLabels();
        return $statusArr[$this->cart_status];
    }

    /**
     * @param integer $code
     * @return true/false
     */
    public function findCartByCode($code)
    {
        return  $this->findByAttributes(array('cart_code'=>$code,'cart_status'=>self::STATUS_UNUSED));
    }

    /**
     * @return array validation getStatusLabels() for model attributes.
     */
    public function getTypeLabels()
    {
        return array(self::TYPE_CARD_PROMOTION=>'Thẻ khuyến mãi');
    }

    /**
     * @return string validation getStatusLabel() for model attributes.
     */
    public function getTypeLabel()
    {
        $statusArr = $this->getTypeLabels();
        return $statusArr[$this->cart_type];
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('cart_code', 'unique'),
			array('cart_code, cart_price, cart_status', 'required'),
			array('cart_type, cart_price, cart_status', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cart_id, cart_type, cart_code, cart_price, cart_status', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cart_id' => 'Seri',
			'cart_type' => 'Kiểu thẻ',
			'cart_code' => 'Mã thẻ',
			'cart_price' => 'Mệnh giá',
			'cart_status' => 'Trạng thái',
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
        $criteria->order = "cart_status asc";
		$criteria->compare('cart_id',$this->cart_id);
		$criteria->compare('cart_type',$this->cart_type);
		$criteria->compare('cart_code',$this->cart_code);
		$criteria->compare('cart_price',$this->cart_price);
		$criteria->compare('cart_status',$this->cart_status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
