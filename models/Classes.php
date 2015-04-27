<?php

/**
 * This is the model class for table "tbl_class".
 *
 * The followings are the available columns in table 'tbl_class':
 * @property integer $id
 * @property string $name
 *
 * The followings are the available model relations:
 * @property Subject[] $subjects
 */
class Classes extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_class';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name', 'safe', 'on'=>'search'),
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
			'subjects' => array(self::HAS_MANY, 'Subject', 'class_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Mã lớp',
			'name' => 'Tên lớp',
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
		$criteria->compare('name',$this->name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Classes the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
	/**
	 * Get all classes & order by name asc
	 */
	public function getAll($isAll=true)
	{
		$criteria = new CDbCriteria;		
		if(!$isAll){
			$criteria->addCondition('id IN (SELECT class_id FROM tbl_subject WHERE allow_to_teach=1)');
		}
		$criteria->order = 'name ASC';
		return Classes::model()->findAll($criteria);
	}
    
    /**
     * Count approved topic in class
     */
    public function countApprovedParentTopic()
    {
    	$criteria = new CDbCriteria;
    	$criteria->compare('parent_id', 0);//Parent id
    	$criteria->compare('status', QuizTopic::STATUS_APPROVED);
    	$criteria->compare('deleted_flag', 0);
    	$criteria->addCondition('subject_id IN (SELECT id FROM tbl_subject WHERE class_id='.$this->id.')');
    	$count = QuizTopic::model()->count($criteria);
    	return $count;//Count approve topic
    }
   
}
