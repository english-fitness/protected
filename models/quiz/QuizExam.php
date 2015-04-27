<?php

/**
 * This is the model class for table "tbl_quiz_exam".
 *
 * The followings are the available columns in table 'tbl_quiz_exam':
 * @property integer $id
 * @property string $name
 * @property integer $quiz_topic_id
 * @property integer $duration
 * @property integer $deleted_flag
 */
class QuizExam extends CActiveRecord
{
	//Const for status of QuizExam
    const STATUS_PENDING = 0;//Pending status
    const STATUS_WRITING = 1;//Writing status
    const STATUS_COMPLETED = 2;//Completed status
    const STATUS_APPROVED = 3;//Approved status
    const STATUS_ENABLED = 4;//Enabled status
    //Const for level of QuizExam
    const LEVEL_AVERAGE = 0;//Level average
    const LEVEL_GOOD = 1;//Level good
    const LEVEL_EXCELLENT = 2;//Level Excellent
    //Const for type of QuizExam
    const TYPE_TRAINING = 0;//Type training
    const TYPE_EXAMINING = 1;//Type examining
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_quiz_exam';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('name, subject_id', 'required'),
			array('duration, deleted_flag', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>256),
			array('type, level, status,created_user_id,created_date,modified_date,modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, subject_id, duration, deleted_flag, type, level, status,created_user_id,created_date,modified_date,modified_user_id', 'safe', 'on'=>'search'),
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
			'id' => 'Mã đề thi',
			'name' => 'Tên đề thi',
			'subject_id' => 'Môn học',
			'type' => 'Kiểu đề thi',
			'level' => 'Độ khó',
			'status' => 'Trạng thái',
			'duration' => 'Thời lượng',
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
	public function search($topicId=null, $itemId=null)
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('type',$this->type);
		$criteria->compare('level',$this->level);
		$criteria->compare('status',$this->status);
		$criteria->compare('duration',$this->duration);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		if($topicId!==null){
			$criteria->addCondition("id IN (SELECT quiz_exam_id FROM tbl_quiz_exam_topic WHERE quiz_topic_id=$topicId)");
		}
		if($itemId!==null){
			$criteria->addCondition("id IN (SELECT quiz_exam_id FROM tbl_quiz_exam_item WHERE quiz_item_id=$itemId)");
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
     * @return TblQuizExam the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
	//After save QuizExam
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete QuizExam
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
			self::STATUS_WRITING => 'Đề đang soạn',
			self::STATUS_COMPLETED => 'Đã soạn xong',
			self::STATUS_APPROVED => 'Đề đã duyệt',
			self::STATUS_ENABLED => 'Đã kích hoạt',
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
	
	//Display Type options
	public function typeOptions($type=null)
	{
		$typeOptions = array(
			self::TYPE_TRAINING => 'Đề ôn tập',
			self::TYPE_EXAMINING => 'Đề thi',
		);
		if($type==null){
			return $typeOptions;
		}elseif(isset($typeOptions[$type])){
			return $typeOptions[$type];
		}
		return null;
	}
	
	/**
	 * Duration options
	 */
	public function durationOptions()
	{
		return array(
			15=>'15 phút',
			30=>'30 phút',
			45=>'45 phút',
			60=>'60 phút',
			90=>'90 phút',
			120=>'120 phút',
			150=>'150 phút',
			180=>'180 phút',
		);
	}

    /**
     * Get items
     */
    public function getItems()
    {
        return QuizExamItem::model()->findAllByAttributes(array("quiz_exam_id"=>$this->id));
    }
    
    /**
     * Get Exam history
     */
    public function getExamHistory()
    {
    	$attributes = array(
			'student_id'=>Yii::app()->user->id,
			'quiz_exam_id'=>$this->id,
		);
    	return QuizExamHistory::model()->findByAttributes($attributes);
    }

    /*
     * Một người dùng vào làm bài
     * Nếu như chưa tạo lịch sử làm bài thì sẽ tạo lịch sử làm bài còn
     * nếu đã tạo thì cho phép làm bài tính thời gian khởi tạo trong lịch sử
     * */
    public function setExamHistory($uid, $status=0)
    {
    	$examHistory = $this->getExamHistory();
    	if(!isset($examHistory->quiz_exam_id)){
    		$quizExamHistory = new QuizExamHistory();
    		$quizExamHistory->attributes = array(
    			'student_id' => $uid,
    			'status' => $status,
    			'quiz_exam_id' => $this->id,
    		);
    		$quizExamHistory->save();
    	}
    }

    /**
     * Save & submit worked exam
     * @param $totalCorrect
     */
    public function submissionsExam($totalCorrect)
    {
        $examHistory = $this->getExamHistory();
        $examHistory->actual_end = date('Y-m-d H:i:s');
        $examHistory->status = QuizExamHistory::STATUS_ENDED;
        $examHistory->correct_percent = $totalCorrect;
        $examHistory->save();
    }

    /**
     * Get remaining time of working exam
     */
    public function getRemainingTime()
    {
    	$examHistory = $this->getExamHistory();
    	$planEndTime = strtotime($examHistory->actual_start) + $this->duration*60;
    	$remainingTime = $planEndTime - time('now');
    	if($remainingTime<0) $remainingTime = 0;
    	return $remainingTime;
    }

    /**
     * Get writing exam to assign items
     */
    public function getWritingExams()
    {
    	$params = array('status'=>self::STATUS_WRITING, 'deleted_flag'=>0);
    	$writingExams = QuizExam::model()->findAllByAttributes($params);
    	return $writingExams;
    }
    
	/**
	 * Count assigned item of quizExam
	 */
	public function countAssignedItem()
	{
		$criteria = new CDbCriteria;
		$query = "SELECT quiz_item_id FROM tbl_quiz_exam_item WHERE quiz_exam_id=".$this->id;
		$examItemIds = Yii::app()->db->createCommand($query)->queryColumn();
		if(count($examItemIds)==0) return 0;//Return zero
		$whereItemId = "(".implode(",", $examItemIds).")";
		$criteria->addCondition("id IN ".$whereItemId." OR parent_id IN ".$whereItemId);
		return QuizItem::model()->count($criteria);
	}
	
	/**
	 * Get assigned items in exam
	 */
	public function getAssignedQuizItems()
	{
		$criteria = new CDbCriteria;
		$criteria->select = 't.*';
        $criteria->join = "INNER JOIN tbl_quiz_exam_item ON t.id= tbl_quiz_exam_item.quiz_item_id";
        $criteria->condition = "tbl_quiz_exam_item.quiz_exam_id=".$this->id;
        $criteria->order = "item_id_order ASC, id ASC";
        $quizItems = QuizItem::model()->findAll($criteria);
        $assignedItems = array();
        if(count($quizItems)>0){//Get parent item
        	foreach($quizItems as $quizItem){
        		$assignedItems[] = $quizItem;
        		$subItems = $quizItem->getSubItems();
        		if(count($subItems)>0){//Get sub item
        			foreach($subItems as $subItem){
        				$assignedItems[] = $subItem;
        			}
        		}
        	}
        }
        return $assignedItems;
	}
	
	/**
	 * Get assigned Topic of exam
	 */
	public function getAssignedTopicId()
	{
		$assignedTopic = QuizExamTopic::model()->findByAttributes(array("quiz_exam_id"=>$this->id));
		if($assignedTopic) return $assignedTopic->quiz_topic_id;
		return NULL;
	}
	
	/**
	 * Assign topic to exam
	 */
	public function assignExamToTopic($topicId)
	{
		QuizExamTopic::model()->deleteAllByAttributes(array("quiz_exam_id"=>$this->id));
		if($topicId!="" && $topicId!=null){
			$newExamTopic = new QuizExamTopic();
			$newExamTopic->attributes = array("quiz_exam_id"=>$this->id, "quiz_topic_id"=>$topicId);
			$newExamTopic->save();//Save new item topic
		}
	}
	
	/**
	 * Check writing exam by user
	 */
	public function isActivatedWritingExam()
	{
		$writingExamId = Yii::app()->session['writingExamId'];
		if(isset($writingExamId) && $this->id==$writingExamId 
			&& $this->status==self::STATUS_WRITING)
		{
			return true;
		}
		return false;
	}
	
	/**
	 * Get & display quiz exam to student
	 */
	public function getEnabledQuizExams($subjectId=null, $pageSize=10, $limit=5)
	{
        $criteria=new CDbCriteria();
        if($subjectId!==null){
        	$criteria->compare('subject_id', $subjectId);
        }
        $criteria->compare('deleted_flag', 0);
        $criteria->addCondition('status='.self::STATUS_ENABLED.' AND type='.self::TYPE_EXAMINING);
        $criteria->order = "name ASC";
        $count = $this->count($criteria);
        $pages = new CPagination($count);
        // results per page
        $pages->pageSize = $pageSize;
        $pages->applyLimit($criteria);
        $quizExams = $this->findAll($criteria);
        return array(
        	'quizExams' => $quizExams,
        	'pages' => $pages,
        );
	}
	
	/**
	 * Update item order as new index
	 */
	public function updateNewOrderIndex($newOrderArrs, $checkResetIndex=false)
	{
		$criteria = new CDbCriteria;
		$criteria->compare('quiz_exam_id', $this->id);
		$whereItemIdIn = "(".implode(',', $newOrderArrs).")";
		$criteria->addCondition("quiz_item_id IN ".$whereItemIdIn);
		$examItems = QuizExamItem::model()->findAll($criteria);
		foreach($examItems as $key=>$examItem){
			if($checkResetIndex){
				$examItem->item_id_order = 0;
			}else{
				$examItem->item_id_order = array_search($examItem->quiz_item_id, $newOrderArrs);
			}
			$examItem->save();//Store new order index
		}
	}
	
	/**
	 * Delete all connected QuizTopic, QuizExam History & QuizItem
	 */
	public function deleteAllConnectedQuiz()
	{
		//Delete all connected QuizItem 
        QuizExamItem::model()->deleteAllByAttributes(array('quiz_exam_id'=>$this->id));
         //Delete all connected quizTopic 
        QuizExamTopic::model()->deleteAllByAttributes(array('quiz_exam_id'=>$this->id));
        //Delete all connected QuizExam history
        QuizExamHistory::model()->deleteAllByAttributes(array('quiz_exam_id'=>$this->id));
	}
	
}
