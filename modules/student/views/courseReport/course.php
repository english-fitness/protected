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
<script src="/media/js/bootstrap/bootstrap-dialog.min.js"></script>
<link rel="stylesheet" href="/media/css/bootstrap/bootstrap-dialog.min.css">
<div class="page-title"><p><?php echo  Yii::t('lang','course_report') ;?></p></div>
<div class="detail-class">
	<div class="session">
		<table class="table text-center table-bordered table-striped data-grid">
			<thead>
				<th class="w100"><?php echo Yii::t('lang', 'course_report_teacher')?></th>
				<th class="w100"><?php echo Yii::t('lang', 'course_report_date')?></th>
				<th class="w150"><?php echo Yii::t('lang', 'level')?></th>
				<th class="w150"><?php echo Yii::t('lang', 'curriculum')?></th>
				<th class="w80"></th>
				<th class="w80"></th>
			</thead>
			<tbody>
			<?php if(count($reports) > 0):?>
				<?php foreach ($reports as $report):?>
					<tr>
						<td><?php echo $report->reportingTeacher->fullname()?></td>
						<td><?php echo date('d-m-Y', strtotime($report->report_date))?></td>
						<td><?php echo $course->level?></td>
						<td><?php echo $course->curriculum?></td>
						<td><?php echo CHtml::link(Yii::t('lang', 'view'), $report->getGoogleDocsViewerUrl(array("embedded"=>"true")), array("class"=>'reportViewLink'))?></td>
						<td><?php echo CHtml::link(Yii::t('lang', 'course_report_comment'), '#'.$report->id, array("class"=>'commentLink', 'data-report'=>$report->id))?></td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr><td colspan="7"><?php echo Yii::t('lang', 'course_report_no_report')?></td></tr>
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

	$(".commentLink").click(function(e){
		e.preventDefault();

		var report = $(this).data('report');

		BootstrapDialog.show({
			title:'<?php echo Yii::t("lang", "course_report_comment_view")?>',
			message: function(){
				return '<div class="comment_dialog">\
					<form>\
						<fieldset>\
							<p><?php echo Yii::t("lang", "course_report_comment_label")?></p>\
							<textarea disabled style="resize:none;width:100%" name="comment" id="comment" rows="15" class="text ui-corner-all"></textarea>\
						</fieldset>\
					</form>\
				</div>';
			},
			buttons:[
				{
					id:'saveComment',
					label:'Save',
					action:function(dialog){
						submitComment(dialog, report, $("#comment").val());
					}
				}
			]
		});

		setComment(report);
	});

	function submitComment(dialog, id, comment){
		$.ajax({
			url:'/student/courseReport/comment',
			type:'post',
			data:{
				id: id,
				comment: comment
			},
			success:function(response){
				dialog.close();
			},
			error:function(){
				dialog.close();
			}
		});
	}

	function setComment(id){
		var comment = "";
		$.ajax({
			url:'/student/courseReport/getComment/id/'+id,
			type:'post',
			success:function(response){
				comment = response.comment;
				$("#comment").val(comment).prop("disabled", false);
			},
		});
	}
</script>