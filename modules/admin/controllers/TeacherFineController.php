<?php

class TeacherFineController extends Controller
{
    /*
        TODO:
        - The model needs rework
        + teacher_id is redundant (retrieve through session_id)
        + id is redundant (use session_id as primary key)
        - Actions needs changes
        + add new fine record must be triggered in a session view to get session_id
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
				'actions'=>array('chargeFine', 'fineRecords', 'fineChargeRecords', 'fineChargeList', 'expiredFine', 'deleteFine',
								 'create', 'view', 'ajaxGetFine', 'ajaxUpdateFine'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('update, delete'),
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

		$teacherFine = $teacherFine->with(array('session', 'teacher'=>array('alias'=>'ft')))->search('t.id desc');

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
				$teachers = User::model()->findByFullname($_REQUEST['TeacherFineCharge']['teacher_fullname'], array('id'));
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

		$teacherFine = $teacherFine->with(array(
			'session'=>array('alias'=>'s', 'joinType'=>'RIGHT JOIN', 'on'=>"s.plan_start < '" . date('Y-m-d', strtotime('-2 month')) . "'"),
			'teacher'=>array('alias'=>'ft')
		))->search();
		
		$this->render('fineRecords', array(
			'model'=>$teacherFine,
			'view'=>'expired',
		));
	}
	
	public function actionView($id){
		$this->subPageTitle = 'Chi tiết';

		$model = TeacherFine::model()->with("teacher", "session")->findByPk($id);
		if ($model == null){
			throw new CHttpException(404, "The requested page could not be found");
			
		}
		
		$this->render('view', array(
			'model'=>$model,
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
				throw new CHttpException(500,'Internal server error');
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

    public function actionAjaxGetFine(){
        if (isset($_REQUEST['session_id'])){
            $fine = TeacherFine::model()->findByAttributes(array("session_id"=>$_REQUEST['session_id']));
            
            if ($fine != null){
                $this->renderJSON(array(
                    "points"=>$fine->points,
                    "notes"=>$fine->notes,
                ));
            } else {
                $this->renderJSON(array(
                    "not_found"=>true,
                ));
            }
        }
    }
    
    public function actionAjaxUpdateFine(){
        $success = false;
        if (isset($_POST['action']) && isset($_POST["TeacherFine"])){
            $action = $_POST['action'];
            
            switch ($action){
                case "create":
                    $fine = new TeacherFine();
                    break;
                case "update":
                    $fine = TeacherFine::model()->findByAttributes(array("session_id"=>$_POST['TeacherFine']['session_id']));
                    break;
                default:
                    break;
            }
            
            $fine->attributes = $_POST["TeacherFine"];
            $fine->teacher_id = $fine->session->teacher_id;
            if ($fine->save()){
                $success = true;
            }
        }
        
        $this->renderJSON(array("success"=>$success));
    }
}
