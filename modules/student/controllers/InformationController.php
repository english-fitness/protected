<?php
class InformationController extends Controller
{
	//Tuition information
    public function actionTuition($type = null)
    {
        if($type==null){
            $user = Yii::app()->user->getData();
            $this->redirect(array('/student/information/tuition','type'=>$user->getStatusNewOrOld()));
        }
        $this->render('tuition');
    }

}