<?php

/*
 * class CourseReportController
 * */
class CourseReportController extends Controller
{
    /*
     * action Index
     * */
    public function actionCourse($id){
        $this->subPageTitle = Yii::t('lang', 'course_report');

        $course = Course::model()->with('reports', 'reports.student')->findByPk($id);
        $this->render('course', array(
            'course'=>$course,
            'reports'=>$course->reports,
        ));
    }
    
	public function actionCreate(){
        $this->subPageTitle = "New Progress Report";
        
        $report = new CourseReport;
        
        if (isset($_REQUEST['cid'])){
            $course = Course::model()->findByPk($_REQUEST['cid']);
            if ($course == null){
                throw new CHttpException(404, 'The requested page is not found!');
            }

            if (isset($_FILES['report_file'])){
                //we only have one student per course now
                //will rework if there are more in the future
                $report->course_id = $_REQUEST['cid'];
                $report->student_id = $course->assignedStudents()[0];
                $report->report_date = date('Y-m-d');
                $report->reporting_teacher = Yii::app()->user->id;
                $report->report_type = $_POST['CourseReport']['report_type'];

                $uploadedFile = $_FILES['report_file'];
                if ($report->handleReportFileUpload($uploadedFile) && $report->save()){
                    $this->redirect("/teacher/courseReport/course/id/".$report->course_id);
                } else {
                    $this->render('create', array(
                        'error'=>"There was an unexpected error happened. Please try again later",
                        'course'=>$course,
                        'report'=>$report,
                    ));
                }
            }
            
            $this->render('create', array(
                'course'=>$course,
                'report'=>$report,
            ));
        } else {
            throw new CHttpException(400, "Invalid request!");
        }
    }
    
    public function actionUpdate($id){
        $report = CourseReport::model()->with('course')->findByPk($id);

        if ($report == null){
            throw new CHttpException(404, 'The requested page is not found!');
        }

        if (isset($_FILES['report_file'])){
            $uploadedFile = $_FILES['report_file'];
            if ($report->handleReportFileUpload($uploadedFile) && $report->save()){
                $this->redirect("/teacher/courseReport/course/id/".$report->course_id);
            } else {
                $this->render('create', array(
                    'error'=>"There was an unexpected error happened. Please try again later",
                    'course'=>$course,
                    'report'=>$report,
                ));
            }
        }

        $this->render('update', array(
            'course'=>$report->course,
            'report'=>$report,
        ));
    }
}
?>