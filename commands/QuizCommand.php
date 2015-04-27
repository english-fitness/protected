<?php

class QuizCommand extends CConsoleCommand
{
	const MAX_EXAM_PER_SUBJECT = 50;//Max number exam per subject/type(ontap, dethi)
		
	/**
	 * Get base Url config
	 */
	public function getBaseUrlConfig()
	{
		$baseUrlConfigs = array(
			'development' => "http://daykem11.local",
			'testing' => "http://dev.tutor.vn",
			'staging' => "http://daykem11.com",
			'production' => "https://beta.daykem123.vn",
		);
		$environment = getenv('ENV') ? getenv('ENV') : 'development';
		if(isset($baseUrlConfigs[$environment])) return $baseUrlConfigs[$environment];
		return null;
	}
	
	/**
	 * Quiz subject & folder config by environment
	 */
	public function getSubjectConfig()
	{
		//Config param in Quiz: subjectFolder => SubjectId
		$subjectConfigs = array(
			'development' => array('toan12'=>null, 'vatly12'=>5, 'hoa12'=>4, 'sinh12'=>6, 'anh12'=>7),
			'testing' => array('toan12'=>null, 'vatly12'=>null, 'hoa12'=>null, 'sinh12'=>null, 'anh12'=>null),
			'staging' => array('toan12'=>null, 'vatly12'=>20, 'hoa12'=>21, 'sinh12'=>22, 'anh12'=>4),
			'production' => array('toan12'=>null, 'vatly12'=>24, 'hoa12'=>25, 'sinh12'=>26, 'anh12'=>4),
		);		
		$environment = getenv('ENV') ? getenv('ENV') : 'development';
		if(isset($subjectConfigs[$environment])) return $subjectConfigs[$environment];
		return null;
	}
	
	/**
	 * Auto generate QuizTopic from quiz folder
	 */
    public function actionCreateTopics()
    {
    	$subjectConfig = $this->getSubjectConfig();//Subject config
    	$baseUrl = $this->getBaseUrlConfig();//Base url config
    	if($subjectConfig!=null && $baseUrl!=null){
			$clsQuiz = new ClsQuizSupport();
			foreach($subjectConfig as $folder=>$subjectId){
				if($subjectId!=null){
					$clsQuiz->generateQuizTopics($subjectId, $folder, $baseUrl);
				}
			}
    	}else{
    		echo 'CONFIG ERROR';
    	}
    }
    
	/**
	 * Auto generate QuizExam & QuizItem from quiz folder
	 */
    public function actionCreateExamItems()
    {
    	$subjectConfig = $this->getSubjectConfig();//Subject config
    	$baseUrl = $this->getBaseUrlConfig();//Base url config
    	if($subjectConfig!=null && $baseUrl!=null){
			$clsQuiz = new ClsQuizSupport();
			foreach($subjectConfig as $folder=>$subjectId){
				if($subjectId!=null){
					//Auto create exam & item with type: ontap
					$clsQuiz->generateExamItems($subjectId, QuizExam::TYPE_TRAINING, $folder, 30, $baseUrl, self::MAX_EXAM_PER_SUBJECT);
					//Auto create exam & item with type: dethi thidh
					$clsQuiz->generateExamItems($subjectId, QuizExam::TYPE_EXAMINING, $folder, 90, $baseUrl, self::MAX_EXAM_PER_SUBJECT);
				}
			}
    	}else{
    		echo 'CONFIG ERROR';
    	}
    }
    
    /**
     * Delete all Completed Exam & items
     */
    public function actionDeleteExamItems()
    {
    	$criteria = new CDbCriteria();
    	$criteria->compare('status', QuizExam::STATUS_COMPLETED);
    	$quizExams = QuizExam::model()->findAll($criteria);
    	if(count($quizExams)>0){
    		foreach($quizExams as $exam){
    			$examItems = $exam->getAssignedQuizItems();
    			if(count($examItems)>0){
    				foreach($examItems as $item){
    					$item->deleteAllConnectedQuiz();//Delete all connected of item
    					echo "Xoa cau hoi ".$item->id." trong de thi $exam->id \n";
            			$item->delete();//Delete this session            			
    				}
    			}
    			$exam->deleteAllConnectedQuiz();//Delete all connected of exam
    			echo "Xoa thanh cong de thi ma so ".$exam->id."\n";
            	$exam->delete();//Delete this exam
    		}
    	}
    }
    
	/**
     * Delete all approved QuizTopics
     */
    public function actionDeleteTopics()
    {
    	$criteria = new CDbCriteria();
    	$criteria->compare('status', QuizTopic::STATUS_APPROVED);
    	$quizTopics = QuizTopic::model()->findAll($criteria);
    	if(count($quizTopics)>0){
    		foreach($quizTopics as $topic){
    			$topic->deleteAllConnectedQuiz();//Delete all connected of topic
    			echo "Xoa thanh cong chu de ma so ".$topic->id."\n";
            	$topic->delete();//Delete this exam
    		}
    	}
    }
    
    /**
     * Convert & move all quiz topic from subject A to subject B
     */
    public function actionChangeSubject()
    {
    	$subjectChanges = array(
			'development' => array('vatly12'=>array(5, 9), 'hoa12'=>array(4, 10), 'sinh12'=>array(6, 11), 'anh12'=>array(7, 12)),
			'staging' => array('vatly12'=>array(20, 25), 'hoa12'=>array(21, 26), 'sinh12'=>array(22, 27), 'anh12'=>array(4, 23)),
			'production' => array('vatly12'=>array(24, 32), 'hoa12'=>array(25, 33), 'sinh12'=>array(26, 34), 'anh12'=>array(4, 30)),
		);
		$environment = getenv('ENV') ? getenv('ENV') : 'development';
		if(isset($subjectChanges[$environment])){
			foreach($subjectChanges[$environment] as $key=>$subject){
				$criteria = new CDbCriteria();
				$criteria->compare('subject_id', $subject[0]);
				$attributes['subject_id'] = $subject[1];//Move to subject id
				//Convert & move QuizTopic to new Subject
				QuizTopic::model()->updateAll($attributes, $criteria);
				//Convert & move QuizExam to new Subject
				QuizExam::model()->updateAll($attributes, $criteria);
				//Convert & move QuizItem to new Subject
				QuizItem::model()->updateAll($attributes, $criteria);
				echo "Chuyen chu de, de thi, cau hoi tu subject ".$subject[0]." subject ".$subject[1]."\n";
			}
		}
    }

}