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
}
