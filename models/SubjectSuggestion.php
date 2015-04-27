<?php

/**
 * This is the model class for table "tbl_subject_suggestion".
 *
 * The followings are the available columns in table 'tbl_subject_suggestion':
 * @property integer $id
 * @property integer $subject_id
 * @property string $title
 * @property string $description
 * @property string $created_date
 * @property string $modified_date
 */
class SubjectSuggestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_subject_suggestion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('title, subject_id', 'required'),
			array('subject_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>256),
			array('description, modified_date, created_user_id, modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, subject_id, title, description, created_date, modified_date, created_user_id, modified_user_id', 'safe', 'on'=>'search'),
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		//Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
			$modelRules[] = array('created_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert');
			$modelRules[] = array('modified_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'update');
		}
		return $modelRules;//Return model rules
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
			'id' => 'Mã',
			'subject_id' => 'Môn học',
			'title' => 'Chủ đề gợi ý',
			'description' => 'Mô tả',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
			'created_user_id' => 'Người tạo',
			'modified_user_id' => 'Người sửa',
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
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SubjectSuggestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//After save Subject Suggestion
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete Subject Suggestion
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
	/**
	 * Display class & subject
	 */
	public function displayClassSubject($type='')
	{
		$subject = Subject::model()->findByPk($this->subject_id);
		if(!isset($subject->id)){
			return NULL;
		}
		if($type=='class') return $subject->class;//Return class
		if($type=='subject') return $subject;//Return subject
		//Return class - subject name
		return $subject->class->name.' - '.$subject->name;
	}
	
	/**
	 * Load suggestion title by subject
	 */
	public function getSuggestionBySubject($subjectId)
	{
		$suggestTitles = array();
		$suggestions = SubjectSuggestion::model()->findAllByAttributes(array('subject_id'=>$subjectId));
		if(count($suggestions)>0){
			foreach($suggestions as $suggestion){
				$suggestTitles[] = $suggestion->title;
			}
		}
		return $suggestTitles;		 
	} 
	
}
