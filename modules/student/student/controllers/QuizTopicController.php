<?php
class QuizTopicController extends Controller
{
	//QuizIndex
	public function actionIndex()
	{
		$this->subPageTitle = "Ôn tập lý thuyết";
		$attributes = array('parent_id'=>0, 'status'=>QuizTopic::STATUS_APPROVED, 'subject_id'=>"");//Get parent topic
		if(isset($_GET['subjectId'])){
			$_SESSION['quizCurrentSubject'] = $_GET['subjectId'];
		}
		if(isset($_SESSION['quizCurrentSubject'])){
			$attributes['subject_id'] = $_SESSION['quizCurrentSubject'];
		}
        $quizTopics = QuizTopic::model()->findAllByAttributes($attributes);
        $this->render('index', array('quizTopics'=>$quizTopics));
	}
	
	//QuizViewTopic
    public function actionView($id)
    {
    	$this->subPageTitle = "Chi tiết chủ đề";
        if(!$id) { $this->redirect(array('index')); }
        $this->loadMathJax = true;
        $topic = QuizTopic::model()->findByAttributes(array('status'=>QuizTopic::STATUS_APPROVED,'id'=>$id));
    	if($topic->parent_id==0){
			$_SESSION['quizParentTopic'] = $topic->id;
		}
        $params = array(
        	'topic' => $topic,
        	'examItems' => $topic->getAssignedQuizItems(),
        );
        $this->render('view',$params);
    }

}