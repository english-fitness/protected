<?php

class HomeController extends Controller
{
    public  function  init()
    {
        $this->layout = '//layouts/blank';
        Yii::app()->language = 'vi';//Config admin language is Vietnamese
    }
    
    public function actionIndex(){
        $this->render('landing');
    }
    
}
