<?php

/* Test Condition */
class TestCondition extends Controller {

	public  $subPageTitle = 'Test speaker and microphone';
    /*  Action Index*/
    public function actionIndex() {
        $this->render("teacher.views.testCondition.index");
    }

    /* test mic action */
    public function actionTestMic() {
        Yii::app()->session["testMic"] = true;
    }
}

?>