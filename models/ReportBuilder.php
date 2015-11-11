<?php
class ReportBuilder {
    public static function reportOptions(){
        return array(
            'session'=>'Buổi học',
            'userRegistration'=>'Học sinh đăng ký',
        );
    }

    private static function getSessionReportCriteria($requestParams, $relations=null){
        $dateConstraint = self::getDateConstraint($requestParams, 'plan_start');

        $subject_id = isset($requestParams['subject'])
                      && $requestParams['subject'] != 'all'
                      && is_numeric($requestParams['subject']) ? $requestParams['subject'] : '';
        if ($subject_id != ''){
            $subjectCondition = "subject_id = " . $subject_id . " ";
        } else {
            $subjectCondition = '';
        }

        $order = isset($requestParams['order']) && in_array(strtoupper($requestParams['order']), array('ASC', 'DESC')) ? $requestParams['order'] : '';
        if ($order != ''){
            $order = 'plan_start '.$order;
        } else {
            $order = 'plan_start, t.id ASC';
        }

        if ($relations == null){
            $relations = array(
                "note"=>array(
                    'select'=>array('using_platform', 'note'),
                ),
                "course"=>array(
                    'select'=>array('subject_id'),
                    'condition'=>$subjectCondition,
                ),
                "teacherFine",
                "teacher"=>array(
                    'select'=>array('firstname', 'lastname'),
                )
            );
        }

        $criteria = new CDbCriteria();
        $criteria->addCondition($dateConstraint);
        $criteria->order = $order;
        $criteria->with= $relations;

        return $criteria;
    }

    public static function getSessionReport($requestParams){
        $criteria = self::getSessionReportCriteria($requestParams);

        return new CActiveDataProvider('Session', array(
            'criteria'=>$criteria,
            'pagination'=>array('pageVar'=>'page', 'pageSize'=>20),
            'sort'=>array('sortVar'=>'sort'),
        ));
    }

    public static function getSessionReportExportData($requestParams){
        $criteria = self::getSessionReportCriteria($requestParams, $relations);

        $sessions = Session::model()->findAll($criteria);

        $reportData = array();

        foreach ($sessions as $data) {
            $reportData[] = array(
                $data->id,
                date("d/m/Y", strtotime($data->plan_start)),
                date("H:i", strtotime($data->plan_start)),
                date("H:i", strtotime("+1 hour", strtotime($data->plan_start))),
                $data->teacher->firstname,
                implode(", ", $data->getAssignedStudentsArrs()),
                self::getSessionTypeDisplay($data->type),
                self::getSessionStatusDisplay($data->status),
                self::getSessionToolDisplay($data),
                $data["teacher_paid"] ? "Paid" : ($data["teacher_paid"] === "0" ? "Unpaid" : ""),
                $data->status == Session::STATUS_CANCELED ? $data->status_note : ($data->note != null ? $data->note->note : ""),
            );
        }

        return $reportData;
    }

    private static function getUserRegistrationCriteria($requestParams){
        $criteria = new CDbCriteria;
        $criteria->alias = 't';

        $dateConstraint = self::getDateConstraint($requestParams, $criteria->alias.'.created_date');

        $params = array();
        $extraConditions = array();

        if (!empty($requestParams['source']) && $requestParams['source'] != 'all'){
            $source = $requestParams['source'];
            if ($source == 'allOnline'){
                $extraConditions[] = "source LIKE '%online%'";
            } else {
                $extraConditions[] = "source = :source";
                $params[":source"] = $requestParams['source'];
            }
        }

        if (!empty($requestParams['saleUserId']) && $requestParams['saleUserId'] != 'all'){
            $extraConditions[] = "sale_user_id = :sale_user_id";
            $params[":sale_user_id"] = $requestParams['saleUserId'];
        }

        $criteria->addCondition($dateConstraint);
        if (!empty($extraConditions)){
            foreach ($extraConditions as $condition) {
                $criteria->addCondition($condition);
            }
        }
        if (!empty($params)){
            $criteria->params = $params;
        }
        $criteria->addCondition($criteria->alias.'.deleted_flag = 0');

        $criteria->with = array(
            'saleUser'=>array(
                'select'=>array('firstname', 'lastname'),
            )
        );

        return $criteria;
    }

    public static function getUserRegistrationReport($requestParams){
        $criteria = self::getUserRegistrationCriteria($requestParams);

        return new CActiveDataProvider('PreregisterUser', array(
            'criteria'=>$criteria,
            'pagination'=>array('pageVar'=>'page', 'pageSize'=>20),
            'sort'=>array('sortVar'=>'sort'),
        ));

    }
    
    public static function getUserRegistrationReportExportData($requestParams){
        $criteria = self::getUserRegistrationCriteria($requestParams);
        
        $registrations =  PreregisterUser::model()->findAll($criteria);

        $reportData = array();
        $html2Text = new Html2Text();

        foreach ($registrations as $data) {
            $reportData[] = array(
                $data->fullname,
                $data->source,
                Common::formatPhoneNumber($data->phone),
                $data->email,
                date("d/m/Y", strtotime($data["created_date"])),
                PreregisterUser::careStatusOptions($data["care_status"]),
                $data->saleUser != null ? $data->saleUser->fullname() : "",
                $html2Text->setHtml($data["sale_note"])->getText(),
            );
        }

        return $reportData;
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
                $dateTo = date('Y-m-d 00:00:00', strtotime('+1 day', strtotime($requestParams['dateTo'])));
                $dateConstraint = $columnName . " >= '" . $dateFrom . "' AND " . $columnName . " < '" . $dateTo . "'";
                break;
            default:
                break;
        }
        
        return $dateConstraint;
    }
    
    private static function getSessionTypeDisplay($type){
        return array(
            Session::TYPE_SESSION_TESTING => 'Test session',
            Session::TYPE_SESSION_TRAINING=>'Trial session',
            Session::TYPE_SESSION_NORMAL=>'Regular session',
        )[$type];
    }

    private static function getSessionStatusDisplay($status){
        return array(
            Session::STATUS_PENDING => 'Pending',
            Session::STATUS_APPROVED => 'Approved',
            Session::STATUS_WORKING => 'Ongoing',
            Session::STATUS_ENDED => 'Ended',
            Session::STATUS_CANCELED => 'Cancelled',
        )[$status];
    }

    private static function getSessionToolDisplay($session){
        if ($session->status == Session::STATUS_CANCELED || $session->note == null){
            return "X";
        } else {
            switch ($session->note->using_platform) {
                case '1':
                    return "Platform";
                    break;
                case '0':
                    return "Skype";
                    break;
                default:
                    return "X";
                    break;
            }
        }
    }
}
?>