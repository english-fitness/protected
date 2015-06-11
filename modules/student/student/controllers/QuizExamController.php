<?php

class QuizExamController extends Controller
{
	//QuizIndex
	public function actionIndex()
	{
		$this->subPageTitle = "Luyện tập trắc nghiệm";
		$subjectId = null;//Current subject id
		if(isset($_GET['subjectId'])){
			$_SESSION['quizCurrentSubject'] = $_GET['subjectId'];
		}
		if(isset($_SESSION['quizCurrentSubject'])){
			$subjectId = $_SESSION['quizCurrentSubject'];
		}
        $enabledExams = QuizExam::model()->getEnabledQuizExams($subjectId, 10);
        $this->render('index', array(
        	'quizExams' => $enabledExams['quizExams'],
        	'pages' => $enabledExams['pages'],
        ));
	}
	
	//QuizViewExam
    public function actionView($id)
    {
    	$this->subPageTitle = "Chi tiết đề trắc nghiệm";
        $this->loadMathJax = true;
        $examAttrs = array('status'=>QuizExam::STATUS_ENABLED,'id'=>$id);
        if(Yii::app()->user->role!=User::ROLE_STUDENT){
        	unset($examAttrs['status']);
        }
        $exam = QuizExam::model()->findByAttributes($examAttrs);
        $this->subPageTitle = $exam->name;
        $exam->setExamHistory(Yii::app()->user->id);
        $renderParams = array(
        	'exam' => $exam,
        	'items' => $exam->getAssignedQuizItems(),
        	'history' => $exam->getExamHistory(),
        );
        $this->render('view',$renderParams);
    }

	//ResetExamHistory
    public function actionRestart($id)
    {
        $uid = Yii::app()->user->id;
        $history = QuizExamHistory::model()->findByAttributes(array('student_id'=>$uid,'quiz_exam_id'=>$id));
        if(isset($history->quiz_exam_id)){
        	 $history->resetHistory();//Reset history
        }
        $this->redirect(array('/student/quizExam/view/id/'.$id));
    }

    //UpdateExamHistory
    public function actionUpdateExamHistory($id)
    {
        //Update & save exam history
        if(isset($_POST['submissions'])){
            $uid = Yii::app()->user->id;
            $exam = QuizExam::model()->findByPk($id);
            $exam->setExamHistory($uid, QuizExamHistory::STATUS_ENDED);
            $answeredItems = Yii::app()->request->getPost('answer', array());
            $totalCorrect = 0;
            if(count($answeredItems)>0){
                $totalCorrect = QuizItemHistory::model()->saveWorkedExamItems($uid, $id,  $answeredItems);
            }
            $exam->submissionsExam($totalCorrect);
            Settings::shareFacebook(Settings::SHARE_QUIZ,$exam);
        }
        $this->redirect(array('/student/quizExam/view/id/'.$id));
    }

    //ajaxSaveItem
    public function actionAjaxSaveItems($id)
    {
        //Save item in doing exam time
        $studentId = Yii::app()->user->id;
        if(isset($_POST['answer'])){
            $answeredItems = Yii::app()->request->getPost('answer', array());
            if(count($answeredItems)>0){
                QuizItemHistory::model()->saveWorkedExamItems($studentId, $id,  $answeredItems);
            }
        }
    }
   
}