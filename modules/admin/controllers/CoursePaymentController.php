<?php

class CoursePaymentController extends Controller
{
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    // public $layout='//layouts/column2';

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
                'actions'=>array('index', 'create', 'update', 'view', 'course'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('delete'),
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
	
	public function loadModel($id)
    {
        $model=CoursePayment::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
	
	public function actionCourse($id){
		$this->subPageTitle = "Học phí khóa học";
		$this->loadJQuery = false;
        
        $course = $this->loadCourseModel($id);
        
		$model = new CoursePayment;
        $model->unsetAttributes();
        
		if (isset($_REQUEST['CoursePayment'])){
			$model->attributes = $_REQUEST['CoursePayment'];
		}
        $model->course_id = $id;
        
		$this->render('course', array(
			'model'=>$model,
			'course'=>$course,
		));
	}
	
	public function actionCreate(){
		$this->subPageTitle = "Thêm học phí cho khóa học";
		
		if(!isset($_REQUEST['course_id'])){
			throw new CHttpException(400, 'Invalid request');
		}
		
		$courseId = $_REQUEST['course_id'];
		
		$payment = new CoursePayment;
		$payment->course_id = $courseId;
		if (isset($_REQUEST['CoursePayment'])){
			$payment->attributes = $_POST['CoursePayment'];
            $packageOption = CoursePackageOptions::model()->findByPk($_POST['CoursePayment']['package_option_id']);
            $payment->tuition = $packageOption->tuition;
            $payment->sessions = $packageOption->package->sessions;
            
            $transaction = Yii::app()->db->beginTransaction();
            try{
                if ($payment->save()){
                    $course = Course::model()->findByPk($courseId);
                    $course->final_price += $payment->tuition;
                    $course->total_sessions += $payment->sessions;
                    if ($course->save()){
                        $transaction->commit();
                        $this->redirect(array('/admin/coursePayment/course/id/' . $courseId));
                    } else {
                        throw new Exception("course_not_saved");
                    }
                } else {
                    throw new Exception("payment_not_saved");
                }
            } catch (Exception $e){
                $transaction->rollback();
            }
		}
		
		$this->render('create', array(
			'model'=>$payment,
		));
	}
	
	public function actionUpdate($id){
		$this->subPageTitle = "Sửa học phí";
		
		$payment = $this->loadModel($id);
		
		$success = false;
		
		if(isset($_REQUEST['CoursePayment'])){
            $oldTuition = $payment->tuition;
            $oldSessions = $payment->sessions;
			$payment->attributes = $_POST['CoursePayment'];
            $packageOption = CoursePackageOptions::model()->findByPk($_POST['CoursePayment']['package_option_id']);
            $payment->tuition = $packageOption->tuition;
            $payment->sessions = $packageOption->package->sessions;
            
            $transaction = Yii::app()->db->beginTransaction();
            
            try{
                if ($payment->save()){
                    if ($oldTuition != $payment->tuition || $oldSessions != $payment->sessions){
                        $course = Course::model()->findByPk($payment->course_id);
                        $course->final_price += $payment->tuition - $oldTuition;
                        $course->total_sessions += $payment->sessions - $oldSessions;
                        if (!$course->save()){
                            throw new Exception("course_not_saved");
                        }
                    }
                    $transaction->commit();
                    $this->redirect(array('/admin/coursePayment/course/id/' . $payment->course_id));
                } else {
                    throw new Exception("payment_not_saved");
                }
            } catch(Exception $e){
                $transaction->rollback();
            }
		}
		
		$this->render('update', array(
			'model'=>$payment,
		));
	}
	
	public function actionView($id){
		$this->subPageTitle = "Xem chi tiết";
		
		$payment = CoursePayment::model()->with("course", "createdUser", "modifiedUser")->findByPk($id);
        
		$this->render('view', array(
			'model'=>$payment,
		));
	}
	
	public function actionDelete($id){
		$payment = $this->loadModel($id);
		
		$success = false;
		
		if ($payment->delete()){
			$success = true;
		}
		
		$this->renderJSON(array(
			'success'=>$success
		));
	}
}
