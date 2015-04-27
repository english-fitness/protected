<?php

class SupportController extends Controller
{
	public function actionIndex()
	{
		$this->subPageTitle = 'Hướng dẫn sử dụng';
		$this->render('index');
	}

}