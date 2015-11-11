<?php
function getSessionTypeDisplay($type){
    return array(
        Session::TYPE_SESSION_TESTING => 'Test session',
        Session::TYPE_SESSION_TRAINING=>'Trial session',
        Session::TYPE_SESSION_NORMAL=>'Regular session',
    )[$type];
}

function getSessionStatusDisplay($status){
    return array(
        Session::STATUS_PENDING => 'Pending',
        Session::STATUS_APPROVED => 'Approved',
        Session::STATUS_WORKING => 'Ongoing',
        Session::STATUS_ENDED => 'Ended',
        Session::STATUS_CANCELED => 'Cancelled',
    )[$status];
}

function getSessionToolDisplay($session){
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
?>
<?php if(isset($sessions)):?>
<div id="reportData">
    <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'dataGridView',
            'dataProvider'=>$sessions,
            'enableHistory'=>true,
            'pager' => array('class'=>'CustomLinkPager'),
            'columns'=>array(
                array(
                    'header'=>'Session ID',
                    'value'=>'$data->id',
                    'htmlOptions'=>array('style'=>'text-align:center; width:80px'),
                ),
                array(
                    'header'=>'Session Date',
                    'value'=>'date("d/m/Y", strtotime($data->plan_start))',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Session Time (Hanoi)',
                    'value'=>'date("H:i", strtotime($data->plan_start))',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Session Time (PH)',
                    'value'=>'date("H:i", strtotime("+1 hour", strtotime($data->plan_start)))',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Tutor name',
                    'value'=>'$data->teacher->firstname',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Student name',
                    'value'=>'implode(", ", $data->getAssignedStudentsArrs())',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Lesson Type',
                    'value'=>'getSessionTypeDisplay($data->type)',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Status',
                    'value'=>'getSessionStatusDisplay($data->status)',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Tool',
                    'value'=>'getSessionToolDisplay($data)',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Payment Status',
                    'value'=>'$data["teacher_paid"] ? "Paid" : ($data["teacher_paid"] === "0" ? "Unpaid" : "")',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Remarks',
                    'value'=>'$data->status == Session::STATUS_CANCELED ? $data->status_note : ($data->note != null ? $data->note->note : "")',
                    'htmlOptions'=>array('style'=>'text-align:center; width:300px'),
                ),
            ),
        ));
    ?>
</div>
<?php endif;?>