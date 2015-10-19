<?php

class HomeController extends Controller
{
    public  function  init()
    {
        Yii::app()->language = 'vi';
        $this->layout = '//layouts/landing';
    }
    
    public function actionIndex(){
        $this->render('landing');
    }

    public function actionLanding2(){
        $device = new Mobile_Detect;
        if ($device->isMobile() && !$device->isTablet()){
            $this->device = 'mobile';
            $this->render('landing2-mobile');
        } else {
            $this->render('landing2');
        }
    }
}
