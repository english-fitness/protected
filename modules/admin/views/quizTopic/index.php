<?php
/* @var $this QuizTopicController */
/* @var $model QuizTopic */

$this->breadcrumbs=array(
	'Quiz Topics'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h3 class="page-title mT10">
        	<a href="/admin/quizTopic?parent_id=0">Chủ đề môn học</a>
        </h3>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
        	<?php 
        		$parentId = Yii::app()->request->getQuery('parent_id', 0);
        		$createLink = Yii::app()->baseUrl.'/admin/quizTopic/create?parent_id='.$parentId;
        	?>
            <a class="top-bar-button btn btn-primary" href="<?php echo $createLink;?>">
			<i class="icon-plus"></i>Thêm chủ đề con
			</a>
        </div>
    </div>
</div>
<?php if(isset($currentTopic)):?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
        <span><?php echo $currentTopic->displayBreadcrumbs('/admin/quizTopic?parent_id=', '&nbsp;>&nbsp;', 'Chủ đề môn học');?></span>
    </div>
</div>
<?php endif;?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'name'=>'subject_id',
		   'value'=>'Subject::model()->displayClassSubject($data->subject_id)',
		   'filter'=>Subject::model()->generateSubjectFilters(),
		   'htmlOptions'=>array('style'=>'width:250px;'),		   
		),
		array(
		   'name'=>'name',
		   'value'=>'CHtml::link($data->name, Yii::app()->createUrl("/admin/quizTopic?parent_id=".$data->id))',
		   'type'=>'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>'$data->statusOptions($data->status)',
		   'filter'=>$model->statusOptions(),
		   'htmlOptions'=>array('style'=>'width:150px;'),	
		),
		array(
		   'header'=>'Chủ đề con',
		   'value'=>'$data->countChildren()." chủ đề con"',
		   'type'=>'raw',
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'header'=>'Số câu hỏi',
		   'value'=>'CHtml::link($data->countQuizItem()." câu hỏi", "/admin/quizItem?topic_id=".$data->id, array())',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		   'type'=>'raw',	
		),
		array(
		   'header'=>'Số đề thi',
		   'value'=>'CHtml::link($data->countQuizExam()." đề thi", "/admin/quizExam?topic_id=".$data->id, array())',
		   'htmlOptions'=>array('style'=>'width:100px;'),
		   'type'=>'raw',	
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
	),
)); ?>
