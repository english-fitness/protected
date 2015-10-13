<?php

class UserController extends Controller
{
    public function actionAjaxSearch(){
        if (isset($_REQUEST['keyword'])){
            if (isset($_REQUEST['role'])){
                $role = $_REQUEST['role'];
            } else {
                $role = null;
            }
            $results = User::model()->searchUsersToAssign($_REQUEST['keyword'], $role);
            $this->renderJSON(array("results"=>$results));
        }
    }

    public function actionGetName(){
        if (isset($_REQUEST['id'])){
            $id = $_REQUEST['id'];
            if (is_array($id)){
                $condition = "id IN (" . implode(",", $id) . ")";
            } else {
                $condition = "id = " . $id;
            }
            $criteria = new CDbCriteria;
            $criteria->select = array("id", "firstname", "lastname");
            $criteria->condition = $condition;
            $users = User::model()->findAll($criteria);
            $name = array();
            foreach ($users as $user) {
                $name[$user->id] = $user->fullname();
            }
            $this->renderJSON(array("users"=>$name));
        }
    }
}
