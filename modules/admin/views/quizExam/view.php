<?php
/* @var $this QuizExamController */
/* @var $model QuizExam */

$this->breadcrumbs=array(
	'Quiz Exams'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List QuizExam', 'url'=>array('index')),
	array('label'=>'Create QuizExam', 'url'=>array('create')),
	array('label'=>'Update QuizExam', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete QuizExam', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage QuizExam', 'url'=>array('admin')),
);
?>
<h1>Chi tiết đề thi</h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
		   'name'=>'subject_id',
		   'value'=>Subject::model()->displayClassSubject($model->subject_id),
		),
		'name',
		array(
		   'name'=>'type',
		   'value'=>$model->typeOptions($model->type),
		   'type'=>'raw',
		),
		array(
		   'name'=>'level',
		   'value'=>$model->levelOptions($model->level),
		   'type'=>'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>$model->statusOptions($model->status),
		   'type'=>'raw',
		),
		'duration',
		array(
		   'name'=>'created_user_id',
		   'value'=>($model->created_user_id)? User::model()->displayUserById($model->created_user_id):"",
		),
		array(
		   'name'=>'created_date',
		   'value'=>($model->created_date)? date('d/m/Y H:i', strtotime($model->created_date)):"",
		),
		array(
		   'name'=>'modified_user_id',
		   'value'=>($model->modified_user_id)? User::model()->displayUserById($model->modified_user_id):"",
		),		
		array(
		   'name'=>'modified_date',
		   'value'=>($model->modified_date)? date('d/m/Y H:i', strtotime($model->modified_date)):"",
		),
		'deleted_flag',
	),
)); ?>
