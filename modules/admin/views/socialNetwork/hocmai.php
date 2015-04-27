<?php
/* @var $this SocialNetworkController */
/* @var $model User */
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Tài khoản Hocmai đã kết nối</h2>
    </div>
    <div class="col col-lg-6">
    	<span class="page-title mT10 fR mL20"><a href="/admin/socialNetwork/facebook">Tài khoản Facebook đã kết nối</a></span>
    	<span class="page-title mT10 mL20 fR"><a href="/admin/socialNetwork/google">Tài khoản Gmail đã kết nối</a></span>
    </div>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(		
		'hocmai_id',
		'hocmai_username',
		'hocmai_email',
		'hocmai_fullname',
		'hocmai_mobile',
		array(
		   'header'=>'Ngày kết nối',
		   'value'=>'date("d/m/Y", strtotime($data->created_date))',
		),
		array(
		   'header'=>'Đã kết nối với',
		   'value'=>'$data->displayConnectedUser()',
		),
	),
)); ?>