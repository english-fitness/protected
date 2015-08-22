<style>
.grid-view table.items tr td {
    vertical-align:top;
}
</style>
<div class="col col-lg-6" style="margin-bottom:20px; margin-left:-15px">
    <h2 class="page-title mT10">Báo cáo</h2>
</div>
<?php 
    $actionParams = array();
    
    if (isset($records)){
        $actionParams['recordCount'] = $records->totalItemCount;
        $actionParams['maxRecordNumber'] = 1000;
    }
    
    if (isset($requestParams)){
        $actionParams['requestParams'] = $requestParams;
        $report = $requestParams['report'];
    }
    
    $this->renderPartial('widgets/dateSelector', $actionParams);
    
    if (isset($report)){
        switch($report){
            case 'session':
                $this->renderPartial('session', array('sessions'=>$records));
                break;
            case 'userRegistration':
                $this->renderPartial('userRegistration', array('users'=>$records));
                break;
            default:
                break;
        }
    }
?>