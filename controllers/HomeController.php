<?php

class SiteController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'vi';//Config admin language is Vietnamese
    }
    
    public function actionIndex(){
        $this->render('landing');
    }
    
}
