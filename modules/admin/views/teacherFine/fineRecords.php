<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Teacher Penalty Records</h2>
    </div>
</div>
<?php 
	$teacherFullname = Yii::app()->controller->getQuery('TeacherFine[teacher_fullname]', '');
	
	$columns = array(
		array(
			'name'=>'id',
			'value'=>'$data->id',
			'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:60px;'),
		),
		array(
			'name'=>'teacher_id',
			'value'=>'$data->teacher->getViewLink()',
			'filter'=>'<input type="text" value="'.$teacherFullname.'" name="TeacherFine[teacher_fullname]">',
			'htmlOptions'=>array('style'=>'width:200px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:200px;'),
			'type'=>'raw',
		),
		array(
			'name'=>'points',
			'value'=>'$data->points',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'header'=>'Điểm còn lại',
			'name'=>'points_to_be_fined',
			'value'=>'$data->points_to_be_fined',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
			'name'=>'notes',
			'value'=>'$data->notes',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:300px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:300px;'),
		),
		array(
			'header'=>'Ngày',
			'value'=>'date("d-m-Y", strtotime($data->session->plan_start))',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		),
	);
	
	if ($view == 'all'){
		$columns[] = array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}',
			'afterDelete'=>'function(link, success, data){if (success) window.location.reload(); else alert(data);}',
			'buttons'=>array (
		        'update'=> array(
					'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
					'url'=>'Yii::app()->createUrl("admin/teacherFine/update/id/$data->id")',
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL5' ),
					'url'=>'Yii::app()->createUrl("admin/teacherFine/view/id/$data->id")',
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-remove mL15' ),
					'url'=>'Yii::app()->createUrl("admin/teacherFine/delete/id/$data->id")',
		        ),
    		),
			'htmlOptions'=>array('style'=>'width:80px;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		);
	} else {
		$actionColumn = array(
			'header'=>'',
			'value'=>'CHtml::link("Xóa điểm phạt", "javascript:deleteFine($data->id);")',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:100px;'),
		);
		$viewColumn = array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'afterDelete'=>'function(link, success, data){if (success) window.location.reload(); else alert(data);}',
			'buttons'=>array (
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL5' ),
					'url'=>'Yii::app()->createUrl("admin/teacherFine/view/id/$data->id")',
		        ),
    		),
			'htmlOptions'=>array('style'=>'width:80px;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		);
		$columns[] = $actionColumn;
		$columns[] = $viewColumn;
	}
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gridView',
	'dataProvider'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>$columns,
)); ?>
<?php if($view = 'expired'):?>
	<script>
		function deleteFine(id){
			$.ajax({
				url:'/admin/teacherFine/deleteFine',
				type:'post',
				data:{
					id: id,
				},
				success:function(response){
					if (response.success){
						$.fn.yiiGridView.update("gridView");
					} else {
						alert('Đã có lỗi xảy ra. Vui lòng thử lại sau.');
					}
				}
			});
		}
	</script>
<?php endif;?>