<?php

class PreregisterCourseController extends Controller
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
				'actions'=>array('index','view','update','create', 'ajaxRefuse'),
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
		$this->subPageTitle = 'Chi tiết đơn xin học';
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
		$this->subPageTitle = "Thêm mới đơn xin học cho học sinh";
		$model = new PreregisterCourse();
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = Subject::model()->generateSubjectFilters();
		$emailError = "";//Email error message
		if(isset($_POST['PreregisterCourse']))
		{
			$model->attributes=$_POST['PreregisterCourse'];
			$model->created_user_id = Yii::app()->user->id;//User id
			$email = Yii::app()->request->getPost('email', NULL);
			$student = User::model()->findByAttributes(array('email'=>$email));
			if(isset($student->id)){
				$model->student_id = $student->id;//Student id
				if($model->save()){
					$this->redirect(array('/admin/preregisterCourse'));
				}
			}else{
				$emailError = "Vui lòng điền đúng email của học sinh trên hệ thống DạyKèm123!";
			}
		}
		$this->render('create',array(
			'model'=>$model,
			'subjects'=>$subjects,
			'emailError'=>$emailError,
		));
	}
	
	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->subPageTitle = 'Sửa/phê duyệt đơn xin học';
		$model=$this->loadModel($id);		
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$mergeCourses = array();
		if(isset($_POST['PreregisterCourse']))
		{
			$preCourseValues = $_POST['PreregisterCourse'];
			$model->attributes = $preCourseValues;
			$isAssignToCourse = false;
			if(isset($preCourseValues['course_id']) && $preCourseValues['course_id']!=""){
				$isAssignToCourse = true;
				$model->status = PreregisterCourse::STATUS_APPROVED;//Approved status
			}
			if(strtotime($model->payment_date)===false){
				$model->payment_date = NULL;//Not save payment date
			}
			if($model->save()){
				if($isAssignToCourse){//Assign to existed course
					$course = Course::model()->findByPk($preCourseValues['course_id']);
					$assignedStudents = $course->assignedStudents();//Assigned students of Course
					if(!in_array($model->student_id, $assignedStudents)){
						$course->assignStudentsToCourseSession(array($model->student_id));
					}
				}
				if($model->preset_course_id>0){//Return preset course page
					$this->redirect(array('/admin/preregisterCourse?preset_id='.$model->preset_course_id));
				}else{
					$this->redirect(array('/admin/preregisterCourse'));
				}
			}
		}
		//Only display waiting merge course if total of student >1
		if($model->total_of_student>1){
			$mergeCourses = $model->displayWaitingMergedCourses();
		}
		$this->render('update',array(
			'model'=>$model,
			'mergeCourses'=>$mergeCourses,
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
		if($model->status==PreregisterCourse::STATUS_PENDING && $model->deleted_flag==0){
            $model->deleted_flag = 1;//Set deleted flag before delete
            $model->save();
            $this->redirect(array('/admin/preregisterCourse'));
        }elseif($model->deleted_flag==1){
			$model->delete();//Delete Preregister course
			$this->redirect(array('/admin/preregisterCourse?deleted_flag=1'));
        }
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/preregisterCourse'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Danh sách đơn xin học';
		$this->loadJQuery = false;//Not load jquery
		$model=new PreregisterCourse('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PreregisterCourse'])){
			$model->attributes=$_GET['PreregisterCourse'];
			if(isset($_GET['PreregisterCourse']['created_date'])){
				$model->created_date = Common::convertDateFilter($_GET['PreregisterCourse']['created_date']);//Created date filter
			}
			if(isset($_GET['PreregisterCourse']['start_date'])){
				$model->start_date = Common::convertDateFilter($_GET['PreregisterCourse']['start_date']);//start_date filter
			}
		}
		if(isset($_GET['student_id']))	$model->student_id = $_GET['student_id'];
		$model->deleted_flag = 0;//Deleted flag
		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
			$model->deleted_flag = 1;
		}
		//Display preregister course in preset course
		$presetCourse = null;
		if(isset($_GET['preset_id']) && !isset($_GET['student_id'])){
			$model->preset_course_id = $_GET['preset_id'];
			$presetCourse = PresetCourse::model()->findByPk($_GET['preset_id']);
		}elseif(!isset($_GET['student_id']) && !isset($_GET['deleted_flag'])){
			if(isset($_GET['type']) && $_GET['type']=='preset'){
				$model->getDbCriteria()->addCondition('preset_course_id is not NULL AND course_type='.Course::TYPE_COURSE_PRESET);
			}else{
				$model->getDbCriteria()->addCondition('preset_course_id is NULL OR course_type<>'.Course::TYPE_COURSE_PRESET);
			}
		}
		$model->getDbCriteria()->order = 'created_date DESC';
		$this->render('index',array(
			'model'=>$model,
			'presetCourse'=>$presetCourse,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PreregisterCourse the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PreregisterCourse::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PreregisterCourse $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='preregister-course-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
    /**
     * Ajax Refuse course
     */
    public function actionAjaxRefuse()
    {
        $precourseId = $_REQUEST['preCourseId'];
        $success = true;//Set success
        $model = $this->loadModel($precourseId);//Load model
        $model->status = PreregisterCourse::STATUS_REFUSED;//Set status course Refuse
        $model->save();//Save status
        $this->renderJSON(array('success'=>$success));
    }
}
