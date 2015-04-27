<?php

/**
 * This is the model class for table "tbl_quiz_item".
 *
 * The followings are the available columns in table 'tbl_quiz_item':
 * @property integer $id
 * @property string $content
 * @property string $tags
 * @property string $suggestion
 * @property string $explaination
 * @property string $anwsers
 * @property string $correct_anwser
 * @property integer $deleted_flag
 */
class QuizItem extends CActiveRecord
{
	//Const for status of QuizTopic
    const STATUS_PENDING = 0;//Pending status
    const STATUS_APPROVED = 1;//Approved status
    //Const for level of QuizTopic
    const LEVEL_AVERAGE = 0;//Level average
    const LEVEL_GOOD = 1;//Level good
    const LEVEL_EXCELLENT = 2;//Level Excellent

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_quiz_item';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('content, subject_id', 'required'),
			array('deleted_flag, parent_id', 'numerical', 'integerOnly'=>true),
			array('tags, level', 'length', 'max'=>256),
			array('correct_answer', 'length', 'max'=>10),
			array('suggestion, explaination, answers, status, correct_answer, parent_id,created_user_id,created_date,modified_date,modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, content, tags, suggestion, explaination, answers, correct_anwser, deleted_flag, status, level,created_user_id,created_date,modified_date,modified_user_id', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
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
			'id' => 'Mã câu hỏi',
			'subject_id' => 'Môn học',
			'content' => 'Nội dung câu hỏi',
			'tags' => 'Tags',
			'suggestion' => 'Gợi ý',
			'explaination' => 'Lời giải chi tiết',
			'answers' => 'Câu trả lời',
			'correct_answer' => 'Câu trả lời đúng',
			'level' => 'Mức độ khó',
			'status' => 'Trạng thái',
			'created_user_id' => 'Người tạo',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
			'modified_user_id' => 'Người sửa',
			'deleted_flag' => 'Trạng thái xóa',
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
	public function search($topicId=null, $examId=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('tags',$this->tags,true);
		$criteria->compare('suggestion',$this->suggestion,true);
		$criteria->compare('explaination',$this->explaination,true);
		$criteria->compare('answers',$this->answers,true);
		$criteria->compare('correct_answer',$this->correct_answer,true);
		$criteria->compare('level',$this->level);
		$criteria->compare('status',$this->status);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		if($topicId!==null){
			$criteria->addCondition("id IN (SELECT quiz_item_id FROM tbl_quiz_item_topic WHERE quiz_topic_id=$topicId)");
		}
		if($examId!==null){
			$criteria->addCondition("id IN (SELECT quiz_item_id FROM tbl_quiz_exam_item WHERE quiz_exam_id=$examId)");
		}
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
	 * @return TblQuizItem the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//After save QuizItem
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete QuizItem
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}
	
	
	//Display Status options
	public function statusOptions($status=null)
	{
		$statusOptions = array(
			self::STATUS_PENDING => 'Đang chờ',
			self::STATUS_APPROVED => 'Đã xác nhận',
		);
		if($status==null){
			return $statusOptions;
		}elseif(isset($statusOptions[$status])){
			return $statusOptions[$status];
		}
		return null;
	}
	
	//Display Level options
	public function levelOptions($level=null)
	{
		$levelOptions = array(
			self::LEVEL_AVERAGE => 'Trung bình',
			self::LEVEL_GOOD => 'Khá',
			self::LEVEL_EXCELLENT => 'Giỏi',
		);
		if($level==null){
			return $levelOptions;
		}elseif(isset($levelOptions[$level])){
			return $levelOptions[$level];
		}
		return null;
	}
	
	/**
	 * Generate answers of QuizItem
	 */
	public function generateAnswers($onlyAnswerKey=false)
	{
		if($onlyAnswerKey){//Generate only key of answer
			return array('A'=>'A', 'B'=>'B', 'C'=>'C', 'D'=>'D');
		}elseif($this->answers!=""){//Generate answers to array
			$itemAnswers = json_decode($this->answers);
			if(is_array($itemAnswers) || is_object($itemAnswers)){
				return (array)$itemAnswers;
			}
		}
		return array('A'=>'', 'B'=>'', 'C'=>'', 'D'=>'');		
	}
	
	/**
	 * Assign quizItem to some quizExam
	 */
	public function assignItemToExams($examIds=array(), $itemId=null)
	{
		$quizItemId = ($itemId)? $itemId: $this->id; 
		if(count($examIds)>0){
			foreach($examIds as $examId){
				$examItemAttrs = array('quiz_exam_id'=>$examId, 'quiz_item_id'=>$quizItemId);
				$examItem = QuizExamItem::model()->findByAttributes($examItemAttrs);
				if(!isset($examItem->quiz_item_id)){
					$examItem = new QuizExamItem();
					$examItem->attributes = $examItemAttrs;
					$examItem->save();//Assign item to exam if not assigned
				}
			}
		}
	}
	
	/**
	 * Get assigned exams of item
	 */
	public function getAssignedExamIds()
	{
		if(!$this->id) return array();//empty exam ids
		$query = "SELECT quiz_exam_id FROM tbl_quiz_exam_item WHERE quiz_item_id=".$this->id;
		$assignedExamIds = Yii::app()->db->createCommand($query)->queryColumn();
		return $assignedExamIds;
	}
	
	/**
	 * Get assigned exam array object
	 */
	public function getAssignedQuizExams()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
        $criteria->join = "INNER JOIN tbl_quiz_exam_item ON t.id= tbl_quiz_exam_item.quiz_exam_id";
        $criteria->condition = "tbl_quiz_exam_item.quiz_item_id=".$this->id;
        $assignedExams = QuizExam::model()->findAll($criteria);
        return $assignedExams;
	}
	
