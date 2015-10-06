<div class="page-title"><p>Edit Progress Report</p></div>
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