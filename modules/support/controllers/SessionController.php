<?php

class SessionController extends Controller
{
	public function actionIndex()
	{
        $this->redirect('/support/session/nearest');
	}
	
	//Nearest sessions
	public function actionNearest()
	{
        $this->subPageTitle = 'Buổi học gần nhất';
        $this->loadJQuery = false;//Not load jquery
        $model= new Session('searchNearestSession');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
        }

		$this->render('nearest',array(
            'model'=>$model,
        ));
	}
	
	//On working sessions
	public function actionActive()
	{
        $this->subPageTitle = 'Buổi học đang diễn ra';
        $this->loadJQuery = false;//Not load jquery
        $model= new Session('searchActiveSession(1)');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Session'])){
            $model->attributes=$_GET['Session'];
        }
        $this->render('active',array(
            'model'=>$model,
        ));
	}

}