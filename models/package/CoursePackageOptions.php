<?php

/**
 * This is the model class for table "tbl_course_package_options".
 *
 * The followings are the available columns in table 'tbl_course_package_options':
 * @property integer $id
 * @property integer $student
 * @property integer $tuition
 * @property integer $sales
 * @property integer $each
 * @property integer $package_id
 * @property integer $type
 */
class CoursePackageOptions extends CActiveRecord
{

    private $_packageOptionMeta = array();

    /**
     * @var $optionMeta  = new CoursePackageOptions()
     */
    public function getOptionMeta($key)
    {
        if(isset($this->_packageOptionMeta[$key]))
            return $this->_packageOptionMeta[$key];
        else{
            $optionMeta = new PackageOptionmeta();
            $optionMeta = $optionMeta->findByAttributes(array('option_id'=>$this->id,'meta_key'=>$key));

            return $this->_packageOptionMeta[$key] = isset($optionMeta->meta_value)?$optionMeta->meta_value:null;
        }

    }

    /**
     * @return updateOptionsMeta();
     */
    public function updateOptionsMeta($data,$formName = 'Meta')
    {
        PackageOptionmeta::model()->metaOptions($this->id,$data,$formName);
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_course_package_options';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tuition, package_id, valid_from', 'required'),
			array('tuition, package_id', 'numerical', 'integerOnly'=>true),
			array('valid_from, expire_date', 'type', 'type' => 'date', 'dateFormat' => 'yyyy-MM-dd'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tuition, package_id', 'safe', 'on'=>'search'),
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
            'package'=>array(self::BELONGS_TO, 'CoursePackage', 'package_id')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
            'package_id' => 'Gói',
			'tuition' => 'Học phí',
            'note'=>'Ghi chú',
            'valid_from'=>'Có hiệu lực từ',
            'expire_date'=>'Ngày hết hạn',
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
        $criteria->compare('package_id',$this->package_id);
		$criteria->compare('tuition',$this->tuition);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function afterDelete()
	{
		PackageOptionmeta::model()->deleteAllByAttributes(array('option_id'=>$this->id));
		return parent::afterDelete();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoursePackageOptions the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
