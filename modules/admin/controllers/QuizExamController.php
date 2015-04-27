<?php

class QuizExamController extends Controller
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
				'actions'=>array('index','view','create','update','preview'),
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
		$this->subPageTitle = 'Chi tiết đề thi';
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 * Preview quiz exam
	 */
	public function actionPreview($id)
	{
		$this->subPageTitle = 'Xem trước đề thi';
		$this->loadMathJax = true;
		$model = $this->loadModel($id);//Load model
		$assignedItems = $model->getAssignedQuizItems();//Assigned item
		if($model->isActivatedWritingExam())
		{
			if(isset($_POST['itemOrderIndex'])){
				$newOrderArrs = $_POST['itemOrderIndex'];
				if(is_array($newOrderArrs) && count($newOrderArrs)>0){
					$checkResetIndex = false;//Reset index
					if(isset($_POST['checkResetIndex']) && $_POST['checkResetIndex']==1){
						$checkResetIndex = true;
					}
					$model->updateNewOrderIndex($newOrderArrs, $checkResetIndex);
					$this->redirect(array('/admin/quizExam/preview/id/'.$id));
				}
			}elseif(isset($_GET['unasign_item_id'])){
				$unassignAttrs = array('quiz_exam_id'=>$model->id, 'quiz_item_id'=>$_GET['unasign_item_id']);
				//UnAssign an item in exam
				QuizExamItem::model()->deleteAllByAttributes($unassignAttrs);
				$this->redirect(array('/admin/quizExam/preview/id/'.$id));
			}
		}
		$this->render('preview',array(
			'model'=>$model,
			'assignedItems'=>$assignedItems,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->subPageTitle = 'Thêm đề thi mới';
		$model=new QuizExam;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		if(isset($_POST['QuizExam']))
		{
			$model->attributes=$_POST['QuizExam'];
			if($model->save()){
				if(isset($_POST['chkWriting']) && $_POST['chkWriting']==1){
					Yii::app()->session['writingExamId'] = $model->id;
				}
				$this->redirect(array('/admin/quizExam'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'subjects'=>$subjects,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->subPageTitle = 'Chỉnh sửa đề thi';
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		if(isset($_POST['QuizExam']))
		{
			$model->attributes=$_POST['QuizExam'];
			if($model->save()){
				if($model->isActivatedWritingExam()){
					if(!isset($_POST['chkWriting'])){
						Yii::app()->session['writingExamId'] = false;
					}
				}elseif(isset($_POST['chkWriting']) && $_POST['chkWriting']==1){
					Yii::app()->session['writingExamId'] = $model->id;
				}
				if(isset($_POST['examTopic'])){//Assign exam to topic
					$model->assignExamToTopic($_POST['examTopic']);
				}
				$this->redirect(array('/admin/quizExam'));
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'subjects'=>$subjects,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->subPageTitle = 'Hủy/Xóa đề thi';
		$model = $this->loadModel($id);//Load model
		if($model->status==QuizExam::STATUS_PENDING && $model->deleted_flag==0){
			$model->deleted_flag = 1;//Set deleted flag before delete
            $model->save();
            $this->redirect(array('/admin/quizExam'));
		}elseif($model->deleted_flag==1){
			$model->deleteAllConnectedQuiz();//Delete all connected
            $model->delete();//Delete this exam
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/quizExam'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Đề thi trắc nghiệm';
		$this->loadJQuery = false;//Not load jquery
		$topicId = Yii::app()->request->getQuery('topic_id', null);
		$itemId = Yii::app()->request->getQuery('item_id', null);
		$model=new QuizExam('search('.$topicId.', '.$itemId.')');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['QuizExam'])){
			$model->attributes=$_GET['QuizExam'];
		}
		$model->getDbCriteria()->order = 'id DESC';
		$renderParams = array('model'=>$model);
		$renderParams['topicId'] = $topicId;//Set param topic id
		$renderParams['itemId'] = $itemId;//Set param item id
		if($topicId) $renderParams['quizTopic'] = QuizTopic::model()->findByPk($topicId);
		if($itemId)	$renderParams['quizItem'] = QuizItem::model()->findByPk($itemId);
		$this->render('index',$renderParams);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return QuizExam the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=QuizExam::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param QuizExam $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='quiz-exam-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
