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
    <?php if(Yii::app()->user->isAdmin()):?>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/student/create">
			<i class="icon-plus"></i>Thêm học sinh
			</a>
        </div>
    </div>
    <?php endif;?>
</div>
<?php 
	$statusOptions = Student::statusOptions();
	$createdDateFilter = Yii::app()->controller->getQuery('User[created_date]', '');
	$birthdayFilter = Yii::app()->controller->getQuery('User[birthday]', '');
    
    function normalizeDate($d){
        if ($d == ''){
            return '';
        }
        $date = new DateTime($d);
        return $date->format('d/m/Y');
    }
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
		   'htmlOptions'=>array('style'=>'width:70px; text-align:center;'),
		),
		array(
		   'name'=>'firstname',
		   'value'=>'$data->fullName()',
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		array(
		   'name'=>'username',
		   'htmlOptions'=>array('style'=>'width:150px;'),
		),
		'email',
		array(
		   'name' => 'phone',
		   'value'=>'Common::formatPhoneNumber($data->phone)',
		   'htmlOptions'=>array('style'=>'width:110px;padding-left:5px'),
		),
		array(
		   'name'=>'status',
		   'value'=>'($data->statusOptions($data->status))',
		   'filter'=>$statusOptions,
		   'htmlOptions'=>array('style'=>'width:130px;text-align:center'),
		),
		array(
		   'name'=>'created_date',
		   'value'=>'date("d/m/Y", strtotime($data->created_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="User[created_date]">',
		   'htmlOptions'=>array('style'=>'width:90px;text-align:center;'),
		),
        array(
            'header'=>'Học viên chính thức từ ngày',
            'value'=>'normalizeDate($data->student->official_start_date)',
            'htmlOptions'=>array('style'=>'text-align:center;width:90px'),
        ),
		array(
		   	'value'=>'CHtml::link("Quản lý", "/admin/student/manage/sid/".$data->id)',
		   	'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
		   	'type' => 'raw',
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
            'htmlOptions'=>array('style'=>'width:60px;'),
            'headerHtmlOptions'=>array('style'=>'width:60px;'),
		),
		array(
		   'header'=>'Tư vấn',
		   'value'=>'CHtml::link("Tư vấn", "/admin/student/saleUpdate/id/".$data->id, array("class"=>"icon-plus pL20", "style"=>"width:60px;"))',
		   'filter'=>false, 'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:60px;'),
		),
       
	),
)); ?>