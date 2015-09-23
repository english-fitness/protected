<?php

class TeacherController extends Controller
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
				'actions'=>array('index','view','changeToStudent','update','create'),
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
        $this->loadJQuery = false;
		$this->subPageTitle = 'Thông tin giáo viên';
		$model = $this->loadModel($id);
		$teacherProfile = Teacher::model()->findByPk($model->id);		
		$this->render('view',array(
			'model'=>$model,
			'teacherProfile'=>$teacherProfile,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$this->subPageTitle = 'Thêm mới giáo viên';
		$model = new User;
		$teacher = new Teacher; 
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$classSubjects = Subject::model()->generateSubjects();
		$abilitySubjects = $teacher->abilitySubjects();//Subjects ability of teacher
		if(isset($_POST['User']))
		{
			$teacher_values = $_POST['User'];//Teacher values
			$teacher_profile_values = $_POST['Teacher'];//Teacher profile values
			if(isset($_POST['abilitySubjects'])){
				$abilitySubjects = $_POST['abilitySubjects'];//Subject ability
			}
			$teacher_values['role'] = User::ROLE_TEACHER;
			if(trim($teacher_values['birthday'])==''){
				unset($teacher_values['birthday']);
			}
			$model->attributes = $teacher_values;
			$model->passwordSave = $teacher_values['password'];
			$common = new Common();
			$dir = "media/uploads/profiles";
			$profilePicture = $common->uploadProfilePicture("profilePicture",$dir);
			if($profilePicture !== false){
                $oldProfilePicture = $model->profile_picture;
				$model->profile_picture=$profilePicture;
			}
			if($model->save()){
                $oldPictureFullPath = $dir."/".$oldProfilePicture;
                if ($profilePicture !== false && file_exists($oldPictureFullPath)){
                    unlink($oldPictureFullPath);
                }
				$teacher->attributes = $teacher_profile_values;
				$teacher->user_id = $model->id;
				if($teacher->save()){
					$teacher->saveAbilitySubjects($abilitySubjects);
					$this->redirect(array('index'));
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
			'teacher'=>$teacher,
			'classSubjects' => $classSubjects,
			'abilitySubjects' => $abilitySubjects,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$this->subPageTitle = 'Sửa thông tin giáo viên';
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$teacher = Teacher::model()->findByPk($id);
		if(!isset($teacher->user_id)){
			$teacher = new Teacher;
		}
		$classSubjects = Subject::model()->generateSubjects();//Subject by Class
		$abilitySubjects = $teacher->abilitySubjects();//Subjects ability of teacher
		if(isset($_POST['User']))
		{
			$teacherValues = $_POST['User'];//Teacher values
			$teacherProfileValues = $_POST['Teacher'];//Teacher profile values
			if(isset($_POST['abilitySubjects'])){
				$abilitySubjects = $_POST['abilitySubjects'];//Subject ability
			}
			if(trim($teacherValues['birthday'])==''){
				unset($teacherValues['birthday']);
			}
			$changePassStatus = (isset($_POST['changeStatus']) && $_POST['changeStatus']==1)? true: false;
			if($teacherValues['password']==""){
				$changePassStatus = false;
				unset($teacherValues['password']);//Not save password
			}
			$model->attributes = $teacherValues;			
			if($changePassStatus){
				$model->passwordSave = $model->password;
				$model->repeatPassword = $model->passwordSave;
			}
			$common = new Common();
			$dir = "media/uploads/profiles";
			$profilePicture = $common->uploadProfilePicture("profilePicture",$dir);
			if($profilePicture !== false){
                $oldProfilePicture = $model->profile_picture;
				$model->profile_picture=$profilePicture;
			}
			if($model->save()){
                $oldPictureFullPath = $dir."/".$oldProfilePicture;
                if ($profilePicture !== false && file_exists($oldPictureFullPath)){
                    unlink($oldPictureFullPath);
                }
				$teacher->attributes = $teacherProfileValues;
				$teacher->user_id = $model->id;
				if($teacher->save()){
					$teacher->saveAbilitySubjects($abilitySubjects);
					$this->redirect(array('index'));
				}
			}
		}

		$this->render('update',array(
			'model'=>$model,
			'teacher'=>$teacher,
			'classSubjects' => $classSubjects,
			'abilitySubjects' => $abilitySubjects,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->subPageTitle = 'Xóa giáo viên';
		$model = $this->loadModel($id);
		if($model->status==User::STATUS_PENDING){
			$model->deleted_flag = 1;//Deleted flag
			$model->save();
		}
		$this->redirect(array('/admin/teacher/index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Danh sách giáo viên';
		$this->loadJQuery = false;//Not load jquery
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		$criteria = new CDbCriteria();
        $criteria->compare('role',User::ROLE_TEACHER);
        $criteria->compare('deleted_flag', 0);
        $model->setDbCriteria($criteria);
		if(isset($_GET['User'])){
			$model->attributes=$_GET['User'];
			if(isset($_GET['User']['created_date'])){
				$model->created_date = Common::convertDateFilter($_GET['User']['created_date']);//Created date filter
			}
			if(isset($_GET['User']['birthday'])){
				$model->birthday = Common::convertDateFilter($_GET['User']['birthday']);//Birthday filter
			}
			if(isset($_GET['User']['firstname'])){
				$keyword = $_GET['User']['firstname'];
				$model->getDbCriteria()->addCondition("CONCAT(`lastname`,' ',`firstname`) LIKE '%".$keyword."%'");
			}
		}
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Change teacher to student
	 */
	public function actionChangeToStudent($id)
	{
		$model = $this->loadModel($id);
		if(isset($model->id)){//Change student to teacher
			$model->role = User::ROLE_STUDENT;
			$model->save();
		}
		//Create teacher profile if not existed
		$student = Student::model()->findByPk($id);
		if(!isset($student->user_id)){
			$modelStudent = new Student();
			$modelStudent->attributes = array(
				'user_id' => $id,
				'short_description' => 'Chuyển từ giáo viên thành học sinh!',
			);
			$modelStudent->save();
		}
		$this->redirect(array('/admin/student/update/id/'.$id));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
