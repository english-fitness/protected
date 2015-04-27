<?php

class PresetCourseController extends Controller
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
				'actions'=>array('index','view','create','update','ajaxCreateCourse'),
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
		$this->subPageTitle = "Chi tiết khóa học tạo sẵn";
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
		$this->subPageTitle = "Thêm mới khóa học tạo sẵn";
		$model=new PresetCourse;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		$subjectId = Yii::app()->request->getQuery('subject_id', $model->subject_id);
		$availableTeachers = array();//Init available subject
		if($subjectId!="" && $subjectId!=null){
			$availableTeachers = Teacher::model()->availableTeachers($subjectId);
			$model->subject_id = $subjectId;//Reset subject id
		}
		if(isset($_POST['PresetCourse']))
		{
			$model->attributes=$_POST['PresetCourse'];
			$model->created_user_id = Yii::app()->user->id;//User id
			if(isset($_POST['priceRule'])){
				$model->price_rules = json_encode($_POST['priceRule']);
			}
			if($model->save()){
				$this->redirect(array('/admin/presetCourse'));
			}
		}
		$this->render('create',array(
			'model'=>$model,
			'subjects'=>$subjects,
			'availableTeachers'=>$availableTeachers,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->subPageTitle = "Chỉnh sửa khóa học tạo sẵn";
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		$subjectId = Yii::app()->request->getQuery('subject_id', $model->subject_id);
		$availableTeachers = array();//Init available subject
		if($subjectId!="" && $subjectId!=null){
			$availableTeachers = Teacher::model()->availableTeachers($subjectId);
			$model->subject_id = $subjectId;//Reset subject id
		}
		if(isset($_POST['PresetCourse']))
		{
			$model->attributes=$_POST['PresetCourse'];
			if(isset($_POST['priceRule'])){
				$model->price_rules = json_encode($_POST['priceRule']);
			}
			if($model->save()){
				$this->redirect(array('/admin/presetCourse'));
			}
		}
		$this->render('update',array(
			'model'=>$model,
			'subjects'=>$subjects,
			'availableTeachers'=>$availableTeachers,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);//Load model
		if($model->status==PresetCourse::STATUS_PENDING && $model->deleted_flag==0){
            $model->deleted_flag = 1;//Set deleted flag before delete
            $model->save();
            $this->redirect(array('/admin/presetCourse'));
        }elseif($model->deleted_flag==1){
			$model->delete();//Delete Preregister course
			$this->redirect(array('/admin/presetCourse?deleted_flag=1'));
        }
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(array('/admin/presetCourse')));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = "Khóa học tạo sẵn";
		$this->loadJQuery = false;//Not load jquery
		$model=new PresetCourse('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PresetCourse'])){
			$model->attributes=$_GET['PresetCourse'];
			if(isset($_GET['PresetCourse']['start_date'])){
				$model->start_date = Common::convertDateFilter($_GET['PresetCourse']['start_date']);//Created date filter
			}
		}
		$model->deleted_flag = 0;//Deleted flag
		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
			$model->deleted_flag = 1;
		}
		$model->getDbCriteria()->order = 'start_date ASC';
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PresetCourse the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PresetCourse::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PresetCourse $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='preset-course-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAjaxCreateCourse()
	{
		$success = false;//Success result
		if($_REQUEST['preset_id']){
			$presetCourse = PresetCourse::model()->findByPk($_REQUEST['preset_id']);
			if(isset($presetCourse->id)){
				$clsCourse = new ClsCourse();
				$success = $clsCourse->createActualCourseFromPreset($presetCourse->id);
			}
		}
		$this->renderJSON(array('success'=>$success));
	}
}
