<?php FileAsset::register($this); ?>
<div class="page-title"><p style="color:#ffffff; text-align:center; font-size:20px;">File Manager</p></div>
<?php $this->renderPartial('myFileTabs'); ?>

<div class="row ">
	<iframe style="border:none" width="100%" height="600" src="<?php echo $this->baseAssetsUrl; ?>/applications/filemanager/main/dialog.php?type=0"></iframe>
</div>

