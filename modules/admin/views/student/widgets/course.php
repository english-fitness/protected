<div style="margin-bottom:-20px;">
	<?php if (isset($_GET["type"]) && $_GET["type"] != Course::TYPE_COURSE_NORMAL):?>
		<a href="/admin/student/courseWidget/sid/<?php echo $studentId?>">Khóa học thường</a>
	<?php else:?>
		<a href="/admin/student/courseWidget/sid/<?php echo $studentId?>?type=3">Khóa học thử</a>
	<?php endif;?>
</div>
<div class="overview-widget">
	<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
		'id'=>'widgetGridview',
		'dataProvider'=>$course,
		'enableHistory'=>true,
		'pager' => array('class'=>'CustomLinkPager'),
		'columns'=>array(
			array(
				'header'=>'ID',
				'value'=>'$data->id',
				'htmlOptions'=>array('style'=>'text-align:center'),
			),
			array(
		       'header' => 'Ngày bắt đầu',
		       'value'=>'$data->getFirstDateInList("ASC")',
		       'htmlOptions'=>array('style'=>'width:130px;text-align:center'),
		    ),
		    array(
		       'name' => 'level',
		       'value'=>'$data->level',
		       'htmlOptions'=>array('style'=>'text-align:center'),
		    ),
		    array(
		       'name' => 'curriculum',
		       'value'=>'$data->curriculum',
		       'htmlOptions'=>array('style'=>'text-align:center'),
		    ),
		    array(
		       'header'=>'Giáo viên',
		       'value'=>'CHtml::link($data->teacher->fullname(), "/admin/teacher/view/id/".$data->teacher->id, array("target"=>"_parent"))',
		       'type' => 'raw',
		       'htmlOptions'=>array('style'=>'text-align:center'),
		    ),
		    array(
		       'header' => 'Số buổi',
		       'value'=>'CHtml::link($data->countSessions(null, true)." buổi", Yii::app()->createUrl("admin/session?course_id=$data->id"), array("target"=>"_parent"))',
		       'type'=>'raw',
		       'htmlOptions'=>array('style'=>'text-align:center'),
		    ),
		    array(
		       'header' => 'Ngày kết thúc',
		       'value'=>'$data->getFirstDateInList("DESC")',
		       'htmlOptions'=>array('style'=>'width:130px;text-align:center'),
		    ),
		    array(
		       'header'=>'Đánh giá',
		       'value'=>'"<a target=\"_parent\" href=\"/admin/courseReport/course/id/".$data->id."\">Đánh giá</a>"',
		       'type'=>'raw',
		       'htmlOptions'=>array('style'=>'text-align:center;width:100px'),
		    ),
		),
	)); ?>
</div>