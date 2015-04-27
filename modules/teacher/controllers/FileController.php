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
        $this->subPageTitle = 'Quản lý tài liệu, giáo án';
        $this->render('index');
    }

}
?>