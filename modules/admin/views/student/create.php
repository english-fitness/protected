<?php
/* @var $this StudentController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Create',
);
?>

<?php 
$params = array(
    'model'=>$model,
    'student'=>$student,
);
if(isset($preregisterUser)){
    $params['preregisterUser'] = $preregisterUser;
}
$this->renderPartial('_form', $params);
?>