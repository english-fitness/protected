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
        $this->subPageTitle = 'File Manager';
        $this->render('index');
    }

}
?>