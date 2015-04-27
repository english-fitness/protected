<?php

/**
 * This is the model class for table "tbl_quiz_exam_topic".
 *
 * The followings are the available columns in table 'tbl_quiz_exam_topic':
 * @property integer $quiz_exam_id
 * @property integer $quiz_topic_id
 */
class QuizExamTopic extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_quiz_exam_topic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('quiz_exam_id, quiz_topic_id', 'required'),
			array('quiz_exam_id, quiz_topic_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('quiz_exam_id, quiz_topic_id', 'safe', 'on'=>'search'),
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
            'exam'=>array(self::BELONGS_TO, 'QuizExam', 'quiz_exam_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'quiz_exam_id' => 'Quiz Exam',
			'quiz_topic_id' => 'Quiz Topic',
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

		$criteria->compare('quiz_exam_id',$this->quiz_exam_id);
		$criteria->compare('quiz_topic_id',$this->quiz_topic_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuizExamTopic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
