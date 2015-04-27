<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */

$this->breadcrumbs=array(
	'Preregister Users'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);
?>
<?php $this->renderPartial('_form', array('model'=>$model)); ?>