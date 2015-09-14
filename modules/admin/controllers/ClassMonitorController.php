<?php

/*
 * class ClassMonitorController
 * */
class ClassMonitorController extends Controller
{
    /*
     * action Index
     * */
    public function actionIndex()
    {
        $this->subPageTitle = 'Theo dõi lớp học';
        $this->render('index');
    }

}
?>