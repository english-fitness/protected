<?php

/**
 * This is the model class for table "tbl_quiz_topic".
 *
 * The followings are the available columns in table 'tbl_quiz_topic':
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property string $content
 * @property integer $deleted_flag
 */
class QuizTopic extends CActiveRecord
{
	//Const for status of QuizTopic
    const STATUS_PENDING = 0;//Pending status
    const STATUS_APPROVED = 1;//Approved status
	 
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_quiz_topic';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        $modelRules = array(
            array('subject_id, name', 'required'),
            array('subject_id, parent_id, deleted_flag, status', 'numerical', 'integerOnly'=>true),
            array('name', 'length', 'max'=>256),
            array('content, status, parent_path,created_user_id,created_date,modified_date,modified_user_id', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, subject_id, name, parent_id, content, deleted_flag, status, parent_path,created_user_id,created_date,modified_date,modified_user_id', 'safe', 'on'=>'search'),
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
            'id' => 'Mã chủ đề',
            'subject_id' => 'Môn học',
            'name' => 'Tên chủ đề',
            'parent_id' => 'Chủ đề cha',
            'content' => 'Nội dung',
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
	public function search()
	{
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('subject_id',$this->subject_id);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('parent_id',$this->parent_id);
        $criteria->compare('status',$this->status);
        $criteria->compare('content',$this->content,true);
        $criteria->compare('deleted_flag',$this->deleted_flag);

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
	 * @return TblQuizTopic the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	//After save QuizTopic
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}
	
	//After delete QuizTopic
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

    /* getItemsTopic */
    public function getExamsTopic()
    {
        return  QuizExamTopic::model()->findAllByAttributes(array("quiz_topic_id"=>$this->id));
    }

    /* getTopicNext */
    public function getTopicSubset($status=1, $parentId=null)
    {
    	$attributes = array("status"=>$status, "parent_id"=>$this->id);
    	if($parentId!=null) $attributes['parent_id'] = $parentId;
        return $this->findAllByAttributes($attributes);
    }
    
    /**
     * Display breadcrumbs by parent path
     */
    public function displayBreadcrumbs($baseTopicUrl, $separator=">", $rootName="Home", $parenTopicClass="parentTopic", $lengthName=null)
    {
    	//Set root of bread crumbs
    	$displayBreadcrumbs = CHtml::link($rootName, Yii::app()->createUrl($baseTopicUrl."0"), array('class'=>$parenTopicClass, 'title'=>$rootName));
   		if($this->parent_id>0){
	   		$parentIds = explode('/', $this->parent_path);//Parse parent path
	   		foreach($parentIds as $parentId){
	   			$parent = QuizTopic::model()->findByPk($parentId);
	   			if($parentId>0 && isset($parent->id)){
	   				$name = preg_replace('/<span[^>]*>.*?<\/span>/i', '', $parent->name);
	   				if($lengthName!=null && $lengthName>0){
	   					$name = Common::truncate($parent->name, $lengthName);
	   				}
	   				$displayBreadcrumbs .= $separator.CHtml::link($name, Yii::app()->createUrl($baseTopicUrl.$parent->id), array('class'=>$parenTopicClass, 'title'=>$parent->name));
	   			}
	   		}
   		}
   		//Last bread crumbs, not input link
   		$topicName = preg_replace('/<span[^>]*>.*?<\/span>/i', '', $this->name);
   		if($this->name<>"")	$displayBreadcrumbs .= $separator.$topicName;
   		return $displayBreadcrumbs;
    }
    
    /**
     * Count children of topic
     */
    public function countChildren()
    {
    	$countChildren = QuizTopic::model()->countByAttributes(array('parent_id'=>$this->id));
    	return $countChildren;
    }
    
    /**
     * Count QuizExam in this topic
     */
    public function countQuizExam()
    {
    	$countQuizExam = QuizExamTopic::model()->countByAttributes(array('quiz_topic_id'=>$this->id));
    	return $countQuizExam;
    }
    
	/**
     * Count QuizExam in this topic
     */
    public function countQuizItem()
    {
    	$countQuizItem = QuizItemTopic::model()->countByAttributes(array('quiz_topic_id'=>$this->id));
    	return $countQuizItem;
    }
    
    /**
     * Get & display content of topic
     */
    public function displayTopicContent()
    {
    	if(trim($this->content)!=""){
    		return $this->content;
    	}elseif($this->countChildren()==1){
    		$firstTopic = $this->findByAttributes(array('parent_id'=>$this->id, 'status'=>self::STATUS_APPROVED, 'deleted_flag'=>0));
    		if(isset($firstTopic->id) && trim($firstTopic->content)!=""){
    			return $firstTopic->content;
    		}
    	}
    	return NULL;
    }
    
    /**
     * Generate quizTopic to array(fill to dropdownlist...)
     */
    public function generateTopicsBySubject($subjectId, $subPrevStr="-", $firstSubject=true, $parentTopicId=0)
    {
    	$quizTopicArrs = array();//Quiz topic array
    	if($firstSubject){
    		$quizTopicArrs = array(""=>"---Chọn chủ đề---");//Topic as array(root->sub...)
    	}
    	$subTopics = $this->findAllByAttributes(array('subject_id'=>$subjectId, 'parent_id'=>$parentTopicId));
    	if($subTopics){
    		foreach($subTopics as $topic){
    			$quizTopicArrs[$topic->id] = $topic->name;
    			$sub1Topics = $this->findAllByAttributes(array('subject_id'=>$subjectId, 'parent_id'=>$topic->id));
    			if($sub1Topics){
    				foreach($sub1Topics as $topic1){
    					$quizTopicArrs[$topic1->id] = $subPrevStr.$topic1->name;
    					$sub2Topics = $this->findAllByAttributes(array('subject_id'=>$subjectId, 'parent_id'=>$topic1->id));
    					if($sub2Topics){
    						foreach($sub2Topics as $topic2){
    							$quizTopicArrs[$topic2->id] = $subPrevStr.$subPrevStr.$topic2->name;
    						}
    					}
    				}
    			}
    		}
    	}
    	return $quizTopicArrs;
    }


    /**
     * Get assigned items in exam
     */
    public function getAssignedQuizItems()
    {
        $criteria = new CDbCriteria;
        $criteria->select = 't.*';
        $criteria->join = "INNER JOIN tbl_quiz_exam_topic ON t.id= tbl_quiz_exam_topic.quiz_exam_id";
        $criteria->condition = "tbl_quiz_exam_topic.quiz_topic_id=".$this->id;
        $criteria->order = "id ASC";
        $assignedItems = QuizExam::model()->findAll($criteria);
        return $assignedItems;
    }

    /**
     * Get class to filter topic in student quiz
     */
    public function getAvailableFilterClasses()
    {
    	$classes = Classes::model()->getAll(true);
    	$availableClasses = array(""=>"Chọn lớp...");//Available class
    	foreach($classes as $class){
    		$countAvailableTopic = $class->countApprovedParentTopic();
    		if($countAvailableTopic>0){
    			$availableClasses[$class->id] = $class->name;
    		}
    	}
    	return $availableClasses;
    }
    
	/**
	 * Delete all connected QuizExam & QuizItem
	 */
	public function deleteAllConnectedQuiz()
	{
        //Delete all connected quizTopic 
        QuizExamTopic::model()->deleteAllByAttributes(array('quiz_topic_id'=>$this->id));
        //Delete all connected QuizExam history
        QuizItemTopic::model()->deleteAllByAttributes(array('quiz_topic_id'=>$this->id));
	}
}
