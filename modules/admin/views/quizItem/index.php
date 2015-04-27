<?php
/* @var $this QuizItemController */
/* @var $model QuizItem */

$this->breadcrumbs=array(
	'Quiz Items'=>array('index'),
	'Manage',
);
?>
<?php $examId = Yii::app()->request->getQuery('exam_id', null);?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h3 class="page-title mT10">Câu hỏi trắc nghiệm <?php if(isset($quizExam)):?> trong đề thi<?php endif;?></h3>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl.'/admin/quizItem/create';?>">
			<i class="icon-plus"></i>Thêm câu hỏi mới
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
<?php if(isset($quizExam)):?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
    	<span class="fL"><b><?php echo Subject::model()->displayClassSubject($quizExam->subject_id)?>:&nbsp;</b></span></label>
        <span class="fL">
			<a href="/admin/quizExam/preview/id/<?php echo $quizExam->id;?>"><?php echo $quizExam->name;?></a>
			<span class="fs12"><i>(<b>Thời lượng:</b> <?php echo $quizExam->duration; ?> phút, <b>Kiểu đề:</b> <?php echo $quizExam->typeOptions($quizExam->type);?>, <b>Độ khó:</b> <?php echo $quizExam->levelOptions($quizExam->level);?>)</i></span>
		</span>
    </div>
</div>
<?php endif;?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search($topicId, $examId),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
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
		   'name'=>'content',
		   'value'=>'$data->content',
		   'type'=>'raw',
		),
		array(
		   'header'=>'Số câu hỏi con',
		   'value'=>'$data->countSubItems()',
		   'type'=>'raw', 'filter'=>false,
		   'htmlOptions'=>array('style'=>'text-align:center;width:80px;'),
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
		   'header'=>'Đề thi đã ghép',
		   'value'=>'CHtml::link($data->countAssignedExam()." đề thi đã ghép", "/admin/quizExam?item_id=".$data->id, array())',
		   'htmlOptions'=>array('style'=>'width:180px;'),
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
