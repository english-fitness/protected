<?php
/* @var $this ClassesController */
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Quản lý danh mục lớp - môn</h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
        	<a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/subject/create">
				<i class="icon-plus"></i>Thêm môn mới
			</a>
            <a class="top-bar-button btn btn-default" href="<?php echo Yii::app()->baseUrl; ?>/admin/classes/create">
				<i class="icon-plus"></i>Thêm lớp mới
			</a>			
        </div>
    </div>
</div>
<?php if(count($classSubjects)>0):?>
<?php foreach($classSubjects as $clsId=>$classSubject):?>
	<div class="form-element-container row mT15">
		<div class="col col-lg-1">
			<a href="<?php echo Yii::app()->baseUrl; ?>/admin/classes/update/id/<?php echo $clsId;?>">
				<b><?php echo $classSubject['name']?></b>
			</a>
		</div>
		<div class="col col-lg-9">
			<?php foreach($classSubject['subject'] as $subject):?>
				<span class="fL mL25">
					<span class="icon-status2"></span>&nbsp;
					<a href="<?php echo Yii::app()->baseUrl; ?>/admin/subject/update/id/<?php echo $subject['id'];?>"><?php echo $subject['name'];?></a>
				</span>
			<?php endforeach; ?>
		</div>
	</div>
<?php endforeach;
endif;?>
<div class="clearfix h30">&nbsp;</div>	

