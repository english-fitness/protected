<?php

/**
 * This is the model class for table "tbl_quiz_exam_history".
 *
 * The followings are the available columns in table 'tbl_quiz_exam_history':
 * @property integer $student_id
 * @property integer $quiz_exam_id
 * @property double $correct_percent
 * @property string $created_date
 * @property string $modified_date
 */
class QuizExamHistory extends CActiveRecord
{
	//Const for status of QuizExamHistory
	const STATUS_PENDING = 0;//Pending status
    const STATUS_WORKING = 1;//Working status
    const STATUS_ENDED = 2;//Ended status
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_quiz_exam_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_id, quiz_exam_id', 'required'),
			array('student_id, quiz_exam_id, status', 'numerical', 'integerOnly'=>true),
			array('correct_percent', 'numerical'),
			array('modified_date, status, actual_start, actual_end', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('student_id, quiz_exam_id, correct_percent, created_date, modified_date, status', 'safe', 'on'=>'search'),
            array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
			array('modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update'),
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
			'student_id' => 'Học sinh',
			'quiz_exam_id' => 'Đề thi',
			'correct_percent' => 'Phần trăm(%) đúng',
			'status' => 'Trạng thái làm bài',
			'actual_start' => 'Thời gian bắt đầu',
			'actual_end' => 'Thời gian kết thúc',
			'created_date' => 'Ngày tạo',
			'modified_date' => 'Ngày sửa',
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
		$criteria->compare('quiz_exam_id',$this->quiz_exam_id);
		$criteria->compare('correct_percent',$this->correct_percent);
		$criteria->compare('status',$this->status);
		$criteria->compare('actual_start',$this->actual_start,true);
		$criteria->compare('actual_end',$this->actual_end,true);
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('modified_date',$this->modified_date,true);
		$criteria->addCondition("quiz_exam_id IN (SELECT id FROM tbl_quiz_exam WHERE deleted_flag=0)");
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
	 * @return TblQuizExamHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


    /*
     * liệt kê danh sách lịch sử theo người dùng*/
    public function getExamHistoryByUser($uid, $pageSize=10)
    {
        if(!$uid) { return false; }
        $criteria = new CDbCriteria();
        $criteria->compare('student_id', $uid);
        $criteria->addCondition('(status='.self::STATUS_WORKING.' OR status='.self::STATUS_ENDED.')');
        $count = $this->count($criteria);
        $pages = new CPagination($count);
        // results per page
        $pages->pageSize = $pageSize;
        $pages->applyLimit($criteria);
        $models = $this->findAll($criteria);
        return array('models' => $models,'pages' => $pages);
    }

    /**
     * Reset history quiz item history
     */
    public function resetHistory()
    {
    	$this->attributes = array(
    		'status' => 0,
    		'actual_start' => date('Y-m-d H:i:s'),
    		'actual_end' => null,
    	);
    	if($this->status!=1) $this->status = 1;//Start working
    	$this->save();//Save then delete history items
        QuizItemHistory::model()->deleteHistoryItems($this->student_id, $this->quiz_exam_id);
    }
    
    /**
     * Display quiz exam score
     */
    public function displayScore($isDecimal=false)
    {
    	$totalScore = $this->exam->countAssignedItem();
    	if($this->actual_end){
	    	if($isDecimal){    		
	    		return $this->correct_percent*(10/$totalScore);
	    	}
	    	return $this->correct_percent.'/'.$totalScore;
    	}
    	return 0;
    }
    
	/**
	 * Get worked user of quiz exam
	 */
	public function getExamUser($userViewLink=null){
		$user = User::model()->findByPk($this->student_id);
		if(isset($user->id)){
			if($userViewLink!=null){
				$link = '<a href="'.$userViewLink.'/'.$user->id.'">'.$user->fullName().'</a>';
				return $link;
			}
			return $user->fullName();
		}
        return NULL;
	}

}