<?php

class TeacherPaymentController extends Controller
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
                'actions'=>array('index', 'report', 'setPaid', 'update'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('openPaymentEdit','setUnpaid'),
                'users'=>array('*'),
                'expression' => 'Yii::app()->user->isAdmin()',
            ),
            array('deny',  // deny all users
                'users'=>array('*'),
            ),
        );
    }
	
	public function loadModel($id)
    {
        $model=TeacherPayment::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
	
	public function actionIndex(){
		$this->subPageTitle = 'Tổng hợp hàng tháng';
		
		$this->loadJQuery = false; //to avoid conflict with cgridview jquery
		$payment = new TeacherPayment;
		$payment->unsetAttributes();
		if (isset($_REQUEST['TeacherPayment'])){
			$payment->attributes = $_REQUEST['TeacherPayment'];
			if(isset($_REQUEST['TeacherPayment']['month'])){
				$month = $_REQUEST['TeacherPayment']['month'];
				$payment->month = date('Y-m-d', strtotime('01-' . $month));
			}
			if(isset($_REQUEST['TeacherPayment']['teacher_fullname'])){
				$keyword = $_REQUEST['TeacherPayment']['teacher_fullname'];
				$teachers = User::model()->findByFullname($keyword, array('id'));
				$teacherId = array();
				foreach ($teachers as $teacher)
				{
					array_push($teacherId, $teacher['id']);
				}
				$teacherIdString = implode(", ", $teacherId);
				if ($teacherIdString == ''){
					$teacherIdString = "''";
				}
				$payment->getDbCriteria()->addCondition("teacher_id in (" . $teacherIdString . ")");
			}
		}
		
		$this->render('index', array(
			"model"=>$payment,
		));
	}
	
	public function actionUpdate($id){
		$this->subPageTitle = 'Tổng hợp hàng tháng';
		
		$this->loadJQuery = false; //to avoid conflict with cgridview jquery
		$payment = $this->loadModel($id);
		
		$day = new TeachingDay;
		$day->unsetAttributes();
		
		$this->render('update', array(
			'payment'=>$payment,
			'days'=>$day,
		));
	}
	
	public function actionReport(){
		$success = false;
		if (isset($_POST['payment_id'])){
			$payment = $this->loadModel($_POST['payment_id']);
			$payment->report_status = TeacherPayment::STATUS_CLOSED;
			$payment->report_date = date('Y-m-d');
			$payment->report_user_id = Yii::app()->user->id;
			if ($payment->save()){
				$success = true;
			}
		}
		$this->renderJSON(array('success'=>$success));
	}
	
	public function actionSetPaid(){
		$success = false;
		if (isset($_POST['payment_id'])){
			$payment = $this->loadModel($_POST['payment_id']);
			$payment->payment_status = TeacherPayment::STATUS_PAID;
			$payment->payment_date = date('Y-m-d');
			if ($payment->save()){
				$success = true;
			}
		}
		$this->renderJSON(array('success'=>$success));
	}
	
	//admin only
	public function actionOpenPaymentEdit(){
		$success = false;
		if (isset($_POST['payment_id'])){
			$payment = $this->loadModel($_POST['payment_id']);
			$payment->report_status = TeacherPayment::STATUS_OPEN;
			$payment->report_date = null;
			$payment->report_user_id = null;
			if ($payment->save()){
				$success = true;
			}
		}
		$this->renderJSON(array('success'=>$success));
	}
	
	public function actionSetUnpaid(){
		$success = false;
		if (isset($_POST['payment_id'])){
			$payment = $this->loadModel($_POST['payment_id']);
			$payment->payment_status = TeacherPayment::STATUS_UNPAID;
			$payment->payment_date = null;
			if ($payment->save()){
				$success = true;
			}
		}
		$this->renderJSON(array('success'=>$success));
	}
}
