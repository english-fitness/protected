<?php

/* Test Condition */
class TestCondition extends Controller {

	public  $subPageTitle = 'Check speaker, mic';
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