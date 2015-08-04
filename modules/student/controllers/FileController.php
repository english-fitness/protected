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
        $this->subPageTitle = Yii::t('lang',"student_documents");
        $this->render('index');
    }
	
	public function actionPublicLibrary($dir = null)
    {
        $this->subPageTitle = Yii::t('lang',"public_library");
        $this->render('publicLibrary');
    }

}
?>