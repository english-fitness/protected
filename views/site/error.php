<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
	<?php
	/* @var $this SiteController */
	/* @var $error array */
	
	$this->pageTitle=Yii::app()->name . ' - Error';
	$this->breadcrumbs=array(
		'Error',
	);
	?>
	
	<h2>Error <?php echo $code; ?></h2>
	
	<div class="error">
	<?php echo CHtml::encode($message); ?>
	</div>
</body>
</html>