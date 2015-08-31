<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/popup.js"></script>
<link href="<?php echo Yii::app()->theme->baseUrl; ?>/css/popup.css" type="text/css" rel="stylesheet">

<div class="col col-lg-6" style="margin-bottom:20px; margin-left:-15px">
    <h2 class="page-title mT10">Thống kê buổi học</h2>
</div>
<?php
function createEditButton($sessionId, $status, $usingPlatform, $paidSession, $note){
		if ($status != Session::STATUS_ENDED){
			return "";
		}
		
		if ($usingPlatform != ""){
			$onclick = "editSessionNote(" . $sessionId . "," . $usingPlatform . "," . $paidSession . "," . json_encode($note) . "); return false;";
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
<?php
    if (!isset($requestParams)){
        $this->renderPartial('dateSelector');
    } else {
        $this->renderPartial('dateSelector', array(
            'requestParams'=>$requestParams,
        ));
    }
    
    if (isset($sessions)){
        $this->widget('zii.widgets.grid.CGridView', array(
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
                   'header' => 'Học sinh',
                   'value'=>'implode(", ", Session::model()->findByPk($data["id"])->getAssignedStudentsArrs("/admin/student/view/id"))',
                   'type'  => 'raw',
                   'htmlOptions'=>array('style'=>'min-width:150px; max-width:400px;'),
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
                   'header' => 'Trạng thái tính tiền',
                   'value'=>'$data["paid_session"] ? "Paid" : ($data["paid_session"] === "0" ? "Unpaid" : "")',
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
                    'value'=>'createEditButton($data["id"], $data["status"], $data["using_platform"], $data["paid_session"], $data["note"])',
                    'type'=>'raw',
                    'htmlOptions'=>array('style'=>'width:40px; text-align:center;vertical-align:top;'),
                ),
            ),
        ));
    }
?>
<script>
	function editSessionNote(sessionId, using_platform, paid_session, note){
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
                console.log(paid_session);
                var paidOptions = elementCreator.option(1, "Có", paid_session == 1 ? "selected" : "");
                paidOptions += elementCreator.option(0, "Không", paid_session == 0 ? "selected" : "");
                
                form += formCreator.newRow(
                    'Tính tiền',
                    elementCreator.select({id:"teacher_paid", name:"SessionNote[paid_session]"}, paidOptions)
                );
                
				form += formCreator.newHtmlRow(
					'<div class="label" style="vertical-align:top">Ghi chú</div>' +
					'<div class="value">' + elementCreator.textarea({id:"note",name:"SessionNote[note]"}, note) + "</div>"
				);
				form += formCreator.newRow("&nbsp;","<button id='saveNote'>Lưu lại</button>" +
													 "<button id='cancel' onclick='removePopupByID"+'("popupAll")'+";return false;'>Hủy</button>");
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
                
                form += formCreator.newRow("&nbsp;","<button id='saveStatus'>Lưu lại</button>" +
													 "<button id='cancel' onclick='removePopupByID"+'("popupAll")'+";return false;'>Hủy</button>");
                                                     
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