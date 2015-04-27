<?php

class UserController extends Controller
{

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            //'postOnly + delete', // we only allow deletion via POST request
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions'=>array('getLoggedInUser'),
                'users'=>array('*'),
            ),
            array('deny',),
        );
    }

    public function actionGetLoggedInUser()
    {
        $sessionId = Yii::app()->request->getQuery('sessionId', '');
        $session =  Session::model()->findByPk($sessionId);
        $studentIds = $session ? $session->assignedStudents() : array();
        $success = false; $data = array();
        if(isset(Yii::app()->user->id)) {
            $user = Yii::app()->user->getData();
            $role = in_array($user->id, $studentIds) ? User::ROLE_STUDENT : $user->role;
            $data = array('userId'=>$user->id, 'email'=>$user->email, 'firstname'=>$user->firstname,
                    'lastname'=>$user->lastname, 'gender'=>$user->gender,
                    'profile_picture'=>$user->profile_picture, 'role'=>$role);

            $success = true;
        }
        $this->renderJSON(array('success'=>$success, 'user'=>$data));
    }
}
