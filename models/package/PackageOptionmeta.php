<?php

/**
 * This is the model class for table "tbl_package_optionmeta".
 *
 * The followings are the available columns in table 'tbl_package_optionmeta':
 * @property integer $meta_id
 * @property integer $option_id
 * @property string $meta_key
 * @property string $meta_value
 */
class PackageOptionmeta extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_package_optionmeta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
            array('option_id+meta_key', 'UniqueValidator'),
			array('option_id, meta_key, meta_value', 'required'),
			array('option_id', 'numerical', 'integerOnly'=>true),
			array('meta_key', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('meta_id, option_id, meta_key, meta_value', 'safe', 'on'=>'search'),
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
			'meta_id' => 'Meta',
			'option_id' => 'Option',
			'meta_key' => 'Meta Key',
			'meta_value' => 'Meta Value',
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

		$criteria->compare('meta_id',$this->meta_id);
		$criteria->compare('option_id',$this->option_id);
		$criteria->compare('meta_key',$this->meta_key,true);
		$criteria->compare('meta_value',$this->meta_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     * @return this;
     */
    public  function metaOptions($optionId,array $data,$formName = 'Meta')
    {
        $data = isset($data[$formName])?$data[$formName]:array();
        foreach($data as $key=>$value) {
            $model = PackageOptionmeta::findByAttributes(array('option_id'=>$optionId,'meta_key'=>$key));
            if(!$model) { $model = new PackageOptionmeta();}
            $model->meta_key = $key;
            $model->meta_value = $value;
            $model->option_id = $optionId;
            $model->save();
        }
    }

    

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PackageOptionmeta the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
