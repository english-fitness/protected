<?php

/*
 * class CourseReportController
 * */
class CourseReportController extends Controller
{
    public function actionCourse($id){
    	$this->subPageTitle = Yii::t('lang', 'course_report');

        $baseAssetsUrl = $this->baseAssetsUrl;
        $cs = Yii::app()->getClientScript();
        $cs->registerScriptFile($baseAssetsUrl."/js/bootstrap/bootstrap.min.js");
        $cs->registerScriptFile($baseAssetsUrl."/js/bootstrap/bootstrap-dialog.min.js");
        $cs->registerCssFile($baseAssetsUrl."/css/bootstrap/bootstrap-dialog.min.css");

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