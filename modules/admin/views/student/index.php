<?php
/* @var $this TeacherController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Quản lý học sinh</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/student/create">
			<i class="icon-plus"></i>Thêm học sinh
			</a>
        </div>
    </div>
</div>
<?php 
	$statusOptions = $model->statusOptions(); unset($statusOptions['-1']);
	$careStatusOptions = array(""=>"") + Student::model()->careStatusOptions();
	$createdDateFilter = Yii::app()->controller->getQuery('User[created_date]', '');
	$birthdayFilter = Yii::app()->controller->getQuery('User[birthday]', '');
	$classId = Yii::app()->controller->getQuery('Student[class_id]', '');
	$saleStatusFilter = Yii::app()->controller->getQuery('Student[sale_status]', '');
	$selectedSaleUserId = Yii::app()->controller->getQuery('Student[sale_user_id]', '');
	$selectedCareStatus = Yii::app()->controller->getQuery('Student[care_status]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'name'=>'id',
		   'value'=>'$data->id',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		),
		array(
		   'name'=>'firstname',
		   'value'=>'$data->fullName()',
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		'email',
		array(
		   'header' => 'Lớp học',
		   'value'=>'Student::model()->displayClass($data->id)',
		   'filter'=>Student::model()->displayFilterClasses($classId),
		   'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
		),
		array(
		   'name' => 'birthday',
		   'value'=>'($data->birthday)? date("d/m/Y", strtotime($data->birthday)): ""',
		   'filter'=>'<input type="text" value="'.$birthdayFilter.'" name="User[birthday]">',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'name' => 'phone',
		   'value'=>'$data->displayContactIcons()',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'name'=>'status',
		   'value'=>'($data->statusOptions($data->status))',
		   'filter'=>$statusOptions,
		   'htmlOptions'=>array('style'=>'width:135px;'),
		),
		array(
		   'name'=>'created_date',
		   'value'=>'date("d/m/Y", strtotime($data->created_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="User[created_date]">',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'header'=>'Đơn',
		   'value'=>'Student::model()->displayPreCourseLink($data->id, "")',
		   'htmlOptions'=>array('style'=>'width:20px; text-align:center;'),
		   'type' => 'raw',
		),
		array(
		   'header'=>'Khóa',
		   'value'=>'Student::model()->displayCourseLink($data->id, "")',
		   'htmlOptions'=>array('style'=>'width:20px; text-align:center;'),
		   'type' => 'raw',
		),
		array(
		   'header'=>'Trạng thái Sale',
		   'value'=>'Student::model()->displaySaleStatus($data->id)',
		   'filter'=>'<input type="text" value="'.$saleStatusFilter.'" name="Student[sale_status]">',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
		),
		array(
		   'header'=>'Trạng thái chăm sóc',
		   'value'=>'CHtml::link(Student::model()->displayCareStatus($data->id), Yii::app()->createUrl("admin/userSalesHistory/index?student_id=$data->id"));',
		   'filter'=>CHtml::dropDownList('Student[care_status]', $selectedCareStatus, $careStatusOptions, array()),
		   'htmlOptions'=>array('style'=>'width:120px;'),'type' => 'raw',
		),
		array(
		   'header'=>'Người tư vấn',
		   'value'=>'Student::model()->displaySaleUser($data->id)',
		   'filter'=>CHtml::dropDownList('Student[sale_user_id]', $selectedSaleUserId, Student::model()->getSalesUserOptions(false), array()),
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
			'class'=>'CButtonColumn',
			'buttons'=>array (
		        'update'=> array('label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view' ),
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'dpn' ),
		        ),
    		),
		),
		array(
		   'header'=>'Tư vấn',
		   'value'=>'CHtml::link("Tư vấn", "/admin/student/saleUpdate/id/".$data->id, array("class"=>"icon-plus pL20", "style"=>"width:75px;"))',
		   'filter'=>false, 'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:60px;'),
		),
	),
)); ?>