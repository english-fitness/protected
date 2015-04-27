<?php

/* Test Condition */
class TestCondition extends Controller {

	public  $subPageTitle = 'Kiểm tra loa, mic';
    /*  Action Index*/
    public function actionIndex() {
        $this->render("student.views.testCondition.index");
    }

    /* test mic action */
    public function actionTestMic() {
        Yii::app()->session["testMic"] = true;
    }
}

?>