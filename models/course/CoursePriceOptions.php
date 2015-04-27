<?php

/**
 * This is the model class for table "tbl_course_price_options".
 *
 * The followings are the available columns in table 'tbl_course_price_options':
 * @property integer $id
 * @property integer $type
 * @property integer $total_student
 * @property integer $hp_toan_khoa
 * @property integer $dong_trong_luc_hoc_thu
 * @property integer $dong_ngay_khong_hoc_thu
 * @property integer $hoc_thu
 */
class CoursePriceOptions extends CActiveRecord
{
    CONST TYPE_COURSE = 1;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_course_price_options';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type', 'required'),
            array('total_student','unique'),
			array('type, total_student, hoc_thu, tuition, hoc_thu_banking, sale', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, total_student, tuition, hoc_thu, hoc_thu_banking, sale', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'type' => 'Kiểu',
			'total_student' => 'Số học sinh',
			'tuition' => 'Hoc phí/Buổi',
			'hoc_thu' => 'Học thử',
            'sale' =>'Giá thực đóng'
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
		$criteria->compare('type',$this->type);
		$criteria->compare('total_student',$this->total_student);
		$criteria->compare('hoc_thu',$this->hoc_thu);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoursePriceOptions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
