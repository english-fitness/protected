<?php

class DefaultController extends Controller
{
	public function actionIndex()
	{
		$this->redirect(array('/admin/session/nearest'));
	}
	
	//Not allow permission
	public function actionPermission()
	{
		$this->subPageTitle = 'Phân quyền người dùng';
		$userId = Yii::app()->user->id;
		$user = User::model()->findByPk($userId);
		$permissions = Permission::model()->findAll(array('order'=>'controller ASC, action ASC'));
		$assignedPermissionIds = Permission::model()->getUserPermissions($userId);
		$this->render('permission',array(
			'permissions'=>$permissions,
			'user'=>$user,
			'assignedPermissionIds' => $assignedPermissionIds,
		));
	}
}