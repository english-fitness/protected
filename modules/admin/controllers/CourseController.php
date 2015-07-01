<?php

class CourseController extends Controller
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
				'actions'=>array('index','view','ajaxLoadSubjects','ajaxLoadTeachers', 'ajaxApprove',
				'unassignStudent', 'ajaxSuggestSchedules', 'ajaxLoadSuggestion', 'ajaxModifySchedule', 'ajaxLoadUser','create','update', 'ajaxLoadStudent',
				'ajaxLoadCourse', 'ajaxLoadSubjectsArray'),
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
		$this->subPageTitle = 'Thông tin khóa học';
		$model = $this->loadModel($id);
		$this->render('view',array(
			'model'=>$model,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Course;
		$this->subPageTitle = 'Tạo khóa học mới';
		$params = array();
		if(isset($_GET['preCourseId'])){
			$preCourse = PreregisterCourse::model()->findByPk($_GET['preCourseId']);
			if(isset($preCourse->id)) $params['preCourse'] =  $preCourse;
		}
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['Course']))
		{
			$registration = new ClsRegistration();
			$model->attributes=$_POST['Course'];
			$model->created_user_id=Yii::app()->user->getId();
			$model->status = Course::STATUS_PENDING;//Pending status
			$sessionValues = $_POST['Session'];//Session schedule values
			$checkValidTime = $registration->validateGenerateSession($sessionValues);
			if(isset($sessionValues['dayOfWeek']) && $checkValidTime){
				if($model->save()){				
					//Create schedule session of Course					
					$sessionValues['course_id'] = $model->id;//Set session course id
					$sessionValues['teacher_id'] = $model->teacher_id;//Set session teacher id
					Session::model()->saveSessionSchedules($sessionValues);
					//Assign students to Course & Sessions
					$extraUserIds = Yii::app()->request->getPost('extraUserIds', array());
					if(count($extraUserIds)==0 && isset($preCourse)){//Auto add student id to assign
						$extraUserIds = array($preCourse->student_id);
					}
					if(count($extraUserIds)>0){
						$model->assignStudentsToCourseSession($extraUserIds);
					}
					//Assign teacher to sessions of Course
					if($model->teacher_id>0){
						$model->assignTeacherToCourseSession();
					}
					$model->resetStatusSessions();//Reset status of sessions
					//Save actual course id to preregister course
					if(isset($preCourse)){
						$preCourse->course_id = $model->id;//Course id
						$preCourse->status = PreregisterCourse::STATUS_APPROVED;//Approve requested Course when create course
						$preCourse->save();
					}
				}
				$this->redirect(array('index?type='.$model->type));
			}else{
				$params['error'] = "Vui lòng kiểm tra lại kế hoạch chi tiết trong tuần!";
			}
		}
		$params['model'] = $model;
		$this->render('create', $params);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$this->subPageTitle = 'Sửa thông tin khóa học';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$subjects = CHtml::listData(Subject::model()->findAll(), 'id', 'name');//Subject
		$availableTeachers = Teacher::model()->availableTeachers($model->subject_id);//Available teachers
		$assignedStudentIds = $model->assignedStudents();//Assigned students of Course
		if(count($assignedStudentIds)==0) $assignedStudentIds = array(0);
		$availableStudents = User::model()->findAll(array("condition"=>"id IN (".implode(",", $assignedStudentIds).")"));
		$priorityTeachers = $model->priorityTeachers();//Priority Teachers
		if(isset($_POST['Course']) || isset($_POST['Course']))
		{
			$model->attributes=$_POST['Course'];
			if($model->save()){
				//Assign students to sessions of Course
				$extraUserIds = Yii::app()->request->getPost('extraUserIds', array());
                if(count($extraUserIds)>0){
					//New assign students(compare to assigned student before)
					$newAssignStudentIds = array_diff($extraUserIds, $assignedStudentIds);//New StudentIds
					$model->assignStudentsToCourseSession($newAssignStudentIds);
				}
				//Assign Teacher to sessions of Course
				if(isset($_POST['Course']['teacher_id'])){
					$model->assignTeacherToCourseSession();
				}
				$changeStatus = false;
				if(isset($_POST['Course']['status']) && $_POST['Course']['status']==Course::STATUS_ENDED){
					$changeStatus = true;
				}
				$model->resetStatusSessions($changeStatus);//Reset status of sessions
				// $this->redirect(array('index?type='.$model->type));
			}
			if (isset($_POST['Session']))
			{
				$sessionValues = $_POST['Session'];
				$planSet = isset($sessionValues['plan_duration']) 
						&& isset($sessionValues['dayOfWeek']) 
						&& isset($sessionValues['startHour']) 
						&& isset($sessionValues['startMin']);
				if ($planSet)
				{
					$model->changeSchedule($sessionValues);
				}
			}
			$this->redirect(array('index?type='.$model->type));
		}

		$this->render('update',array(
			'model'=>$model,
			'subjects' => $subjects,
			'availableTeachers'=>$availableTeachers,
			'availableStudents'=>$availableStudents,
			'priorityTeachers'=>$priorityTeachers,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->subPageTitle = 'Hủy/Xóa khóa học';
		$model = $this->loadModel($id);//Load model
		try {
			if($model->status==Course::STATUS_PENDING && $model->deleted_flag==0){
				$model->deleted_flag = 1;//Set deleted flag before delete
				$model->save();
				Session::model()->updateAll(array('deleted_flag'=>1), "(course_id = $id)");
				$this->redirect(array('/admin/course/update/id/'.$model->id));
			}elseif($model->deleted_flag==1){
				$model->deleteAssignedSessionStudents();//Delete all assigned student in sessions
				$model->deleteAssignedCourseStudents();//Delete all assigned student in course
				$model->deleteCourseSessions();//Delete Course Sessions
				$model->delete();//Delete this session
				$this->redirect(array('/admin/course?type='.$model->type));
			}
		}catch(Exception $e){
			//Display error message here
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/course?type='.$model->type));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Danh sách khóa học';
		$this->loadJQuery = false;//Not load jquery
		$model=new Course('search("created_date DESC")');		
		$model->unsetAttributes();  // clear any default values		
		if(isset($_GET['Course'])){
			$model->attributes=$_GET['Course'];
		}
		//Get list of course by teacher
		if(isset($_GET['teacher_id']))	$model->teacher_id = $_GET['teacher_id'];
		
		//Get list of course by student
		if(isset($_GET['student_id'])){
			$assignedCourses = Student::model()->assignedCourses($_GET['student_id']);
			if(count($assignedCourses)==0) $assignedCourses = array(0);
			$model->id = $assignedCourses;//Filter by student id
		}
		//Get list of course by type
		if(isset($_GET['type'])) $model->type = $_GET['type'];
		
		$model->deleted_flag = 0;//Deleted flag
		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
			$model->deleted_flag = 1;
		}
		
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Unassign student from Course
	 */
	public function actionUnassignStudent()
	{
		$this->subPageTitle = 'Hủy gán học sinh';
		$studentId = Yii::app()->request->getQuery('student_id', NULL);
		$courseId = Yii::app()->request->getQuery('course_id', NULL);
		$course = $this->loadModel($courseId);//Load course
		if(isset($course->id) && $studentId!=NULL){
			$course->unassignStudents(array($studentId));
			$this->redirect(array('/admin/course/update/id/'.$course->id));
		}		
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Course the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Course::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Course $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='course-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Ajax load subject by class id
	 */
	public function actionAjaxLoadSubjects()
	{
		$class_id = $_REQUEST['class_id'];
		$subjectParams = array();//Init subjects
		if($class_id!=""){
			$subjectParams['subjects'] = Subject::model()->findAllByAttributes(array('class_id'=>$class_id));
			if(isset($_REQUEST['subject_id'])){
				$subjectParams['subjectId'] = $_REQUEST['subject_id'];
			}
		}
		echo $this->renderPartial('widget/classSubjects', $subjectParams);
	}
	
	//normal ajax load subject action
	public function actionAjaxLoadSubjectsArray(){
		$class_id = $_REQUEST['class_id'];
		$subjects = array();//Init subjects
		if($class_id!=""){
			$subjects = Subject::model()->findAllByAttributes(array('class_id'=>$class_id));
			$this->renderJSON(array('subjects'=>$subjects));
		}
	}

	/**
	 * Ajax load available Teachers by subject id
	 */
	public function actionAjaxLoadTeachers()
	{
		$subject_id = $_REQUEST['subject_id'];
		$teachers = array();//Init teachers
		if($subject_id!=""){
			$teachers = Teacher::model()->availableTeachers($subject_id);
		}
		echo $this->renderPartial('widget/assignTeachers', array('teachers'=>$teachers));
	}
	
	/**
	 * Ajax Approve course
	 */
	public function actionAjaxApprove()
	{
		$courseId = $_REQUEST['course_id'];
		$success = false;//Set success
		$model = $this->loadModel($courseId);//Load model
		$model->status = Course::STATUS_APPROVED;//Set status course to 2-approve
		if ($model->save()){
			$success = true;
		}
		//Save status
		//Approved all session of Course
		$model->resetStatusSessions();
		$this->renderJSON(array('success'=>$success));
	}
	
	/**
	 * Ajax load generate schedule suggestion
	 */
	public function actionAjaxSuggestSchedules()
	{
		$nPerWeek = $_REQUEST['nPerWeek'];
		$registration = new ClsRegistration();
		$suggestedDays = $registration->suggestSessionDayInWeek($nPerWeek);
		echo $this->renderPartial('widget/scheduleSuggestion', array('suggestedDays'=>$suggestedDays));
	}
	
	/**
	 * Ajax load suggestion title by subject Id
	 */
	public function actionAjaxLoadSuggestion()
	{
		$subjectId = $_REQUEST['subject_id'];
		$suggestions = SubjectSuggestion::model()->getSuggestionBySubject($subjectId);
		$this->renderJSON(array('success'=>true, 'suggestions'=>$suggestions));
	}
	
	/**
	 * Change course schedule, apply for all future sessions of course.
	 */
	public function actionAjaxModifySchedule()
	{
		$courseId = Yii::app()->request->getPost('courseId', NULL);
		$modifyDay = Yii::app()->request->getPost('modifyDay', NULL);
		$success = false;//Modify success
		if($courseId && $modifyDay)
        {
            $clsSession = new ClsSession();
            $success = $clsSession->checkAndUpdateCalendarSessions($courseId, $modifyDay);
        }
        $this->renderJSON(array('success'=>true));
	}

    /* action Ajax Load User */
    public function actionAjaxLoadUser($keyword){
       $usersAttributes = User::model()->searchUsersToAssign($keyword);
       $this->renderJSON(array($usersAttributes));
    }
	
	public function actionAjaxLoadStudent($keyword){
       $usersAttributes = User::model()->searchUsersToAssign($keyword, 'role_student');
       $this->renderJSON(array($usersAttributes));
    }
	
	public function actionAjaxLoadCourse($student){
		$query = "SELECT id, title FROM tbl_course JOIN tbl_course_student 
				  ON tbl_course.id = tbl_course_student.course_id 
				  WHERE tbl_course_student.student_id = " . $student . " " .
				  "AND deleted_flag <> 1 " .
				  " ORDER BY course_id DESC";
		$course = Course::model()->findAllBySql($query);
		
		$student = User::model()->findByPk($student);
		if (empty($course) && $student->status < User::STATUS_OFFICIAL_USER){
			$courseOptions = array(
				array(
					"id"=>Course::TYPE_COURSE_TRAINING*-1,
					"title"=>"Tạo khóa học thử",
				),
			);
		} else {
			$courseOptions = array(
				array(
					"id"=>Course::TYPE_COURSE_NORMAL*-1,
					"title"=>"Tạo khóa học thường",
				),
				array(
					"id"=>Course::TYPE_COURSE_TRAINING*-1,
					"title"=>"Tạo khóa học thử",
				),
			);
		}
		
		$courseOptions = array_merge($course, $courseOptions);
		
		$this->renderJSON($courseOptions);
	}
}
