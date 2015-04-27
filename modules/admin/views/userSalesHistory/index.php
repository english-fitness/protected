<?php
/* @var $this UserSalesController */
/* @var $model UserSalesHistory */

$this->breadcrumbs=array(
	'User Sales Histories'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Lịch sử chăm sóc, tư vấn</h2>
    </div>
    <?php if(isset($student) && isset($student->id)):?>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="/admin/student/saleUpdate/id/<?php echo $student->id;?>">
			<i class="icon-plus"></i>Thêm lịch sử chăm sóc
			</a>
        </div>
    </div>
    <div class="col col-lg-12">
		<span class="fL"><b>Họ và tên:&nbsp;</b><?php echo $student->fullName();?>,</span>
		<span class="fL mL25"><b>Email:&nbsp;</b><?php echo $student->email;?>,</span>
		<span class="fL mL25"><b>Điện thoại:&nbsp;</b><?php echo $student->phone;?>,</span>
		<span class="fL mL25"><b>Trạng thái:&nbsp;</b><?php echo $student->statusOptions($student->status);?></span>
	</div>
	<?php endif;?>
</div>
<?php 
	$saleDateFilter = Yii::app()->controller->getQuery('UserSalesHistory[sale_date]', '');
	$nextSaleDateFilter = Yii::app()->controller->getQuery('UserSalesHistory[next_sale_date]', '');
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'header'=>'Học sinh',
		   'value'=>'$data->getStudent("/admin/student/view/id")',
		   'type' => 'raw',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),		
		array(
            'name'=>'sale_note',
            'value'=>'$data->sale_note',
            'type'=>'raw',
			'htmlOptions'=>array('style'=>'width:120px;'),
        ),
		array(
            'name'=>'sale_question',
            'value'=>'$data->sale_question',
            'type'=>'raw',
			'htmlOptions'=>array('style'=>'width:300px;'),
        ),
        array(
            'name'=>'user_answer',
            'value'=>'$data->user_answer',
            'type'=>'raw'
        ),
        array(
            'name'=>'sale_status',
            'value'=>'$data->sale_status',
            'type'=>'raw',
        	'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
        ),
        array(
		   'name'=>'sale_date',
		   'value'=>'date("d/m/Y, H:i", strtotime($data->sale_date))',
		   'filter'=>'<input type="text" value="'.$saleDateFilter.'" name="UserSalesHistory[sale_date]">',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name'=>'next_sale_date',
		   'value'=>'($data->next_sale_date)?date("d/m/Y", strtotime($data->next_sale_date)):""',
		   'filter'=>'<input type="text" value="'.$nextSaleDateFilter.'" name="UserSalesHistory[next_sale_date]">',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		),
		array(
		   'name'=>'created_user_id',
		   'value'=>'($data->created_user_id)? User::model()->displayUserById($data->created_user_id):""',
		   'filter'=>Student::model()->getSalesUserOptions(false),
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
		   'value'=>'CHtml::link("Tư vấn", "/admin/student/saleUpdate/id/".$data->user_id, array("class"=>"icon-plus pL20", "style"=>"width:75px;"))',
		   'filter'=>false, 'type'  => 'raw',
		   'htmlOptions'=>array('style'=>'width:60px;'),
		),
	),
)); ?>
