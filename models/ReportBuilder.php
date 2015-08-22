<?php
class ReportBuilder {
    public static function reportOptions(){
        return array(
            'session'=>'Buổi học',
            'userRegistration'=>'Học sinh đăng ký',
        );
    }
    
    public static function getSessionReport($requestParams){
        $dateConstraint = self::getDateConstraint($requestParams, 'sessions.plan_start');
        $countQuery =   "SELECT count(sessions.id) FROM tbl_session sessions JOIN tbl_course c
                        ON sessions.course_id = c.id
                        WHERE c.subject_id = 55
                        AND sessions.deleted_flag = 0 
                        AND " . $dateConstraint;
        
        $count = Yii::app()->db->createCommand($countQuery)->queryScalar();
        
        $query = self::getSessionReportQuery($dateConstraint);
        
        return new CSqlDataProvider($query, array(
            'totalItemCount'=>$count,
			'pagination'=>array(
				'pageSize'=>20,
			),
            'keyField'=>'session_id',
        ));
    }
    
    public static function getSessionReportExportData($requestParams){
        $dateConstraint = self::getDateConstraint($requestParams, 'sessions.plan_start');
        $query = self::getSessionReportQuery($dateConstraint);
        
        return Yii::app()->db->createCommand($query)->queryAll();
    }
    
    public static function getUserRegistrationReport($requestParams){
        $dateConstraint = self::getDateConstraint($requestParams, 'created_date');
        $countQuery = "SELECT count(id) FROM tbl_preregister_user
                       WHERE deleted_flag = 0
                       AND " . $dateConstraint;
                       
        $count = Yii::app()->db->createCommand($countQuery)->queryScalar();
        
        $query = self::getUserRegistrationReportQuery($dateConstraint);
        
        return new CSqlDataProvider($query, array(
            'totalItemCount'=>$count,
            'pagination'=>array(
                'pageSize'=>20,
            ),
            'keyField'=>'email',
        ));
    }
    
    public static function getUserRegistrationReportExportData($requestParams){
        $dateConstraint = self::getDateConstraint($requestParams, 'created_date');
        $query = self::getUserRegistrationReportQuery($dateConstraint);
        
        return Yii::app()->db->createCommand($query)->queryAll();
    }
    
    public static function getReportDate($requestParams){
        $type= $requestParams['type'];
        switch ($type){
            case 'date':
                return date('d-m-Y', strtotime($requestParams['date']));
                break;
            case 'week':
                return 'week_' . date('W_Y');
                break;
            case 'month':
                $month = $requestParams['month'];
                $year = $requestParams['year'];
                if ($month < 10){
                    $month = "0" . $month;
                }
                return date('M_Y', strtotime($year . '-' . $month . '-01'));
                break;
            case 'range':
                $dateFrom = date('Y-m-d', strtotime($requestParams['dateFrom']));
                $dateTo = date('Y-m-d', strtotime($requestParams['dateTo']));
                return date('dMY', strtotime($dateFrom)) . '-' . date('dMY', strtotime($dateTo));
                break;
            default:
                break;
        }
    }
    
    private static function getDateConstraint($requestParams, $columnName){
        $type= $requestParams['type'];
        switch ($type){
            case 'date':
                $dateTimestamp =  strtotime($requestParams['date']);
                $date = date('Y-m-d 00:00:00', $dateTimestamp);
                $dateAfter = date('Y-m-d 00:00:00', strtotime('+1 days', $dateTimestamp));
                $dateConstraint = $columnName . " >= '" . $date . "' AND " . $columnName . " < '" . $dateAfter . "'";
                break;
            case 'week':
                $week = $requestParams['week'];
                $year = date('Y');
                if ($week < 10){
                    $week = "0" . $week;
                }
                $dateStartTimestamp = strtotime($year . 'W' . $week);
                $dateStart = date('Y-m-d 00:00:00', $dateStartTimestamp);
                $dateEnd = date('Y-m-d 00:00:00', strtotime('+7 days', $dateStartTimestamp));
                $dateConstraint = $columnName . " >= '" . $dateStart . "' AND " . $columnName . " < '" . $dateEnd . "'";
                break;
            case 'month':
                $month = $requestParams['month'];
                $year = $requestParams['year'];
                if ($month < 10){
                    $month = "0" . $month;
                }
                $monthStart = $year . '-' . $month . '-01 00:00:00';
                $monthEnd = date($year . '-' . $month . '-t 00:00:00');
                $dateConstraint = $columnName . " >= '" . $monthStart . "' AND " . $columnName . " < '" . $monthEnd . "'";
                break;
            case 'range':
                $dateFrom = date('Y-m-d 00:00:00', strtotime($requestParams['dateFrom']));
                $dateTo = date('Y-m-d 00:00:00', strtotime($requestParams['dateTo']));
                $dateConstraint = $columnName . " >= '" . $dateFrom . "' AND " . $columnName . " < '" . $dateTo . "'";
                break;
            default:
                break;
        }
        
        return $dateConstraint;
    }
    
    private static function getSessionReportQuery($dateConstraint){
        return "SELECT
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
                WHERE course.subject_id = 55
                AND " . $dateConstraint . " " .
                "AND sessions.deleted_flag = 0
                ORDER BY sessions.plan_start ASC";
    }
    
    private static function getUserRegistrationReportQuery($dateConstraint){
        return  "SELECT fullname, phone, email, sale_note, planned_schedule, planned_course_package FROM tbl_preregister_user
                WHERE deleted_flag = 0
                AND " . $dateConstraint . " " .
                "ORDER BY created_date DESC";
    }
}
?>