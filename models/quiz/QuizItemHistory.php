<?php

/**
 * This is the model class for table "tbl_quiz_item_history".
 *
 * The followings are the available columns in table 'tbl_quiz_item_history':
 * @property integer $student_id
 * @property integer $quiz_item_id
 * @property integer $is_correct
 */
class QuizItemHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_quiz_item_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, quiz_item_id', 'required'),
			array('student_id, quiz_item_id, is_correct', 'numerical', 'integerOnly'=>true),
			array('answer', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('student_id, quiz_item_id, is_correct, answer', 'safe', 'on'=>'search'),
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
			'student_id' => 'Student',
			'quiz_item_id' => 'Quiz Item',
			'is_correct' => 'Is Correct',
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

		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('quiz_item_id',$this->quiz_item_id);
		$criteria->compare('answer',$this->answer);
		$criteria->compare('is_correct',$this->is_correct);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return QuizItemHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Delete all item history in an exam
	 */
	public function deleteHistoryItems($studentId, $examId)
	{
		//Delete all history of item in exam of student
		$query = "SELECT quiz_item_id FROM tbl_quiz_exam_item WHERE quiz_exam_id=$examId";
		$examItemIds = Yii::app()->db->createCommand($query)->queryColumn();
		$whereItemId = "(".implode(",", $examItemIds).")";
		$subResultQuery = "SELECT id FROM tbl_quiz_item WHERE id IN ".$whereItemId." OR parent_id IN ".$whereItemId;
		$criteria=new CDbCriteria;
        $criteria->addCondition("student_id = $studentId");
        $criteria->addCondition("quiz_item_id IN (".$subResultQuery.")");
        QuizItemHistory::model()->deleteAll($criteria);
	}
	
	/**
	 * Save quiz item history of student in exam
	 */
	public function saveWorkedExamItems($studentId, $examId,  $answeredItems)
	{
		//Delete all history of item in exam of student
        try{
            $this->deleteHistoryItems($studentId, $examId);
        }catch (Exception $e){
            //
        }
        $countCorrect = 0;
		$newWorkedItems = array();//New worked items
		foreach($answeredItems as $itemId=>$answer){
			$item = QuizItem::model()->findByPk($itemId);
			$is_correct = ($answer==$item->correct_answer)? 1: 0;
            if($is_correct) $countCorrect++;
			$newWorkedItems[] = array(
				'student_id'=>$studentId,
				'quiz_item_id'=>$itemId,
				'answer'=>$answer,
				'is_correct'=>$is_correct,
			);
		}
		//Save item history
		$builder = Yii::app()->db->schema->commandBuilder;
		$command = $builder->createMultipleInsertCommand('tbl_quiz_item_history', $newWorkedItems)->execute();
        return $countCorrect;
	}

}
