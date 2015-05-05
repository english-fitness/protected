<?php

class SupportController extends Controller
{
	public function actionIndex()
	{
		$this->redirect('/student/class/nearestSession');
		// $this->subPageTitle = 'Hướng dẫn sử dụng';
		// $this->render('index');
	}

}