	/**
	 * Get assigned Topic of item
	 */
	public function getAssignedTopicId()
	{
		$assignedTopic = QuizItemTopic::model()->findByAttributes(array("quiz_item_id"=>$this->id));
		if($assignedTopic) return $assignedTopic->quiz_topic_id;
		return NULL;
	}
	
	/**
	 * Assign topic to item
	 */
	public function assignItemToTopic($topicId)
	{
		QuizItemTopic::model()->deleteAllByAttributes(array("quiz_item_id"=>$this->id));
		if($topicId!="" && $topicId!=null){
			$newItemTopic = new QuizItemTopic();
			$newItemTopic->attributes = array("quiz_item_id"=>$this->id, "quiz_topic_id"=>$topicId);
			$newItemTopic->save();//Save new item topic
		}
	}
	
	/**
	 * Count assigned exam of item
	 */
	public function countAssignedExam()
	{
		$count = QuizExamItem::model()->countByAttributes(array('quiz_item_id'=>$this->id));
		return $count;
	}
	
	/**
	 * Delete all connected QuizTopic, QuizExam & QuizItem History
	 */
	public function deleteAllConnectedQuiz()
	{
		//Delete all connected Exam 
        QuizExamItem::model()->deleteAllByAttributes(array('quiz_item_id'=>$this->id));
         //Delete all connected quizTopic 
        QuizItemTopic::model()->deleteAllByAttributes(array('quiz_item_id'=>$this->id));
        //Delete all connected quizItem history
        QuizItemHistory::model()->deleteAllByAttributes(array('quiz_item_id'=>$this->id));
	}
	
	/**
	 * Get item history of user 
	 */
	public function getItemHistoryByUser($userId)
    {
        $itemHistory = QuizItemHistory::model()->findByAttributes(array('student_id'=>$userId,'quiz_item_id'=>$this->id));
        return $itemHistory;
    }
    
    /**
     * Get sub item of parent item
     */
    public function getSubItems()
    {
    	$criteria = new CDbCriteria;
    	$criteria->compare('parent_id', $this->id);
    	$criteria->order = 'id ASC';
    	$subItems = $this->findAll($criteria);
    	return $subItems;
    }
    
    /**
     * Count subitem of quizItem
     */
    public function countSubItems()
    {
    	return $this->countByAttributes(array('parent_id'=>$this->id));
    }
}
