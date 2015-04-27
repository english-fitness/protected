<?php
/* @var $this QuizTopicController */
/* @var $model QuizTopic */

$this->breadcrumbs=array(
	'Quiz Topics'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List QuizTopic', 'url'=>array('index')),
	array('label'=>'Create QuizTopic', 'url'=>array('create')),
	array('label'=>'Update QuizTopic', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete QuizTopic', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage QuizTopic', 'url'=>array('admin')),
);
?>

<h1>Chi tiết chủ đề</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
		   'name'=>'subject_id',
		   'value'=>Subject::model()->displayClassSubject($model->subject_id),
		),
		array(
		   'name'=>'Đường dẫn',
		   'value'=>$model->displayBreadcrumbs('/admin/quizTopic?parent_id=', '&nbsp;>&nbsp;', 'Chủ đề môn học'),
		   'type'=>'raw',
		),		
		'name',
		array(
		   'name'=>'status',
		   'value'=>$model->statusOptions($model->status),
		   'type'=>'raw',
		),
		array(
		   'name'=>'content',
		   'value'=>$model->content,
		   'type'=>'raw',
		),
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
