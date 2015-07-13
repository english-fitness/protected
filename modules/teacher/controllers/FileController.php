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
        $this->subPageTitle = 'My documents';
        $this->render('index');
    }
	
	public function actionPublicLibrary($dir = null)
    {
        $this->subPageTitle = 'Public library';
        $this->render('publicLibrary');
    }

}
?>