<?php

class PackageController extends Controller
{

	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('list','view','add','edit','delete'),
				'users'=>array('*'),
				'expression' => 'Yii::app()->user->isAdmin()',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * list setting header page
	 */
	public function actionIndex()
	{
        $model = new CoursePackage('search');
        $model->unsetAttributes();  // clear any default values
        $params = array(
            'model'=>$model
        );
        $this->render('list',$params);
	}
	
	/**
	 * update setting header page
	 */
	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		
		if(isset($_POST['CoursePackage'])) {
			$model->attributes = $_POST['CoursePackage'];
			if($model->save()) {
                $this->redirect(array('index'));
			}
		}
		
		$params = array(
			'model'=>$model
		);
		$this->render('update',$params);
	}
	
	/**
	 * add setting header page
	 */
	public function actionAdd()
	{
		$model = new CoursePackage();
		if(isset($_POST['CoursePackage'])) {
			$model->attributes = $_POST['CoursePackage'];
			if($model->save()) {
				$this->redirect(array('index'));
			}
		}
		
		$params = array(
			'model'=> $model,
		);
		
		$this->render('add',$params);
	}
	
	/**
	 * view header page config 
	 */
	public function actionView($id) 
	{
		$model = $this->loadModel($id);
		$this->render('view',array('model'=>$model));
	}
	
	/**
	 * view header page config
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$model->delete();
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	private function loadModel($id)
	{
		$model=CoursePackage::model()->findByAttributes(array('id'=>$id));
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}


}
