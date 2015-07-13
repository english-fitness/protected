<?php

/*
 * class FileController
 * */
class FileController extends Controller
{
    /*
     * action Index
     * */
    public function actionIndex($dir = null)
    {
        $this->subPageTitle = 'Thư viện';
        $this->render('index');
    }

}
?>