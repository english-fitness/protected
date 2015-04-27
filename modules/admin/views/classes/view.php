<?php
/* @var $this ClassesController */
/* @var $model Classes */

$this->breadcrumbs=array(
	'Classes'=>array('index'),
	$model->name,
);
?>

<h1>Chi tiết lớp</h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
<div class="clearfix h20">&nbsp;</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<a href="<?php echo Yii::app()->baseUrl.'/admin/classes'; ?>"><div class="btn-back mT2"></div>&nbsp;Quay lại danh mục lớp học</a>
	</div>
</div>
<div class="clearfix h20">&nbsp;</div>
