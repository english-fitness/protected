<?php

class HomeController extends Controller
{
    private $currentMainLanding = "landing2";
    private $availableLanding = array("landing2");

    public function init()
    {
        Yii::app()->language = 'vi';
        $this->layout = '//layouts/landing';
    }

    public function actionIndex(){
        $actionMethod = "action".$this->currentMainLanding;
        $this->{$actionMethod}();
        exit();
    }

    public function actionLanding()
    {
        $this->actionIndex();
    }
    
    public function actionLanding1(){
        if (!in_array("landing1", $this->availableLanding) && !isset($_GET['go'])){
            $this->actionIndex();
        }

        $baseAssetsUrl = $this->baseAssetsUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseAssetsUrl."/css/bootstrap/bootstrap.min.css");
        $cs->registerCssFile($baseAssetsUrl."/home/style/landing.css");
        $this->render('landing');
    }

    public function actionLanding2(){
        if (!in_array("landing2", $this->availableLanding) && !isset($_GET['go'])){
            $this->actionIndex();
        }

        $baseAssetsUrl = $this->baseAssetsUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseAssetsUrl."/css/bootstrap/bootstrap.min.css");

        $params = array(
            'promotionEnd'=>"30/11/2015"
        );

        $device = new Mobile_Detect;
        if (($device->isMobile() && !$device->isTablet()) || (isset($_GET['device']) && $_GET['device']=='mobile')){
            $this->device = 'mobile';
            $cs->registerCssFile($baseAssetsUrl."/home/style/landing2/landing-mobile.css");
            $cs->registerScriptFile($baseAssetsUrl."/home/js/landing2/landing-m.js");
            $this->render('landing2-mobile', array('params'=>$params));
        } else {
            $cs->registerCssFile($baseAssetsUrl."/home/style/landing2/landing.css");
            $cs->registerScriptFile($baseAssetsUrl."/home/js/landing2/landing.js");
            $this->render('landing2', array('params'=>$params));
        }
    }

    public function actionLanding3(){
        if (!in_array("landing3", $this->availableLanding) && !isset($_GET['go'])){
            $this->actionIndex();
        }

        $baseAssetsUrl = $this->baseAssetsUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseAssetsUrl."/home/style/landing3/landing.css");
        $cs->registerScriptFile($baseAssetsUrl."/home/js/landing3/landing.js");
        $device = new Mobile_Detect;
        $this->render('landing3');
        // if ($device->isMobile() && !$device->isTablet()){
        //     $this->device = 'mobile';
        //     $this->render('landing3-mobile');
        // }else {
        //     $this->render('landing3');
        // }
    }
}
