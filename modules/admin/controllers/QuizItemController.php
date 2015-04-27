<?php

class QuizItemController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','create','update', 'ajaxAddSubItem', 'ajaxDeleteSubItem'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('*'),
				'expression' => 'Yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->subPageTitle = 'Chi tiết câu hỏi';
		$this->loadMathJax = true;//Load math jax
		$model = $this->loadModel($id);
		$assignedQuizExams = $model->getAssignedQuizExams();//Get Assigned quiz exam
		$this->render('view',array(
			'model'=>$model,
			'assignedQuizExams'=>$assignedQuizExams,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->subPageTitle = 'Thêm câu hỏi mới';
		$model=new QuizItem;
		$this->loadMathJax = true;//Load math jax
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		$writingExams = QuizExam::model()->getWritingExams();
		//Load default subject as selected subjectId
		if(!$model->subject_id){
			if(isset(Yii::app()->session['writingSubjectId'])){
				$model->subject_id = Yii::app()->session['writingSubjectId'];
			}
		}
		if(isset($_POST['QuizItem']))
		{
			$model->attributes=$_POST['QuizItem'];
			if(isset($_POST['ItemAnswers'.$model->id])){
				$model->answers = json_encode($_POST['ItemAnswers'.$model->id]);
			}
			$model->correct_answer = Yii::app()->request->getPost('CorrectAnswer'.$model->id, 'A');;
			if($model->save()){
				Yii::app()->session['writingSubjectId'] = $model->subject_id;
				if(isset($_POST['writingExam'])){
					$model->assignItemToExams($_POST['writingExam'], $model->id);
				}
				$this->redirect(array('/admin/quizItem'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'subjects'=>$subjects,
			'writingExams'=>$writingExams,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->subPageTitle = 'Chỉnh sửa câu hỏi';
		$model=$this->loadModel($id);
		$this->loadMathJax = true;//Load math jax
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();//Subject to select
		$writingExams = QuizExam::model()->getWritingExams();//Writing exam
		$assignedQuizExams = $model->getAssignedQuizExams();//Get Assigned quiz exam
		if(isset($_POST['QuizItem']))
		{
			$model->attributes=$_POST['QuizItem'];
			if(isset($_POST['ItemAnswers'.$model->id])){
				$model->answers = json_encode($_POST['ItemAnswers'.$model->id]);
			}
			$model->correct_answer = Yii::app()->request->getPost('CorrectAnswer'.$model->id, 'A');
			if($model->save()){
				if(isset($_POST['writingExam'])){
					$model->assignItemToExams($_POST['writingExam'], $model->id);
				}
				if(isset($_POST['itemTopic'])){//Assign item to topic
					$model->assignItemToTopic($_POST['itemTopic']);
				}
				$subItems = $model->getSubItems();
				if(count($subItems)>0){
					foreach($subItems as $subItem){
						if(isset($_POST['ItemAnswers'.$model->id])){
							$subItem->answers = json_encode($_POST['ItemAnswers'.$subItem->id]);
						}
						$subItem->correct_answer = Yii::app()->request->getPost('CorrectAnswer'.$subItem->id, 'A');
						$subItem->content = Yii::app()->request->getPost('ItemContent'.$subItem->id, 'Nội dung câu hỏi con');
						$subItem->save();//Save subitem
					}
				}
				$this->redirect(array('/admin/quizItem'));
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'subjects'=>$subjects,
			'writingExams'=>$writingExams,
			'assignedQuizExams'=>$assignedQuizExams,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->subPageTitle = 'Hủy/Xóa câu hỏi';
		$model = $this->loadModel($id);//Load model
		if($model->status==QuizItem::STATUS_PENDING && $model->deleted_flag==0){
			$model->deleted_flag = 1;//Set deleted flag before delete
            $model->save();
            $this->redirect(array('/admin/quizItem'));
		}elseif($model->deleted_flag==1){
			$model->deleteAllConnectedQuiz();//Delete all connected
            $model->delete();//Delete this session
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/quizItem'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Câu hỏi trắc nghiệm';
		$this->loadJQuery = false;//Not load jquery
		$this->loadMathJax = true;//Load math jax
		$topicId = Yii::app()->request->getQuery('topic_id', null);
		$examId = Yii::app()->request->getQuery('exam_id', null);
		$model = new QuizItem('search('.$topicId.', '.$examId.')');		
		$model->unsetAttributes();  // clear any default values
		$writingExams = QuizExam::model()->getWritingExams();
		if(isset($_GET['QuizItem'])){
			$model->attributes=$_GET['QuizItem'];
		}
		$model->parent_id = 0;//Get only main quizItem
		if($examId==null){
			$model->getDbCriteria()->order = 'id DESC';
		}
		$renderParams = array('model'=>$model, 'writingExams'=>$writingExams,);
		$renderParams['topicId'] = $topicId;//Set param topic id
		$renderParams['examId'] = $examId;//Set param item id
		//Set render param QuizTopic
		if($topicId) $renderParams['quizTopic'] = QuizTopic::model()->findByPk($topicId);
		//Set render param quizExam
		if($examId)	$renderParams['quizExam'] = QuizExam::model()->findByPk($examId);
		$this->render('index', $renderParams);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return QuizItem the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=QuizItem::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param QuizItem $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='quiz-item-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Generate sub items to parent quizItem
	 */
	public function actionAjaxAddSubItem()
	{
		$parentId = $_REQUEST['parent_id'];
		$mainItem = $this->loadModel($parentId);
		$subItem = new QuizItem();
		//Set some field data of sub item
		$subItem->attributes = array(
			'parent_id' => $parentId,
			'subject_id' => $mainItem->subject_id,
			'content' => 'Nội dung câu hỏi con',
		);
		$subItem->save();
		echo $this->renderPartial('/quizItem/widget/itemAnswers', array('quizItem'=>$subItem));
	}
	
	/**
	 * Remove sub items from parent quizItem
	 */
	public function actionAjaxDeleteSubItem()
	{
		$itemId = $_REQUEST['item_id'];
		$item = $this->loadModel($itemId);
		if(isset($item->id) && $item->parent_id>0){
			$item->delete();//Delete sub item
		}
		$this->renderJSON(array('success'=>true));
	}
}
