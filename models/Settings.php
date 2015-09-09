<?php

/**
 * This is the model class for table "tbl_settings".
 *
 * The followings are the available columns in table 'tbl_settings':
 * @property integer $id
 * @property integer $type
 * @property string $condition
 * @property string $value
 */
class Settings extends CActiveRecord
{
	
	CONST SETTING_HEADER = 1;

    CONST SETTING_SHARE_FACEBOOK = 2;

    CONST STATUS_YES = 1;

    CONST STATUS_NO = 0;

    CONST SHARE_PRESET_REQUEST = "preset_request";
    CONST SHARE_COURSE_REQUEST = "course_request";
    CONST SHARE_QUIZ           = "share_quiz";
    CONST SHARE_SIGN_UP_ACCOUNT  = 'share_sign_up_account';

    // share items
    public $shareItems = array(
        self::SHARE_PRESET_REQUEST =>'Share Preset Request',
        self::SHARE_COURSE_REQUEST =>'Share Course Request',
        self::SHARE_QUIZ =>'Share Quiz',
        self::SHARE_SIGN_UP_ACCOUNT=>'Share sign up account'
    );

    // share items
    public $status_all = array(
        self::STATUS_YES =>'Yes',
        self::STATUS_NO  =>'No'
    );

    /**
     * getShareItems
     */
    public function getShareItems($key)
    {
        if(isset($this->shareItems[$key])) {
            return $this->shareItems[$key];
        } else {
            throw new CHttpException(404,'The requested page does not exist.');
        }
    }

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type, condition, value', 'required'),
			array('type, status', 'numerical', 'integerOnly'=>true),
			array('condition', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, type, condition, value, status', 'safe', 'on'=>'search'),
            array('condition','unique','on'=>'add_share_facebook')
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
			'condition' => 'Điều kiện',
			'value' => 'Giá trị',
            'status'=>'Trạng thái'
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
		$criteria->compare('condition',$this->condition,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


    public function getContent()
    {
        return $this->getValueContent('content');
    }

    public function getLink()
    {
        return $this->getValueContent('link');
    }

    public function getValueContent($filed)
    {
        $content = json_decode($this->value);
        return (isset($content->$filed))?$content->$filed:'';
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
     * load header content page
     */
	public static function loadHeader($url)
	{
        $models = self::model()->findAllByAttributes(array('type'=>Settings::SETTING_HEADER));
       foreach($models as $model){
           $data = explode('{*}',$model->condition);
           if(count($data) > 1 ){
               if ( $data[0] !="" && $data[1] !="" && strpos($url,$data[0]) !==false && strpos($url,$data[1]) !==false) {
                   return $model;
               }
           } else {
               if($data[0] == $url){
                   return $model;
               }
           }
       }
	}

    public function findByCondition($condition,$type)
    {
        return self::model()->findByAttributes(array('condition'=>$condition,'type'=>$type));
    }

    public function beforeSave()
    {
        if(parent::beforeSave()) {
            $this->value = (is_array($this->value))?json_encode($this->value):$this->value;
            return true;
        }
        return false;
    }

    /**
     * load header content page
     */
    public static function shareFacebook($condition,$data)
    {
        $result = array();
        $data = (is_array($data->attributes))?$data->attributes: array();
        foreach($data as $key=>$value){
            $result['{'.$key.'}'] = $value;
        }
        $setting = self::findByCondition($condition,self::SETTING_SHARE_FACEBOOK);
        if(isset($setting->value) && $setting->status == self::STATUS_YES) {
            ClsFacebookShare::app()->url($setting->link)->content(Yii::t('setting_share',$setting->content,$result))->share();
        }
    }
	
    public static function getPresetOptions($name){
        $query= "SELECT value FROM tbl_preset_options
                WHERE name = '" . $name . "'";
        return Yii::app()->db->createCommand($query)->queryColumn();
    }
}
