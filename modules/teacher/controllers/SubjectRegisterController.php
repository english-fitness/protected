<?php

class SubjectRegisterController extends Controller
{
    public function actionIndex()
    {
		$this->subPageTitle = 'Đăng ký môn dạy';
        $teacher = Teacher::model()->findByPk(Yii::app()->user->id);
        $classSubjects = Subject::model()->generateSubjects();
        $abilitySubjects = $teacher->abilitySubjects();//Subjects ability of teacher
        $this->render("index",array(
            "classSubjects"=>$classSubjects,
            "abilitySubjects"=>$abilitySubjects
        ));
    }

    public function actionAjaxSubjectRegister()
    {
        $success = false;
        $notice = "Đăng ký môn dạy thành công!";
        if(!isset($_POST['abilitySubjects']))
        {
                $_POST['abilitySubjects'] = null;
        }
        $teacher = Teacher::model()->findByPk(Yii::app()->user->id);
        $teacher->saveAbilitySubjects($_POST['abilitySubjects']);
        $success = true;

        $this->renderJSON(array(
            'success' =>$success,
            'htmlTag'=>".noticeForm",
            'notice'=>$notice
        ));
    }
}