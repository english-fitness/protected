<?php
/* @var $this NoticeController */
/* @var $model CartLog */

$this->breadcrumbs=array(
    'Notice'=>array('index'),
    $model->id,
);
?>

<h1 class="page-header">View Notice #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$model,
    'attributes'=>array(
        'id',
        'content',
        'created_time',
    ),
)); ?>
