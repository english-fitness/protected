<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */

$this->breadcrumbs=array(
	'Preregister Users'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php 
    		$pageTitle = 'Danh sách đăng ký tư vấn';
    		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
    			$pageTitle = 'Đăng ký tư vấn đã bị xóa/hủy';
    		}
    	?>
        <h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="/admin/preregisterUser/create">
			<i class="icon-plus"></i>Thêm đăng ký tư vấn
			</a>
        </div>
    </div>
</div>
<?php if($model->deleted_flag==0):?>
<div class="form-element-container row">
	<div class="col col-lg-12">
      <a href="/admin/preregisterUser?deleted_flag=1"><span class="trash"></span>&nbsp;Danh sách đăng ký tư vấn đã bị xóa/hủy</a>
    </div>
</div>
<?php endif;?>
<?php 
	$createdDateFilter = Yii::app()->controller->getQuery('PreregisterUser[created_date]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
	'columns'=>array(
		'fullname',
		'phone',		
		'email',
		'promotion_code',
		array(
		   'name'=>'created_date',
		   'value'=>'date("d/m/Y, H:i", strtotime($data->created_date))',
		   'filter'=>'<input type="text" value="'.$createdDateFilter.'" name="PreregisterUser[created_date]">',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name'=>'sale_status',
		   'value'=>'($data->sale_status)',
		   'htmlOptions'=>array('style'=>'width:135px; text-align:center;'),
		),
		array(
		   'name'=>'care_status',
		   'value'=>'$data->careStatusOptions($data->care_status)',
		   'filter'=>$model->careStatusOptions(),
		   'htmlOptions'=>array('style'=>'width:135px;'),
		),
		array(
		   'name'=>'sale_user_id',
		   'value'=>'($data->sale_user_id)? User::model()->displayUserById($data->sale_user_id):""',
		   'filter'=>Student::model()->getSalesUserOptions(false, "", false),
		   'htmlOptions'=>array('style'=>'width:150px;'),
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
		   'value'=>'CHtml::link("Tư vấn", "/admin/preregisterUser/saleUpdate/id/".$data->id, array("class"=>"icon-plus pL20", "style"=>"width:75px;"))',
		   'filter'=>false, 'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:60px;'),
		),
	),
)); ?>
