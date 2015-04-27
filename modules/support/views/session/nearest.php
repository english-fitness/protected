<script type="text/javascript">
   	setTimeout(function(){window.location.href="/support/session/nearest"},60000);
</script>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$model->searchNearestSession(1),
    //'filter'=>$model,
    'enableHistory'=>true,
    'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
    'columns'=>array(
        array(
            'name'=>'course_id',
            'value'=>'$data->course->title',
            'type'=>'raw',
        ),
        array(
            'header'=>'Môn học',
            'value'=>'Subject::model()->displayClassSubject($data->course->subject_id)',
        ),
        array(
            'name'=>'subject',
            'value'=>'$data->subject',
        ),
        array(
            'header'=>'Kiểu lớp',
            'value'=>'"1-".$data->total_of_student',
            'type'  => 'raw',
        ),
        array(
            'name'=>'teacher_id',
            'value'=>'$data->getTeacher()',
            'type'  => 'raw',
        ),
        array(
            'header' => 'Học sinh',
            'value'=>'implode(", ", $data->getAssignedStudentsArrs())',
            'type'  => 'raw',
        ),
        array(
            'header'=>'Ngày học',
            'value'=>'date("d/m/Y", strtotime($data->plan_start))',
        ),
        array(
            'header'=>'Giờ học',
            'value'=>'$data->displayActualTime()',
        ),
        array(
            'header' => 'Thời gian còn lại',
            'value'=>'$data->displayRemainTime()',
        ),
        array(
            'header' => 'Vào lớp',
            'value'=>'($data->checkDisplayBoard())? ClsSession::displayEnterBoardButton($data->whiteboard):"";',
        	'htmlOptions'=>array('style'=>'width:100px;'),
        ),
    ),
)); ?>
