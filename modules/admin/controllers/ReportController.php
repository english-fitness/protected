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
				'actions'=>array('index','session', 'test', 'export'),
				'users'=>array('*'),
			),
			
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    
    private static function getReportHeader($report){
        switch($report){
            case 'session':
                return array(
                    array('name'=>'Session ID','width'=>'10'),
                    array('name'=>'Date','width'=>'15'),
                    array('name'=>'Session Time (Hanoi)','width'=>'20'),
                    array('name'=>'Session Time (PH)','width'=>'20'),
                    array('name'=>'Tutor name','width'=>'15'),
                    array('name'=>'Student name','width'=>'25'),
                    array('name'=>'Lession Type','width'=>'15'),
                    array('name'=>'Status','width'=>'12'),
                    array('name'=>'Tools','width'=>'10'),
                    array('name'=>'Remarks','width'=>'25'),
                );
                break;
            default:
                break;
        }
    }

	public function actionIndex(){
        $this->subPageTitle = "Báo cáo";
        
        $this->render('index');
    }
    
    public function actionSession(){
        $this->subPageTitle = "Buổi học";
        
        if (isset($_REQUEST['type'])){
            $sessions = ReportBuilder::getSessionReport($_REQUEST);
            $requestParams = array_splice($_REQUEST, 0, 1);
            $this->render('session', array(
                "sessions"=>$sessions,
                "requestParams"=>$_REQUEST,
            ));
        } else {
            $this->render('session');
        }
    }
    
    public function actionExport(){
        if (isset($_REQUEST['report'])){
            $report = $_REQUEST['report'];
            
            switch ($report){
                case 'session':
                    self::sendSessionReport($_REQUEST);
                    break;
                default:
                    break;
            }
        } else {
            throw new CHttpException(400, 'Bad request');
        }
    }
    
    public function sendSessionReport($requestParams){
        $data = ReportBuilder::getSessionReportExportData($requestParams);
        
        $phpExcel = new PHPExcel();
        
        $phpExcel->setActiveSheetIndex(0);
        
        $col = 0;
        //remember row index is 1-based
        $row = 1;
        
        $headers = self::getReportHeader('session');
        
        $activeSheet = $phpExcel->getActiveSheet();
        
        foreach($headers as $header){
            $activeSheet->setCellValueByColumnAndRow($col, $row, $header['name']);
            $activeSheet->getColumnDimensionByColumn($col)->setWidth($header['width']);
            
            $col++;
        }
        
        $row = 2;
        foreach($data as $record){
            $col = 0;
            foreach ($record as $value){
                $activeSheet->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }
        
        $activeSheet->getStyle( $phpExcel->getActiveSheet()->calculateWorksheetDimension() )
        ->getAlignment()
        ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        
        $activeSheet->setSelectedCells('A1');
        
        ob_end_clean();
        ob_start();
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . ReportBuilder::getReportDate($requestParams) . '.xls"');
        header('Cache-Control: max-age=0');
        $writer = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel5');
        $writer->save('php://output');
    }
    
    public function actionModules(){
        $ex = '';
        foreach (get_loaded_extensions() as $key=>$val){
            $ex .= $key . ": " . $val . "<br>";
        }
        exit($ex);
    }
}
