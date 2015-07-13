<?php

/* Test Condition */
class TestCondition extends Controller {

	public  $subPageTitle = 'Kiểm tra loa, mic';
    /*  Action Index*/
    public function actionIndex() {
        $this->render(Yii::t('lang', 'test_speaker_mic_view'));
    }

    /* test mic action */
    public function actionTestMic() {
        Yii::app()->session["testMic"] = true;
    }
}

?>