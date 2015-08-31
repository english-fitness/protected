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
                    'value'=>'$data["session_id"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:80px'),
                ),
                array(
                    'header'=>'Session Date',
                    'value'=>'$data["session_date"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Session Time (Hanoi)',
                    'value'=>'$data["session_time_hn"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Session Time (PH)',
                    'value'=>'$data["session_time_ph"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Tutor name',
                    'value'=>'$data["session_tutor"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Student name',
                    'value'=>'$data["session_student"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Lesson Type',
                    'value'=>'$data["session_type"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
                ),
                array(
                    'header'=>'Status',
                    'value'=>'$data["session_status"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Tool',
                    'value'=>'$data["session_tool"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Payment Status',
                    'value'=>'$data["paid_session"]',
                    'htmlOptions'=>array('style'=>'text-align:center'),
                ),
                array(
                    'header'=>'Remarks',
                    'value'=>'$data["session_remarks"]',
                    'htmlOptions'=>array('style'=>'text-align:center; width:300px'),
                ),
            ),
        ));
    ?>
</div>
<?php endif;?>