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

    CONST CLASS_1_1 = 1;
    CONST CLASS_1_2 = 2;
    CONST CLASS_3_6 = 6;
    CONST TYPE_STUDENT_OLD = 2;
    CONST TYPE_STUDENT_NEW = 1;

    public function getTypeStudent($get = null)
    {
        $type = array(
            self::TYPE_STUDENT_OLD=>'Học sinh cũ',
            self::TYPE_STUDENT_NEW=>'Học sinh mới',
        );
        if($get) {
            return $type[$get];
        }else {
            return $type;
        }
    }

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


    public function getClassNumbers($get = null)
    {
        $classes = array(
            self::CLASS_1_1=>'Lớp 1-1 (1 giáo viên, 1 học sinh)',
            self::CLASS_1_2=>'Lớp 1-2 (1 giáo viên, 2 học sinh)',
            self::CLASS_3_6=>'Lớp 3-6 (1 giáo viên, 3-6 học sinh)'
        );
        if($get) {
            return $classes[$get];
        }else {
            return $classes;
        }
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
            array('student+type+package_id', 'UniqueValidator'),
			array('student, tuition, sales, each_, package_id', 'required'),
			array('student, tuition, sales, each_, package_id, type', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, student, tuition, sales, each_, package_id, type', 'safe', 'on'=>'search'),
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
			'student' => 'Số học sinh',
            'package_id' => 'Gói',
			'tuition' => 'Giá gốc',
			'sales' => 'Thực đóng',
			'each_' => 'Mỗi buổi',
            'type'=>'Kiểu'
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
		$criteria->compare('student',$this->student);
        $criteria->compare('package_id',$this->package_id);
		$criteria->compare('tuition',$this->tuition);
		$criteria->compare('sales',$this->sales);
		$criteria->compare('each_',$this->each_);

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
