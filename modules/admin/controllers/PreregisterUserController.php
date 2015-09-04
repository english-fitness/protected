<?php

class PreregisterUserController extends Controller
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
				'actions'=>array('index','view','create', 'update', 'saleUpdate', 'importData', 'delete'),
				'users'=>array('*'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
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
		$this->subPageTitle = 'Thông tin đăng ký';
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
		$this->subPageTitle = 'Thêm đăng ký tư vấn';
		$model = new PreregisterUser;
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['PreregisterUser']))
		{
			$preUserValues = $_POST['PreregisterUser'];
			$model->attributes = $preUserValues;
            $model->sale_user_id = Yii::app()->user->id;
			if($model->save()){
				$this->redirect(array('index'));
			}
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$this->subPageTitle = 'Sửa đăng ký tư vấn';
		$model = $this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['PreregisterUser']))
		{
			$preUserValues = $_POST['PreregisterUser'];
			$model->attributes = $preUserValues;
			if($model->save()){
				$this->redirect(array('index'));
			}
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Sale update of preregister course
	 */
	public function actionSaleUpdate($id)
	{
		$this->subPageTitle = 'Sửa đăng ký tư vấn';
		$model = $this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if(isset($_POST['PreregisterUser']))
		{
			$preUserValues = $_POST['PreregisterUser'];
			if(trim($preUserValues['last_sale_date'])==''){
				unset($preUserValues['last_sale_date']);
			}
			$model->attributes = $preUserValues;
			if($model->save()){
				$this->redirect(array('index'));
			}
		}
		$this->render('saleForm',array(
			'model'=>$model,
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
		if($model->deleted_flag==0){
			$model->deleted_flag = 1;//Set deleted flag before delete
			$model->save();
			$this->redirect(array('/admin/preregisterUser'));
		}else{
			$model->delete();//Delete forever this User
		}
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/admin/preregisterUser'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->subPageTitle = 'Danh sách đăng ký tư vấn';
		$this->loadJQuery = false;//Not load jquery
		$model = new PreregisterUser('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['PreregisterUser'])){
			$model->attributes=$_GET['PreregisterUser'];
			if(isset($_GET['PreregisterUser']['created_date'])){
				$model->created_date = Common::convertDateFilter($_GET['PreregisterUser']['created_date']);//Created date filter
			}
			if(isset($_GET['PreregisterUser']['birthday'])){
				$model->birthday = Common::convertDateFilter($_GET['PreregisterUser']['birthday']);//Birthday filter
			}
		}
		$model->deleted_flag = 0;
		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
			$model->deleted_flag = 1;
		}
		$model->getDbCriteria()->order = 'created_date DESC';
		$this->render('index',array(
			'model'=>$model,
		));
	}
    
    public function actionImportData(){
        $columns = array(
            'fullname',
            'birthday',
            'source',
            'phone',
            'email',
            'sale_note',
            'planned_schedule',
            'planned_course_package',
        );
        
        if (isset($_FILES['spreadsheet'])){
            if ($_FILES['spreadsheet']['name'] && !$_FILES['spreadsheet']['error']){
                $uploadedFile = $_FILES['spreadsheet']['name'];
                $extension = strtoupper(pathinfo($uploadedFile, PATHINFO_EXTENSION));
                if ($extension == 'XLS' || $extension == 'XLSX'){
                    try{
                        $spreadsheet = $_FILES['spreadsheet']['tmp_name'];
                        $inputFileType = PHPExcel_IOFactory::identify($spreadsheet);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $phpExcel = $objReader->load($spreadsheet);
                        
                        $sheet = $phpExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow();
                        $highestColumn = $sheet->getHighestColumn();
                        $colCount = sizeof($columns);
                        
                        if (PHPExcel_Cell::columnIndexFromString($highestColumn) >= $colCount){
                            $transaction = Yii::app()->db->beginTransaction();
                            
                            try{
                                $rowData = $sheet->rangeToArray('A2:' . $highestColumn . $highestRow, NULL, TRUE, FALSE);
                                foreach ($rowData as $row){
                                    $attributes = array();
                                    for ($i = 0; $i < $colCount; $i++){
                                        $attributes[$columns[$i]] = $row[$i];
                                    }
                                    $preregisterUser = new PreregisterUser;
                                    $preregisterUser->attributes = $attributes;
                                    
                                    if (!$preregisterUser->save()){
                                        throw new Exception("Error saving records");
                                    }
                                }
                                $transaction->commit();
                                $this->renderJSON(array(
                                    'success'=>true,
                                ));
                            } catch (Exception $se){
                                $transaction->rollBack();
                                $this->renderJSON(array(
                                    'success'=>false,
                                    'error'=>'error_saving_records',
                                ));
                            }
                        }
                    } catch (Exception $e){
                        $this->renderJSON(array(
                            'success'=>false,
                            'error'=>'error_processing_file',
                        ));
                    }
                } else {
                    $this->renderJSON(array(
                        'success'=>false,
                        'error'=>'file_extension_not_allowed',
                    ));
                }
            } else {
                $this->renderJSON(array(
                    'success'=>false,
                    'error'=>$_FILES['spreadsheet']['error'],
                ));
            }
        } else {
            $this->renderJSON(array(
                'success'=>false,
                'error'=>'no_file_found',
            ));
        }
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return PreregisterUser the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=PreregisterUser::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param PreregisterUser $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='preregister-user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
