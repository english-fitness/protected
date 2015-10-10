<script src="/media/js/bootstrap/bootstrap.min.js"></script>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
<script src="/media/js/bootstrap/bootstrap-dialog.min.js"></script>
<link rel="stylesheet" href="/media/css/bootstrap/bootstrap-dialog.min.css">

<style type="text/css">
	.table th{
		text-align: center;
	}
	.modal-dialog{
        left:0 !important;
        width:960px;
        margin-top:0;
    }
    .iframe-container{
        background:url(/media/images/icon/ripple-loader.gif) center center no-repeat;
    }
</style>

<div class="page-title"><p>Course Progress Report</p></div>
<div class="detail-class">
	<div class="row">
		<a class="btn btn-primary mT10 mB10 mL10" href="/teacher/courseReport/create?cid=<?php echo $course->id;?>">New Progress Report</a>
	</div>
	<div class="session">
		<table class="table text-center table-bordered table-striped data-grid">
			<thead>
				<th class="w100">Student</th>
				<th class="w100">Date</th>
				<th class="w150">Level</th>
				<th class="w150">Curriculum</th>
				<th class="w80"></th>
				<th class="w80"></th>
			</thead>
			<tbody>
			<?php if(count($reports) > 0):?>
				<?php foreach ($reports as $report):?>
					<tr>
						<td><?php echo $report->student->fullname()?></td>
						<td><?php echo date('d-m-Y', strtotime($report->report_date))?></td>
						<td><?php echo $course->level?></td>
						<td><?php echo $course->curriculum?></td>
						<td><?php echo CHtml::link("View", $report->getGoogleDocsViewerUrl(array("embedded"=>"true")), array("class"=>'reportViewLink'))?></td>
						<td><?php echo CHtml::link("Edit", "/teacher/courseReport/update/id/".$report->id)?></td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr><td colspan="6">There is no progress report for this course</td></tr>
			<?php endif;?>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	$(".reportViewLink").click(function(e){
	    e.preventDefault();
	    
	    BootstrapDialog.show({
	        title:"<?php echo Yii::t('lang', 'course_report_view_title')?>",
	        message:'<div class="iframe-container"><iframe id="googleDocsViewer" height="520" width="910" src="' + this.getAttribute('href') + '"></div>',
	    });
	    
	    $('body').unbind('mousewheel DOMMouseScroll');
	    $('body').bind('mousewheel DOMMouseScroll', onWheel);
	    
	    return false;
	});

	function onWheel (e){
	    var iframe = document.getElementById('googleDocsViewer');
	    if (e.target === iframe)
	        e.preventDefault();
	}
</script>