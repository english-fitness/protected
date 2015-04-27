<?php
/* @var $this TeacherController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Quản lý cấu hình đầu trang</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/headerScript/add">
			<i class="icon-plus"></i>Thêm cấu hình đầu trang
			</a>
        </div>
    </div>
</div>

<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $model->getAttributeLabel('condition')?>
	</div>
	<div class="col col-lg-9">
		<?php echo Yii::app()->request->hostInfo.$model->condition; ?>
	</div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<?php echo $model->getAttributeLabel('value')?>
	</div>
	<div class="col col-lg-9">
		<?php echo CHtml::encode($model->value); ?>
	</div>
</div>
