<?php

class ReportController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','session', 'test'),
				'users'=>array('*'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex(){
        $this->subPageTitle = "Báo cáo";
        
        $this->render('index');
    }
    
    public function actionSession(){
        $this->subPageTitle = "Buổi học";
        
        if (isset($_REQUEST['type'])){
            $type= $_REQUEST['type'];
            switch ($type){
                case 'date':
                    $dateTimestamp =  strtotime($_REQUEST['date']);
                    $date = date('Y-m-d 00:00:00', $dateTimestamp);
                    $dateAfter = date('Y-m-d 00:00:00', strtotime('+1 days', $dateTimestamp));
                    $dateConstraint = "AND sessions.plan_start >= '" . $date . "' AND sessions.plan_start < '" . $dateAfter . "'";
                    $requestParams = array(
                        "type"=>"date",
                        "date"=>$_REQUEST['date'],
                    );
                    break;
                case 'week':
                    $week = $_REQUEST['week'];
                    $year = date('Y');
                    if ($week < 10){
                        $week = "0" . $week;
                    }
                    $dateStartTimestamp = strtotime($year . 'W' . $week);
                    $dateStart = date('Y-m-d 00:00:00', $dateStartTimestamp);
                    $dateEnd = date('Y-m-d 00:00:00', strtotime('+7 days', $dateStartTimestamp));
                    $dateConstraint = "AND sessions.plan_start >= '" . $dateStart . "' AND sessions.plan_start < '" . $dateEnd . "'";
                    $requestParams = array(
                        "type"=>"week",
                        "week"=>$_REQUEST['week'],
                    );
                    break;
                case 'month':
                    $month = $_REQUEST['month'];
                    $year = $_REQUEST['year'];
                    if ($month < 10){
                        $month = "0" . $month;
                    }
                    $monthStart = date('Y-' . $month . '-01 00:00:00');
                    $monthEnd = date('Y-' . $month . '-t 00:00:00');
                    $dateConstraint = "AND sessions.plan_start >= '" . $monthStart . "' AND sessions.plan_start < '" . $monthEnd . "'";
                    $requestParams = array(
                        "type"=>"month",
                        "month"=>$_REQUEST['month'],
                        "year"=>$_REQUEST['year'],
                    );
                    break;
                case 'range':
                    $dateFrom = date('Y-m-d 00:00:00', strtotime($_REQUEST['dateFrom']));
                    $dateTo = date('Y-m-d 00:00:00', strtotime($_REQUEST['dateTo']));
                    $dateConstraint = "AND sessions.plan_start >= '" . $dateFrom . "' AND sessions.plan_start < '" . $dateTo . "'";
                    $requestParams = array(
                        "type"=>"range",
                        "dateFrom"=>$_REQUEST['dateFrom'],
                        "dateTo"=>$_REQUEST['dateTo'],
                    );
                    break;
                default:
                    break;
            }
        } else {
            $this->render('session');
            exit();
        }
        
        $countQuery =   "SELECT count(sessions.id) FROM tbl_session sessions JOIN tbl_course c
                        ON sessions.course_id = c.id
                        WHERE c.subject_id = 55
                        AND sessions.deleted_flag = 0 " . $dateConstraint;
                        
        $count = Yii::app()->db->createCommand($countQuery)->queryScalar();
        
        $query = "SELECT
                    sessions.id AS 'session_id',
                    DATE_FORMAT(sessions.plan_start, '%d/%m/%Y') AS 'session_date',
                    DATE_FORMAT(sessions.plan_start, '%H:%i') AS 'session_time_hn',
                    DATE_FORMAT(sessions.plan_start  + INTERVAL 1 HOUR, '%H:%i') AS 'session_time_ph',
                    teacher.firstname AS 'session_tutor',
                    CONCAT(student.lastname, ' ' ,student.firstname) AS 'session_student',
                    CASE
                        WHEN sessions.type = 1 THEN 'Regular session'
                        ELSE 'Trial session'
                    END AS 'session_type',
                    CASE
                        WHEN sessions.status = 0 THEN 'Pending'
                        WHEN sessions.status = 1 THEN 'Approved'
                        WHEN sessions.status = 2 THEN 'Active'
                        WHEN sessions.status = 3 THEN 'Ended'
                        WHEN sessions.status = 4 THEN 'Cancelled'
                        ELSE 'N/a'
                    END AS 'session_status',
                    CASE
                        WHEN sessions.status = 4 OR note.note = NULL THEN 'X'
                        WHEN note.using_platform = 1 THEN 'Platform'
                        ELSE 'Skype'
                    END AS 'session_tool',
                    CASE
                        WHEN note.note <> NULL THEN note.note
                        ELSE ''
                    END AS 'session_remarks'
                FROM tbl_session as sessions JOIN (tbl_session_student JOIN tbl_user student ON tbl_session_student.student_id = student.id)
                ON sessions.id = tbl_session_student.session_id
                JOIN tbl_user teacher ON sessions.teacher_id = teacher.id
                JOIN tbl_course course ON sessions.course_id = course.id
                LEFT JOIN tbl_session_note note ON sessions.id = note.session_id
                WHERE course.subject_id = 55 " .
                $dateConstraint . " " .
                "AND sessions.deleted_flag = 0
                ORDER BY sessions.plan_start ASC";
                
        $dataProvider = new CSqlDataProvider($query, array(
            'totalItemCount'=>$count,
			'pagination'=>array(
				'pageSize'=>20,
			),
            'keyField'=>'session_id',
        ));
        
        $this->render('session', array(
            "sessions"=>$dataProvider,
            "requestParams"=>$requestParams,
        ));
    }
    
    public function actionTest(){
        $ex = '';
        foreach (get_loaded_extensions() as $key=>$val){
            $ex .= $key . ": " . $val . "<br>";
        }
        exit($ex);
    }
}
