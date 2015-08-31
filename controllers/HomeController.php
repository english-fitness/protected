<?php

class HomeController extends Controller
{
    public  function  init()
    {
        $this->layout = '//layouts/blank';
        Yii::app()->language = 'vi';
    }
    
    public function actionIndex(){
        $this->render('landing');
    }
}
