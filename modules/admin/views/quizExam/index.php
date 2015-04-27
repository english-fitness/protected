<?php
/* @var $this QuizExamController */
/* @var $model QuizExam */

$this->breadcrumbs=array(
	'Quiz Exams'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h3 class="page-title mT10">Đề thi trắc nghiệm <?php if(isset($quizItem)):?> đã ghép câu hỏi có nội dung<?php endif;?></h3>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl.'/admin/quizExam/create';?>">
			<i class="icon-plus"></i>Thêm đề thi mới
			</a>
        </div>
    </div>
</div>
<?php if(isset($quizTopic)):?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
    	<label>Thuộc chủ đề:&nbsp;</label><?php echo $quizTopic->displayBreadcrumbs('/admin/quizTopic?parent_id=', '&nbsp;>&nbsp;', 'Chủ đề môn học');?>
    </div>
</div>
<?php endif;?>
<?php if(isset($quizItem)):?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
		<?php echo $quizItem->content;?>
    </div>
</div>
<?php endif;?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search($topicId, $itemId),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->isActivatedWritingExam() && $data->deleted_flag==0)?array("class"=>"writingQuizExam"): (($data->deleted_flag==1)? array("class"=>"deletedRecord"): array())',
	'columns'=>array(
		array(
		   'name'=>'id',
		   'value'=>'$data->id',
		   'filter'=>false,
		   'htmlOptions'=>array('style'=>'text-align:center;width:60px;'),	
		),
		array(
		   'name'=>'subject_id',
		   'value'=>'Subject::model()->displayClassSubject($data->subject_id)',
		   'filter'=>Subject::model()->generateSubjectFilters(),
		   'htmlOptions'=>array('style'=>'width:250px;'),		   
		),
		array(
		   'name'=>'name',
		   'value'=>'CHtml::link($data->name, "/admin/quizExam/preview/id/".$data->id, array())',
		   'type'=>'raw',
		),
		array(
		   'header'=>'Số câu hỏi',
		   'value'=>'CHtml::link($data->countAssignedItem()." câu hỏi", "/admin/quizItem?exam_id=".$data->id, array())',
		   'htmlOptions'=>array('style'=>'width:80px;'),
		   'type'=>'raw',	
		),
		array(
		   'name'=>'type',
		   'value'=>'$data->typeOptions($data->type)',
		   'filter'=>$model->typeOptions(),
		   'htmlOptions'=>array('style'=>'width:120px;'),	
		),
		array(
		   'name'=>'duration',
		   'value'=>'$data->duration." phút"',
		   'htmlOptions'=>array('style'=>'width:80px;text-align:center;'),		   
		),
		array(
		   'name'=>'level',
		   'value'=>'$data->levelOptions($data->level)',
		   'filter'=>$model->levelOptions(),
		   'htmlOptions'=>array('style'=>'width:120px;'),	
		),
		array(
		   'name'=>'status',
		   'value'=>'$data->statusOptions($data->status)',
		   'filter'=>$model->statusOptions(),
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
	),
)); ?>
