<?php

class TeacherFineController extends Controller
{
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
				'actions'=>array('chargeFine', 'fineRecords', 'fineChargeRecords', 'fineChargeList', 'expiredFine', 'deleteFine',
								 'create', 'view', 'update', 'delete'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(''),
				'users'=>array('*'),
				'expression' => 'Yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionChargeFine(){
		if (isset($_POST['teacherId'])){
			$teacherId = $_POST['teacherId'];
			$currentPoints = TeacherFine::getCurrentPoint($teacherId);
			$pointsToCharge = TeacherFineCharge::getNumberOfPointsToCharge($currentPoints);
			if ($pointsToCharge > 0){
				TeacherFineCharge::chargeFine($teacherId, $pointsToCharge);
			}
		}
	}
	
	public function actionFineRecords(){
		$this->subPageTitle = 'Điểm phạt của giáo viên';
		
		$this->loadJQuery = false; //to avoid conflict with cgridview jquery
		
		$teacherFine = new TeacherFine;
		$teacherFine->unsetAttributes();
		if (isset($_REQUEST['TeacherFine'])){
			$teacherFine->attributes = $_REQUEST['TeacherFine'];
			if (isset($_REQUEST['TeacherFine']['teacher_fullname'])){
				$teachers = User::model()->findByFullname($_REQUEST['TeacherFine']['teacher_fullname'], array('id'));
				$teacherId = array();
				foreach ($teachers as $teacher)
				{
					array_push($teacherId, $teacher['id']);
				}
				$teacherIdString = implode(", ", $teacherId);
				if ($teacherIdString == ''){
					$teacherIdString = "''";
				}
				$teacherFine->getDbCriteria()->addCondition("teacher_id in (" . $teacherIdString . ")");
			}
		}
		
		$this->render('fineRecords', array(
			"model"=>$teacherFine,
			"view"=>"all",
		));
	}
	
	public function actionFineChargeRecords(){
		$this->subPageTitle = 'Fine Charged';
		
		$this->loadJQuery = false; //to avoid conflict with cgridview jquery
		
		$teacherFineCharge = new TeacherFineCharge;
		$teacherFineCharge->unsetAttributes();
		if (isset($_REQUEST['TeacherFineCharge'])){
			$teacherFineCharge->attributes = $_REQUEST['TeacherFineCharge'];
			if (isset($_REQUEST['TeacherFineCharge']['teacher_fullname'])){
				$teachers = User::model()->findByFullname($_REQUEST['teacherFineCharge']['teacher_fullname'], array('id'));
				$teacherId = array();
				foreach ($teachers as $teacher)
				{
					array_push($teacherId, $teacher['id']);
				}
				$teacherIdString = implode(", ", $teacherId);
				if ($teacherIdString == ''){
					$teacherIdString = "''";
				}
				$teacherFineCharge->getDbCriteria()->addCondition("teacher_id in (" . $teacherIdString . ")");
			}
		}
		
		$this->render('fineChargeRecords', array(
			"model"=>$teacherFineCharge,
		));
	}
	
	public function actionFineChargeList(){
		$this->subPageTitle = 'Danh sách giáo viên';
		
		$this->loadJQuery = false;

		if (isset($_REQUEST['view']) && $_REQUEST['view'] == 'all'){
			$teachers = TeacherFineCharge::model()->getAllTeachersFine();
			$showAll = true;
		} else {
			$teachers = TeacherFineCharge::model()->getTeachersToBeCharged();
			$showAll = false;
		}
		
		$this->render('fineChargeList', array(
			'teachers'=>$teachers,
			'showAll'=>$showAll,
		));
	}
	
	public function actionExpiredFine(){
		$this->subPageTitle = 'Expired Fine';
		
		$this->loadJQuery = false;
		
		$teacherFine = new TeacherFine;
		$teacherFine->unsetAttributes();
		$criteria = $teacherFine->getDbCriteria();
		$criteria->addCondition("points_to_be_fined > 0");
		$criteria->addCondition("created_date < '" . date('Y-m-d', strtotime('-2 month')) . "'");
		if (isset($_REQUEST['TeacherFine'])){
			$teacherFine->attributes = $_REQUEST['TeacherFine'];
			if (isset($_REQUEST['TeacherFine']['teacher_fullname'])){
				$teachers = User::model()->findByFullname($_REQUEST['TeacherFine']['teacher_fullname'], array('id'));
				$teacherId = array();
				foreach ($teachers as $teacher)
				{
					array_push($teacherId, $teacher['id']);
				}
				$teacherIdString = implode(", ", $teacherId);
				if ($teacherIdString == ''){
					$teacherIdString = "''";
				}
				$teacherFine->getDbCriteria()->addCondition("teacher_id in (" . $teacherIdString . ")");
			}
		}
		
		$this->render('fineRecords', array(
			'model'=>$teacherFine,
			'view'=>'expired',
		));
	}
	
	public function actionCreate(){
		$this->subPageTitle = 'Ghi nhận phạt mới';
		$teacherFine = new TeacherFine;
		if (isset($_POST['TeacherFine'])){
			$teacherFine->attributes = $_POST['TeacherFine'];
			$teacherFine->points_to_be_fined = $_POST['TeacherFine']['points'];
			if($teacherFine->save()){
				$this->redirect('/admin/teacherFine/fineRecords');
			}else {
				throw new CHttpException(500,'Unexpected error happened');
			}
		} else {
			if(isset($_REQUEST['teacherId'])){
				$teacherFine->teacher_id = $_REQUEST['teacherId'];
			}
			$this->render('create', array(
				'model'=>$teacherFine,
			));
		}
	}
	
	public function actionView($id){
		$this->subPageTitle = 'Chi tiết';
		
		$this->render('view', array(
			'model'=>$this->loadModel($id),
		));
	}
	
	public function actionUpdate($id){
		$this->subPageTitle = 'Sửa thông tin';
		$teacherFine = $this->loadModel($id);
		if (isset($_POST['TeacherFine'])){
			$oldPoints = $teacherFine->points;
			$teacherFine->attributes = $_POST['TeacherFine'];
			$teacherFine->points_to_be_fined += ($_POST['TeacherFine']['points'] - $oldPoints);
			if ($teacherFine->points_to_be_fined < 0){
				$teacherFine->points_to_be_fined  = 0;
			}
			if($teacherFine->save()){
				$this->redirect('/admin/teacherFine/fineRecords');
			} else {
				throw new CHttpException(500,'Unexpected error happened');
			}
		} else {
			$this->render('update', array(
				'model'=>$teacherFine,
			));
		}
	}
	
	public function actionDelete($id){
		$teacherFine = $this->loadModel($id);
		//fine charged record cannot be revoked
		$success = false;
		if ($teacherFine->points != $teacherFine->points_to_be_fined && $points_to_be_fined !== 0){
			if ($teacherFine->delete()){
				$success = true;
			}
		}
		return $success;
	}
	
	public function actionDeleteFine(){
		$success = false;
		if(isset($_POST['id'])){
			$teacherFine = TeacherFine::model()->findByPk($_POST['id']);
			if ($teacherFine != null){
				$teacherFine->points_to_be_fined = 0;
				if ($teacherFine->save()){
					$success = true;
				}
			}
		}
		$this->renderJSON(array("success"=>$success));
	}
	
	public function loadModel($id)
	{
		$model=TeacherFine::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


}
