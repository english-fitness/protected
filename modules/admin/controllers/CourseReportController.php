<?php

class CourseReportController extends Controller
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
				'actions'=>array('course','view','create','update'),
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
    
    public function loadCourseModel($id){
		$model=Course::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
	}
    
    public function actionCourse($id){
        $this->subPageTitle = "Báo cáo khóa học";
        $course = $this->loadCourseModel($id);

        $this->loadJQuery = false;
        
        $model = new CourseReport;
        $model->unsetAttributes();
        
		if (isset($_REQUEST['CourseReport'])){
			$model->attributes = $_REQUEST['CourseReport'];
		}
        $model->course_id = $id;
        
		$this->render('course', array(
			'model'=>$model,
			'course'=>$course,
		));
    }
    
    public function actionView($id){
        $this->subPageTitle = "Báo cáo khóa học";
		
		$report = CourseReport::model()->findByPk($id);
        if ($report == null){
            throw new CHttpException(404, "The requested page is not found");
        }
        
		$this->render('view', array(
			'model'=>$report,
		));
    }
    
    public function actionCreate(){
        $this->subPageTitle = "Báo cáo mới";
        
        $model = new CourseReport;
        
        if (isset($_REQUEST['course_id'])){
            $model->course_id = $_REQUEST['course_id'];
            if (isset($_POST['CourseReport'])){
                $model->attributes = $_POST['CourseReport'];
                if (isset($_FILES['report_file']) && $_FILES['report_file']['name'] && !$_FILES['report_file']['error']){
	                $uploadedFile = $_FILES['report_file'];
	                $fileUploaded = $model->handleReportFileUpload($uploadedFile);
		            $modelSaved = $model->save();
		            if ($fileUploaded && $modelSaved){
		                $this->redirect("/admin/courseReport/course/id/".$model->course_id);
		            }
	            }
            }
            
            $this->render('create', array(
                'model'=>$model,
            ));
        } else {
            throw new CHttpException(400, "Invalid request!");
        }
    }
    
    public function actionUpdate($id){
        $this->subPageTitle = "Sửa báo cáo";

        $model = CourseReport::model()->with('course')->findByPk($id);
        
        if (isset($_POST['CourseReport'])){
            $model->attributes = $_POST['CourseReport'];
            if (isset($_FILES['report_file']) && $_FILES['report_file']['name'] && !$_FILES['report_file']['error']){
	            $uploadedFile = $_FILES['report_file'];
	            $fileUploaded = $model->handleReportFileUpload($uploadedFile);
	            $modelSaved = $model->save();
	            if ($fileUploaded && $modelSaved){
	                $this->redirect("/admin/courseReport/course/id/".$model->course_id);
	            }
	        }
        }
        
        $this->render('create', array(
            'model'=>$model,
        ));
    }
    
}