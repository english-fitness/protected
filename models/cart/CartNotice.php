<?php

/**
 * This is the model class for table "tbl_cart_notice".
 *
 * The followings are the available columns in table 'tbl_cart_notice':
 * @property integer $id
 * @property integer $cart_id
 * @property integer $from_id
 * @property integer $to_id
 * @property string $content
 * @property integer $read_status
 * @property integer $created_time
 */
class CartNotice extends CActiveRecord
{

    CONST STATUS_UNREAD = 0;
    CONST STATUS_READ   = 1;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_cart_notice';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cart_id, from_id, content, created_time', 'required'),
			array('cart_id, from_id, read_status, created_time', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, cart_id, from_id, content, read_status, created_time', 'safe', 'on'=>'search'),
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
            'user' => array(self::BELONGS_TO, 'User', 'from_id'),
            'cart' => array(self::BELONGS_TO, 'Cart', 'cart_id'),
		);
	}

    /**
     * @return array list notification.
     */
    public function findNewNotifications()
    {
        return $this->findAllByAttributes(array(),array('order'=>'created_time desc , read_status desc','limit'=>'50'));
    }

    /**
     * @return integer count notification.
     */
    public function countNewNotification()
    {
        return $this->countByAttributes(array('read_status'=>self::STATUS_UNREAD));
    }

    /**
     * @return true/false.
     */
    public function UpdatedRead()
    {
        if($this->read_status ==self::STATUS_READ)
            return true;
        else {
            $this->read_status = self::STATUS_READ;
            return $this->save();
        }
    }

    /**
     * @param object $cart
     * @param  string $content
     * @return true.
     */
    public function send($cart,$content)
    {
        $model = new CartNotice();
        $model->from_id = Yii::app()->user->id;
        $model->cart_id = $cart->cart_id;
        $model->created_time = time();
        $model->content = $content;
        $model->save();
        return $model;
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'cart_id' => 'Cart',
			'from_id' => 'From',
			'content' => 'Content',
			'read_status' => 'Read Status',
			'created_time' => 'Created Time',
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
        $criteria->order = "created_time DESC";
		$criteria->compare('id',$this->id);
		$criteria->compare('cart_id',$this->cart_id);
		$criteria->compare('from_id',$this->from_id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('read_status',$this->read_status);
		$criteria->compare('created_time',$this->created_time);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CartNotice the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
