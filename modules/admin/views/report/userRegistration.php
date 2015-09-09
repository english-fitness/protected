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
                    'header'=>'Người liên hệ/Nguồn',
                    'value'=>'$data["source"]',
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
                    'header'=>'Trạng thái chăm sóc',
                    'value'=>'$data["care_status"]',
                    'htmlOptions'=>array('style'=>'width:200px;'),
                ),
                array(
                    'header'=>'Ghi chú',
                    'value'=>'$data["sale_note"]',
                    'type'=>'raw',
                ),
            ),
        ));
    ?>
</div>
<?php endif;?>