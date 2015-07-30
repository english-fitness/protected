<?php
/* @var $this SessionMonitorController */
/* @var $sessions CSqlDataProvider */
?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/popup.css" type="text/css" rel="stylesheet">
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
        <h2 class="page-title mT10">Các buổi học trong khóa</h2>
    </div>
</div>
<?php
	$courseId = $course->id;
	function createEditButton($sessionId, $status, $usingPlatform, $note){
		if ($status != Session::STATUS_ENDED){
			return "";
		}
		
		if ($usingPlatform != ""){
			$onclick = "editSessionNote(" . $sessionId . "," . $usingPlatform . "," . json_encode($note) . "); return false;";
		} else {
			$onclick = "editSessionNote(" . $sessionId . "); return false;";
		}
		
		return CHtml::link(
			"",
			"#",
			array(
				"class"=>($usingPlatform != "" ? "btn-edit" : "icon-plus") . " mL15",
				"onclick"=>$onclick,
			)
		);
	}
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gridView',
	'dataProvider'=>$sessions,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'htmlOptions'=>array('style'=>'vertical-align:top;'),
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
			'header'=>'ID',
			'value'=>'$data["id"]',
			'htmlOptions'=>array('style'=>'width:80px; text-align:center; vertical-align:top;'),
		),
		array(
		   'header'=>'Chủ đề',
		   'value'=>'$data["subject"]',
		   'htmlOptions'=>array('style'=>'width:150px; vertical-align:top;'),
		),
		array(
		   'header'=>'Giáo viên',
		   'value'=>'Yii::app()->user->getFullNameById($data["teacher_id"])',
		   'htmlOptions'=>array('style'=>'width:150px; vertical-align:top;'),
		   'type'  => 'raw',
		),
		array(
		   'header'=>'Ngày học',
		   'value'=>'date("d/m/Y", strtotime($data["plan_start"]))',
		   'htmlOptions'=>array('style'=>'width:100px; text-align:center;vertical-align:top;'),
		),
		array(
		   'header' => 'Giờ học',
		   'value'=>'date("H:i", strtotime($data["plan_start"])) . " - " . date("H:i", strtotime($data["plan_start"] . " +" . $data["plan_duration"] . "minutes"))',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;vertical-align:top;'),
		),
		array(
		   'header' => 'Trạng thái',
		   'value'=>'ClsAdminHtml::displaySessionStatus($data["id"], $data["status"])',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;vertical-align:top;'),
		),
		array(
		   'header' => 'Buổi học trên hệ thống',
		   'value'=>'$data["using_platform"] ? "Yes" : ($data["using_platform"] === "0" ? "No" : "")',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;vertical-align:top;'),
		),
		array(
		   'header' => 'Ghi chú',
		   'value'=>'nl2br($data["note"])',
		   'htmlOptions'=>array('style'=>'width:250px;vertical-align:top;'),
		   'type'=>'raw',
		),
		array(
			'header'=>'',
			'value'=>'createEditButton($data["id"], $data["status"], $data["using_platform"], $data["note"])',
			'type'=>'raw',
			'htmlOptions'=>array('style'=>'width:40px; text-align:center;vertical-align:top;'),
		),
	),
)); ?>
<script>
	function editSessionNote(sessionId, using_platform, note){
		if (using_platform == undefined){
			var title = "Ghi chú mới"
			using_platform = 1;
		} else {
			var title = "Sửa ghi chú"
		}
		if (note == undefined){
			note = "";
		}
		popup({
			width:"600px",
			title:title,
			content:function(formCreator){
				var elementCreator = formCreator.popupForm();
				
				var form = formCreator.getForm({id:"sessionNoteForm",method:"post","class":"myFormPopup"});
				
				var using_platform_options = elementCreator.option("1", "Yes", using_platform == 1 ? "selected" : "");
				using_platform_options += elementCreator.option("0", "No", using_platform == 0 ? "selected" : "");
				form += formCreator.newRow(
					"Buổi học trên hệ thống",
					elementCreator.select({id:"using_platform",name:"SessionNote[using_platform]"},using_platform_options)
				);
				form += formCreator.newHtmlRow(
					'<div class="label" style="vertical-align:top">Ghi chú</div>' +
					'<div class="value">' + elementCreator.textarea({id:"note",name:"SessionNote[note]"}, note) + "</div>"
				);
				form += formCreator.newRow("&nbsp;","<button id='saveNote'>Lưu lại</button>" +
													 "<button id='cancel' onclick='removePopupByID"+'("popupAll")'+";return false;'>Đóng</button>");
				form += '</form>';
                return form;
			}
		});
		
		$('#saveNote').on('click', function(e){
			updateSessionNote(sessionId);
			removePopupByID('popupAll');
			e.preventDefault();
			return false;
		});
	}
	
	function createSessionNote(){
		
	}
	
	function updateSessionNote(sessionId){
		$.ajax({
			url: "/admin/sessionMonitor/update/id/" + sessionId,
			type:'post',
			data:$('#sessionNoteForm').serialize(),
			success: function(response){
				if (response.success){
					$.fn.yiiGridView.update("gridView")
				} else {
					$("<div>Đã có lỗi xảy ra, vui lòng thử lại sau</div>").dialog({
						modal:true,
						resizable:false,
						buttons:{
							"Đóng": function(){
								$(this).dialog('close');
							}
						}
					});
				}
			},
		});
	}
</script>