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
}
