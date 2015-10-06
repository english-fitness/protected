<div class="page-title"><p>New Progress Report</p></div>
<?php
	$params = array(
		'course'=>$course,
		'report'=>$report,
	);
	if (isset($error)){
		$params['error'] = $error;
	}

	$this->renderPartial('_form', $params);
?>