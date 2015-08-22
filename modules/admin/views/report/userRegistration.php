<?php if(isset($users)):?>
<div id="reportData">
    <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id'=>'dataGridView',
            'dataProvider'=>$users,
            'enableHistory'=>true,
            'pager' => array('class'=>'CustomLinkPager'),
            'columns'=>array(
                array(
                    'header'=>'Họ và tên',
                    'value'=>'$data["fullname"]',
                    'htmlOptions'=>array('style'=>'width:200px;'),
                ),
                array(
                    'header'=>'Số điện thoại',
                    'value'=>'$data["phone"]',
                    'htmlOptions'=>array('style'=>'width:150px;text-align:center'),
                ),
                array(
                    'header'=>'Email',
                    'value'=>'$data["email"]',
                    'htmlOptions'=>array('style'=>'width:250px;text-align:center'),
                ),
                array(
                    'header'=>'Ghi chú',
                    'value'=>'$data["sale_note"]',
                    'type'=>'raw',
                ),
                array(
                    'header'=>'Lịch học',
                    'value'=>'nl2br($data["planned_schedule"])',
                    'type'=>'raw',
                ),
                array(
                    'header'=>'Học phí',
                    'value'=>'nl2br($data["planned_course_package"])',
                    'type'=>'raw',
                ),
            ),
        ));
    ?>
</div>
<?php endif;?>