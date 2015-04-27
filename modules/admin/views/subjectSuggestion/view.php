<?php
/* @var $this SubjectSuggestionController */
/* @var $model SubjectSuggestion */

$this->breadcrumbs=array(
	'Subject Suggestions'=>array('index'),
	$model->title,
);
?>

<h1>Chi tiết gợi ý chủ đề</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'subject_id',
		'title',
		'description',
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
	),
)); ?>
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<a href="<?php echo Yii::app()->baseUrl.'/admin/subjectSuggestion'; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh sách chủ đề gợi ý</a>
	</div>
</div>
