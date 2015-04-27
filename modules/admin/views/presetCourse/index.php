<?php
/* @var $this PresetCourseController */
/* @var $model PresetCourse */

$this->breadcrumbs=array(
	'Preset Courses'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php 
    		$pageTitle = 'Đơn/khóa học tạo trước';
    		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
    			$pageTitle = 'Đơn/khóa tạo trước đã xóa/hủy';
    		}
    	?>
        <h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/presetCourse/create">
			<i class="icon-plus"></i>Thêm đơn/khóa tạo trước
			</a>
        </div>
    </div>
</div>
<?php if(!(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1)):?>
<div class="form-element-container row">
	<div class="col col-lg-12">
      <a href="<?php echo Yii::app()->baseUrl; ?>/admin/presetCourse?deleted_flag=1"><span class="trash"></span>&nbsp;Đơn/khóa tạo trước đã xóa/hủy</a>
    </div>
</div>
<?php endif;?>
<?php $startDate = Yii::app()->controller->getQuery('PresetCourse[start_date]', '');?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(
		array(
		   'name'=>'title',
		   'value'=>'CHtml::link($data->title, Yii::app()->createUrl("admin/preregisterCourse?preset_id=$data->id"))',
		   'type' => 'raw',
		),
		array(
		   'name'=>'short_description',
		   'value'=>'$data->short_description',
		   'type' => 'raw',
		   'htmlOptions'=>array('style'=>'width:200px;'),
		),
		array(
		   'name'=>'subject_id',
		   'value'=>'Subject::model()->displayClassSubject($data->subject_id)',
		   'filter'=>Subject::model()->generateSubjectFilters(),
		),
		array(
		   'header'=>'Giáo viên',
		   'value'=>'($data->created_user_id==$data->teacher_id)? $data->getTeacher("/admin/teacher/view/id")."<br/><span class=\"error\">(GV tự đăng ký ngày ".date("d/m/Y, H:i", strtotime($data->created_date)).")</span>": $data->getTeacher("/admin/teacher/view/id")',
		   'type' => 'raw',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name'=>'start_date',
		   'value'=>'date("d/m/Y", strtotime($data->start_date))',
		   'filter'=>'<input type="text" value="'.$startDate.'" name="PresetCourse[start_date]">',
		   'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
		),
		array(
		   'name'=>'total_of_session',
		   'value'=>'$data->total_of_session',
		   'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
		),
		array(
		   'name'=>'price_per_student',
		   'value'=>'number_format($data->price_per_student)',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:right;'),
		),		
		array(
		   'header'=>'Số HS Min-Max',
		   'value'=>'$data->min_student." -> ".$data->max_student',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		),
		array(
		   'name'=>'session_per_week',
		   'value'=>'ClsAdminHtml::displaySessionPerWeek($data->session_per_week)',
		   'type'  => 'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>'$data->getStatus()',
		   'filter'=>$model->statusOptions(),
		   'htmlOptions'=>array('style'=>'width:120px;'),	
		),
		array(
		   'header'=>'Đơn xin học',
		   'value'=>'$data->countRegisteredStudents(PreregisterCourse::PAYMENT_STATUS_PAID)."/".$data->countRegisteredStudents()',
		   'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL15' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
		),
	),
)); ?>
