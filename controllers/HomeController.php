<?php

class HomeController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'vi';
    }
    
    public function actionIndex(){
        $this->layout = '//layouts/landing';
        $this->render('landing');
    }

    public function actionLanding2(){
    	$this->layout = '//layouts/landing';
        $this->render('landing2');
    }
}
