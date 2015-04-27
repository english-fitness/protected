<?php

class QuizController extends Controller
{
	//QuizIndex
	public function actionIndex()
	{
		$this->subPageTitle = "Ôn tập, kiểm tra kiến thức";
		$userId = Yii::app()->user->id;
        $examHistory = QuizExamHistory::model()->getExamHistoryByUser($userId);
        $this->render('index',
        	array('examHistory'=>$examHistory)
        );
	}
   
}