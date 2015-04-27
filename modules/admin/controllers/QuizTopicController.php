<?php

class QuizTopicController extends Controller
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
				'actions'=>array('index','view','create','update'),
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
		$this->subPageTitle = 'Chi tiết chủ đề';
		$this->loadMathJax = true;
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->subPageTitle = 'Tạo chủ đề con';
		$model=new QuizTopic;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		
		if(isset($_POST['QuizTopic']))
		{
			$model->attributes=$_POST['QuizTopic'];
			$model->status = QuizTopic::STATUS_PENDING;
			if(!isset($model->parent_path)) $model->parent_path = 0;
			if($model->save()){
				$this->redirect(array('/admin/quizTopic?parent_id='.$model->parent_id));
			}
		}
		if(isset($_GET['parent_id']) && $_GET['parent_id']>0){
			$model->parent_id = $_GET['parent_id'];
			$parentTopic = $this->loadModel($model->parent_id);
			$model->subject_id = $parentTopic->subject_id;
			$model->parent_path = $parentTopic->parent_path.'/'.$parentTopic->id;
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
		$this->subPageTitle = 'Chỉnh sửa chủ đề';
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		if(isset($_POST['QuizTopic']))
		{
			$model->attributes=$_POST['QuizTopic'];
			if($model->save()){
				$this->redirect(array('/admin/quizTopic?parent_id='.$model->parent_id));
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
		$this->subPageTitle = 'Hủy/Xóa chủ đề';
		$model = $this->loadModel($id);//Load model
		$parentId = $model->parent_id;
		if($model->status==QuizTopic::STATUS_PENDING && $model->deleted_flag==0){
			$model->deleted_flag = 1;//Set deleted flag before delete
            $model->save();
            $this->redirect(array('/admin/quizTopic?parent_id='.$parentId));
		}elseif($model->deleted_flag==1){
			$model->deleteAllConnectedQuiz();//Delete all connected
            $model->delete();//Delete this topic
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/quizTopic?parent_id='.$parentId));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Chủ đề môn học';
		$this->loadJQuery = false;//Not load jquery
		$model=new QuizTopic('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['QuizTopic'])){
			$model->attributes=$_GET['QuizTopic'];
		}
		$renderParams = array();//Render params
		if(isset($_GET['parent_id'])){
			$renderParams['currentTopic'] = QuizTopic::model()->findByPk($_GET['parent_id']);
			$model->parent_id = $_GET['parent_id'];
		}else{
			$model->parent_id = 0;
		}
		$renderParams['model'] = $model;//Set model param
		$this->render('index',$renderParams);
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return QuizTopic the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=QuizTopic::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param QuizTopic $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='quiz-topic-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
