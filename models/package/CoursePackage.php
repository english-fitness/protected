<?php

/**
 * This is the model class for table "tbl_course_package".
 *
 * The followings are the available columns in table 'tbl_course_package':
 * @property integer $id
 * @property integer $sessions
 * @property integer $type
 * @property string $title
 */
class CoursePackage extends CActiveRecord
{

    CONST TYPE_OFFICIAL = 1;
    CONST TYPE_TRIAL = 2;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_course_package';
	}

    public function getPackageType($get = null) {
        $list =  array(self::TYPE_OFFICIAL=>'Chính thức',self::TYPE_TRIAL=>'Thử nghiệm');
        if($get) {
            return $list[$get];
        }else {
            return $list;
        }
    }


	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('sessions, type', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>255),
            array('sessions', 'unique'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, sessions, type, title', 'safe', 'on'=>'search'),
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
            'options'=>array(
                self::HAS_ONE,'CoursePackageOptions',array('package_id'=>'id')
            ),
		);
	}

    public function getOption($userStatus,$numberStudent)
    {
        return CoursePackageOptions::model()->findByAttributes(array('type'=>$userStatus,'student'=>$numberStudent,'package_id'=>$this->id));
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'sessions' => 'Số buổi',
			'type' => 'Kiểu',
			'title' => 'Tiêu đề',
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
		$criteria->compare('sessions',$this->sessions);
		$criteria->compare('type',$this->type);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function afterDelete()
	{
		$models = CoursePackageOptions::model()->findAllByAttributes(array('package_id'=>$this->id));
		foreach($models as $model)
			$model->delete();
		return parent::afterDelete();
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CoursePackage the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
