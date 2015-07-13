<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$teacher->search(),
	'filter'=>$teacher,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'header'=>'ID',
		   'value'=>'$data->id',
		),
		array(
		   'name'=>'firstname',
		   'value'=>'$data->fullName()',
		),
		array(
		   'name'=>'username',
		   'htmlOptions'=>array('style'=>'width:180px;'),
		),
		'email',
		array(
		   'name' => 'phone',
		   'value'=>'$data->displayContactIcons()', 
		),
		array(
		   'header' => 'Lịch dạy',
		   'value'=>'CHtml::link("Lịch dạy", Yii::app()->createUrl("admin/schedule/registerSchedule?teacher=$data->id"))',
		   //don't write it like this "CHtml::link('Lịch dạy', Yii::app()->createUrl('admin/schedule/registerSchedule?teacher=$data->id'))"
		   //it won't work. Variables must be wraped in "", not ''
		   'type'=>'raw',
		),
	),
)); ?>