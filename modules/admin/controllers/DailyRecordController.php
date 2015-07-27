<?php

class DailyRecordController extends Controller
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
                'actions'=>array('index', 'create', 'update', 'delete', 'view'),
                'users'=>array('*'),
            ),
            array('allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions'=>array('editLocked'),
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
        $model=TeachingDay::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
    }
	
	public function loadPaymentModel($id){
		$model = TeacherPayment::model()->findByPk($id);
        if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
        return $model;
	}
	
	public function actionView($id){
		$this->subPageTitle = 'Thống kê buổi học trong ngày';
		
		$this->render('admin.views.teacherPayment.dailyRecord.view', array(
			'model'=>$this->loadModel($id),
		));
	}

    public function actionCreate(){
		$this->subPageTitle = 'Tạo thống kê buổi học mới';
		
		$record = new TeachingDay;
		if (isset($_POST['Record'])){
			$values = $_POST['Record'];
			$month = date('Y-m-01', strtotime($values['day']));
			if (isset($_REQUEST['payment_id'])){
				$payment = TeacherPayment::model()->findByPk($_REQUEST['payment_id']);
			} else {
				if ($_POST['Record']['teacher_id'] != null){
					$payment = TeacherPayment::model()->findByAttributes(array(
						'teacher_id'=>$values['teacher_id'],
						'month'=>$month
					));
				}else {
					$record->attributes = $values;
					$this->render('admin.views.teacherPayment.dailyRecord.create', array(
						'model'=>$record,
						'error'=>'no_teacher_id',
					));
				}
			}
			if ($payment == null){
				$payment = new TeacherPayment;
				if ($values['platform_session'] != null && $values['non_platform_session'] != null){
					$payment->teacher_id = $values['teacher_id'];
					$payment->month = $month;
					$payment->save();
				}
			} else {
				$existingRecord = TeachingDay::model()->findByAttributes(array(
					'day'=>$_POST['Record']['day'],
					'payment_id'=>$payment->id,
				));
				if ($existingRecord != null){
					$record->attributes = $values;
					$this->render('admin.views.teacherPayment.dailyRecord.create', array(
						'model'=>$record,
						'payment'=>$payment,
						'error'=>'record_existed',
					));
					exit();
				}
				else if ($payment->report_status == TeacherPayment::STATUS_CLOSED){
					$record->attributes = $values;
					$this->render('admin.views.teacherPayment.dailyRecord.create', array(
						'model'=>$record,
						'payment'=>$payment,
						'error'=>'payment_edit_closed',
					));
				}
			}
			$record->attributes = $values;
			$record->payment_id = $payment->id;
			
			if ($record->save()){
				$payment->total_platform_session += $record->platform_session;
				$payment->total_non_platform_session += $record->non_platform_session;
				$payment->save();
				$this->redirect('/admin/TeacherPayment/update/id/' . $payment->id);
			}
		}
		if (isset($_REQUEST['payment_id'])){
			$this->render('admin.views.teacherPayment.dailyRecord.create', array(
				'model'=>$record,
				'payment'=>$this->loadPaymentModel($_REQUEST['payment_id']),
			));
		} else {
			$this->render('admin.views.teacherPayment.dailyRecord.create', array(
				'model'=>$record,
			));
		}
	}
	
	public function actionUpdate($id){
		$this->subPageTitle = 'Sửa thống kê hàng ngày';
		
		$record = $this->loadModel($id);
		$payment = TeacherPayment::model()->findByPk($record->payment_id);
		if ($payment->report_status == TeacherPayment::STATUS_OPEN) {
			if (isset($_POST['Record'])){
				if (isset($_POST['Record']['day']) && $record->day != $_POST['Record']['day']){
					$existingRecord = TeachingDay::model()->findByAttributes(array(
						'day'=>$_POST['Record']['day'],
						'payment_id'=>$payment->id,
					));
				} else {
					$existingRecord = null;
				}
				if ($existingRecord == null){
					$oldPlatformSessionCount = $record->platform_session;
					$oldNonPlatformSessionCount = $record->non_platform_session;
					$record->attributes = $_POST['Record'];
					if ($record->save()){
						$payment->total_platform_session += $record->platform_session - $oldPlatformSessionCount;
						$payment->total_non_platform_session += $record->non_platform_session - $oldNonPlatformSessionCount;
						$payment->save();
						$this->redirect('/admin/TeacherPayment/update/id/' . $payment->id);
					}
				} else {
					$this->render('admin.views.teacherPayment.dailyRecord.create', array(
						'model'=>$record,
						'payment'=>$payment,
						'error'=>'record_existed',
					));
				}
			} else {
				$this->render('admin.views.teacherPayment.dailyRecord.update', array(
					'model'=>$record,
					'payment'=>$payment,
				));
			}
		}
	}
	
	public function actionDelete($id){
		$record = $this->loadModel($id);
		$payment = TeacherPayment::model()->findByPk($record->payment_id);
		if ($payment->report_status == TeacherPayment::STATUS_OPEN){
			$platform_session = $record->platform_session;
			$non_platform_session = $record->non_platform_session;
			if ($record->delete()){
				$payment->total_platform_session -= $platform_session;
				$payment->total_non_platform_session -= $non_platform_session;
				$payment->save();
			}
		}
	}
	
	public function actionIndex(){
		$this->subPageTitle = 'Danh sách bản ghi';
		
		$this->render('admin.views.teacherPayment.dailyRecord.index');
	}
	
	public function actionEditLocked(){
		$this->subPageTitle = 'Sửa bản ghi';
		
		$success = false;
		
		$record = $this->loadModel($id);
		$payment = TeacherPayment::model()->findByPk($record->payment_id);
		if (isset($_POST['Record'])){
			$record->attributes = $_POST['Record'];
			if ($record->save()){
				$this->redirect('/admin/TeacherPayment/view?payment_id=' . $payment->id);
			}
		}
		
		$this->render('admin.views.teacherPayment.dailyRecord.update', array(
			'record'=>$record,
			'payment'=>$payment,
		));
	}
}

?>