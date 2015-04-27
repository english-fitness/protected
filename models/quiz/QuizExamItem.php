<?php

/**
 * This is the model class for table "tbl_quiz_exam_item".
 *
 * The followings are the available columns in table 'tbl_quiz_exam_item':
 * @property integer $quiz_exam_id
 * @property integer $quiz_item_id
 */
class QuizExamItem extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_quiz_exam_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('quiz_exam_id, quiz_item_id', 'required'),
			array('quiz_exam_id, quiz_item_id, item_id_order', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('quiz_exam_id, quiz_item_id, item_id_order', 'safe', 'on'=>'search'),
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
			'quiz_exam_id' => 'Quiz Exam',
			'quiz_item_id' => 'Quiz Item',
			'item_id_order' => 'Quiz Item Order',
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
		$criteria->compare('quiz_item_id',$this->quiz_item_id);
		$criteria->compare('item_id_order',$this->item_id_order);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getItem()
    {
        return QuizItem::model()->findByPk($this->quiz_item_id);
    }



	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuizExamItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
