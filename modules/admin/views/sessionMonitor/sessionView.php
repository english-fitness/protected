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
	<div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Môn học:</b>&nbsp;<?php echo Subject::model()->displayClassSubject($course->subject_id);?></span>
    		</div>
    		<div class="col col-lg-8 pL0i">
    			<span class="fL"><b>Chủ đề khóa học:</b>&nbsp;<?php echo $course->title;?></span>
    			<span class="fL"><a class="btn-edit mL15" href="/admin/course/update/id/<?php echo $course->id?>" title=""></a></span>
    			<span class="fL"><a class="btn-view mL15" href="/admin/course/view/id/<?php echo $course->id?>" title=""></a></span>
    		</div>
    	</p>
    </div>    
    <div class="col col-lg-12">
    	<div class="col col-lg-3 pL0i">
    		<b>Giáo viên:</b>&nbsp;<?php $teacher = $course->getTeacher("/admin/teacher/view/id");?>
    		<?php echo ($teacher)? $teacher: "Chưa gán giáo viên";?>
    	</div>
    	<div class="col col-lg-8 pL0i"><b>Học sinh:</b>&nbsp;
	        <?php $courseStudentValues = array_values($course->getAssignedStudentsArrs("/admin/student/view/id"));?>
			<?php $students = implode(', ', $courseStudentValues);?>
			<?php echo ($students!="")? $students: "Chưa gán học sinh";?>
		</div>
	</div>
	<div class="col col-lg-12">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Kiểu khóa học:</b>&nbsp;
    			<?php $typeOptions = $course->typeOptions(); echo $typeOptions[$course->type];?></span>
    		</div>
    		<div class="col col-lg-8 pL0i">
    			<span class="fL"><b>Trạng thái:</b>&nbsp;<?php echo $course->getStatus();?></span>
    		</div>
    	</p>
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
	
	function getStatusDisplay($session){
		//session is an array since we are using CSqlDataProvider
		$endTime = date('Y-m-d H:i', strtotime('+' . $session['plan_duration'] . ' minutes', strtotime($session['plan_start'])));
		$now = date('Y-m-d H:i');
		$status = $session['status'];
		if($status != Session::STATUS_ENDED && $status != Session::STATUS_CANCELED && $endTime < $now){
			return '<a href="#" onclick="endSession(' . $session['id'] . '); return false;" class="changeStatusLink">' .
						Session::statusOptions()[$status] . '<br>(Hết giờ)' .
					'</a>';
		} else {
			return ClsAdminHtml::displaySessionStatus($session['id'], $status);
		}
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
		   'value'=>'getStatusDisplay($data)',
		   'htmlOptions'=>array('style'=>'width:80px; text-align:center;vertical-align:top;'),
		   'type'=>'raw',
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
	
	function endSession(sessionId){
        popup({
            title:"Kết thúc buổi học",
            width:"500px",
            content:function(formCreator){
                var elementCreator = formCreator.popupForm();
                var form = formCreator.getForm({id:"sessionEndForm",method:"post","class":"myFormPopup"});
                
                var statusOptions = elementCreator.option(<?php echo Session::STATUS_ENDED?>, "Đã kết thúc");
                statusOptions += elementCreator.option(<?php echo Session::STATUS_CANCELED?>, "Đã hủy");
                
                form += formCreator.newRow(
                    'Trạng thái',
                    elementCreator.select({id:"status", name:"status"}, statusOptions)
                );
                
                var paidOptions = elementCreator.option(1, "Có");
                paidOptions += elementCreator.option(0, "Không");
                
                form += formCreator.newRow(
                    'Tính tiền',
                    elementCreator.select({id:"teacher_paid", name:"teacher_paid"}, paidOptions)
                );
                
                form += formCreator.newRow("&nbsp;","<button id='saveStatus'>Lưu lại</button>" +
													 "<button id='cancel' onclick='removePopupByID"+'("popupAll")'+";return false;'>Đóng</button>");
                                                     
				form += '</form>';
                
                return form;
            }
        });
        
        $('#saveStatus').click(function(e){
            e.preventDefault();
            var form = document.getElementById('sessionEndForm');
            $.ajax({
				url:'/admin/session/ajaxChangeStatus',
				type:'post',
				data:{
					sessionId:sessionId,
					status:document.getElementById('status').value,
				},
				success:function(){
					$.fn.yiiGridView.update("gridView");
                    removePopupByID('popupAll');
				}
			});
        });
        
		// if (confirm('Buổi học này đã kết thúc. Chuyển trạng thái thành kết thúc?')){
			// $.ajax({
				// url:'/admin/session/ajaxChangeStatus',
				// type:'post',
				// data:{
					// sessionId:sessionId,
					// status:<?php echo Session::STATUS_ENDED?>,
				// },
				// success:function(){
					// $.fn.yiiGridView.update("gridView");
				// }
			// });
		// }
	}
</script>