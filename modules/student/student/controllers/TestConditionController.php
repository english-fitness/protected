<?php

/* Test Controller */
class TestConditionController extends TestCondition
{
        public function actionUserUpdateStatus()
        {
            if(isset($_POST['update']))
            {
                $userLogin  = Yii::app()->user->getData();

                if($userLogin->status <User::STATUS_ENOUGH_AUDIO)
                {
                    $user = User::model()->findByPk(Yii::app()->user->id);
                    $user->status = User::STATUS_ENOUGH_AUDIO;
                    $user->save();
                }


            }

        }
}

?>