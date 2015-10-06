<div class="overview-widget">
	<?php 
	$this->widget('zii.widgets.grid.CGridView', array(
		'dataProvider'=>$model,
		'enableHistory'=>true,
		'pager' => array('class'=>'CustomLinkPager'),
		'columns'=>array(
			array(
				'header'=>'Khóa học',
				'value'=>'$data->course_id',
				'htmlOptions'=>array('style'=>'text-align:center; width:80px'),
			),
		    array(
		       'name' => 'payment_date',
		       'value'=>'$data->payment_date != null ? date("d-m-Y", strtotime($data->payment_date)) : ""',
		       'htmlOptions'=>array('style'=>'text-align:center; width:120px'),
		    ),
		    array(
	            'header'=>'Học phí',
	            'value'=>'number_format($data->tuition)',
	            'htmlOptions'=>array('style'=>'text-align:center; width:100px'),
	        ),
	        array(
	            'header'=>'Số buổi',
	            'value'=>'$data->sessions',
	            'htmlOptions'=>array('style'=>'text-align:center; width:70px;'),
	        ),
	        array(
	        	'name'=>'note',
	        	'value'=>'$data->note',
        	)
		),
	)); ?>
</div>