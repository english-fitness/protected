<?php

class DefaultController extends CController
{
	public function actionIndex()
	{
		$this->redirect('/teacher/class/nearestSession');
	}
}