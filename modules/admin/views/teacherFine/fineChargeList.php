<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Danh sách giáo viên</h2>
    </div>
</div>
<div>
	<?php if($showAll):?>
		<a href="/admin/teacherFine/fineChargeList">Xem danh sách giáo viên đủ điểm phạt</a>
	<?php else:?>
		<a href="/admin/teacherFine/fineChargeList?view=all">Xem toàn bộ danh sách giáo viên</a>
	<?php endif;?>
</div>
<?php 
	$teacherFullname = Yii::app()->controller->getQuery('teacher_fullname', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gridView',
	'dataProvider'=>$teachers,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
			'header'=>'Giáo viên',
			'value'=>'User::getLink($data["teacher_id"])',
			'filter'=>'<input type="text" value="'.$teacherFullname.'" name="TeacherFineCharge[teacher_fullname]">',
			'htmlOptions'=>array('style'=>'width:200px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:200px;'),
			'type'=>'raw',
		),
		array(
			'header'=>'Tổng số điểm',
			'name'=>'total_points',
			'value'=>'$data["total_points"]',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'header'=>'Số điểm bị trừ',
			'value'=>'TeacherFineCharge::getNumberOfPointsToCharge($data["total_points"])',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'header'=>'Số buổi học bị trừ',
			'value'=>'TeacherFineCharge::getNumberOfSessionDeducted($data["total_points"])',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'header'=>'',
			'value'=>'CHtml::link("Trừ điểm", "javascript:chargeFine(" . $data["teacher_id"] . ");")',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		)
	),
)); ?>
<script>
	function chargeFine(teacherId){
		if (confirm('Bạn có muốn trừ điểm của giáo viên này?')){
			$.ajax({
				url:'/admin/teacherFine/chargeFine',
				type:'post',
				data:{
					teacherId:teacherId
				},
				success:function(){
					$.fn.yiiGridView.update("gridView");
				}
			});
		}
	}
</script>