<?php

class NewsController extends Controller
{
    public function  init()
    {
        Yii::app()->language = 'vi';//Config admin language is Vietnamese
    }
	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->layout = '//layouts/blank';
		$this->subPageTitle = 'Trang chủ';
		$this->render('/site/index');
	}
}