<?php

/*
 * class CourseReportController
 * */
class CourseReportController extends Controller
{
    public function actionCourse($id){
    	$this->subPageTitle = Yii::t('lang', 'course_report');

        $baseUrl = Yii::app()->baseUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseUrl."/media/js/bootstrap/bootstrap.min.js");
        $cs->registerScriptFile($baseUrl."/media/js/bootstrap/bootstrap-dialog.min.js");
        $cs->registerCssFile($baseUrl."/media/css/bootstrap/bootstrap-dialog.min.css");

        $course = Course::model()->with('reports', 'reports.reportingTeacher')->findByPk($id);
        $this->render('course', array(
        	'course'=>$course,
        	'reports'=>$course->reports,
    	));
    }
    
    public function actionComment(){
    	$success = false;
        if (isset($_REQUEST['id']) && isset($_POST['comment'])){
        	$report = CourseReport::model()->findByPk($_REQUEST['id']);
        	if ($report != null){
        		$report->student_comment = $_POST['comment'];
        		if ($report->save()){
        			$success = true;
        		}
        	}
        }
        $this->renderJSON(array('success'=>$success));
    }

    public function actionGetComment($id){
    	$report = CourseReport::model()->findByPk($id);
    	$this->renderJSON(array('comment'=>$report->student_comment));
    }
    
}
?>