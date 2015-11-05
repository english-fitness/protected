<?php
function createEditButton($session){
    if (($session->status != Session::STATUS_ENDED && $session->status != Session::STATUS_CANCELED && !$session->isTimedOut())
        || $session->deleted_flag == 1){
        return "";
    }

    $formFields = array(
        "session_id"=>$session->id,
        "status"=>$session->status,
        "status_note"=>$session->status_note,
        "teacher_paid"=>$session->teacher_paid,
        "content"=>$session->content,
    );

    if ($session->note != null){
        $formFields["using_platform"] = $session->note->using_platform;
        $formFields["note"] = $session->note->note;
    }

    if ($session->teacherFine != null){
        $formFields["teacher_fine_points"] = $session->teacherFine->points;
        $formFields["taecher_fine_note"] = $session->teacherFine->notes;
    }

    $onclick = "editSessionNote(".json_encode($formFields)."); return false;";
    
    return CHtml::link(
        "",
        "#",
        array(
            "class"=>"btn-edit mL10",
            "onclick"=>$onclick,
        )
    );
}

function getStatusDisplay($session){
    if ($session->deleted_flag == 1){
        return "Đã xóa";
    }
    $status = $session->status;
    if($status != Session::STATUS_ENDED && $status != Session::STATUS_CANCELED){
        if ($session->isTimedOut()){
            return '<span style="color:orange">' . Session::statusOptions()[$status] . '<br>(Hết giờ)</span>';
        } else {
            return Session::statusOptions()[$status];
        }
    } else {
        $redText = $status == Session::STATUS_CANCELED ? 'style="color:red"' : '';
        return '<span ' . $redText . '>' .
                    Session::statusOptions()[$status] .
                '</span>';
    }
}
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
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'gridView',
	'dataProvider'=>$sessions,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'htmlOptions'=>array('style'=>'vertical-align:top;'),
    'rowHtmlOptionsExpression'=>'($data->deleted_flag==1)?array("class"=>"deletedRecord"):array()',
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
           'header' => 'Skype / Platform',
           'value'=>'$data->note != null ? ($data->note->using_platform ? "Platform" : "Skype") : ""',
           'htmlOptions'=>array('style'=>'width:80px; text-align:center;vertical-align:top;'),
        ),
        array(
           'header' => 'Tính tiền cho giáo viên',
           'value'=>'$data["teacher_paid"] ? "Paid" : ($data["teacher_paid"] === "0" ? "Unpaid" : "")',
           'htmlOptions'=>array('style'=>'width:80px; text-align:center;vertical-align:top;'),
        ),
        array(
           'header' => 'Ghi chú',
           'value'=>'$data->status == Session::STATUS_CANCELED ? $data->status_note : ($data->note != null ? $data->note->note : "")',
           'htmlOptions'=>array('style'=>'width:250px;vertical-align:top;'),
           'type'=>'raw',
        ),
        array(
            'header'=>'',
            'value'=>'createEditButton($data)',
            'type'=>'raw',
            'htmlOptions'=>array('style'=>'width:40px; text-align:center;vertical-align:top;'),
        ),
	),
)); ?>
<div id="session-form" class="container-fluid dpn">
    <input type="hidden" id="session-id">
    <form id="form">
        <div class="row">
            <div class="col-md-3">
                <label class="form-row-label">Trạng thái</label>
            </div>
            <div class="col-md-9">
                <select id="status" name="Session[status]">
                    <option value="3">Đã kết thúc</option>
                    <option value="4">Đã hủy</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <label class="form-row-label">Tính tiền cho giáo viên</label>
            </div>
            <div class="col-md-9">
                <select id="teacher_paid" name="Session[teacher_paid]">
                    <option value="1">Có</option>
                    <option value="0">Không</option>
                </select>
            </div>
        </div>
        <div id="status-note" class="row dpn">
            <div class="col-md-3">
                <label>Lý do hoãn/hủy</label>
            </div>
            <div class="col-md-9">
                <textarea id="session-status-note" name="Session[status_note]" style="height:100px;max-width:651px"></textarea>
            </div>
        </div>
        <div class="session-note dpn">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-row-label">Platform/Skype</label>
                </div>
                <div class="col-md-9">
                    <select id="using_platform" name="SessionNote[using_platform]">
                        <option value="1">Platform</option>
                        <option value="0">Skype</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>Nội dung</label>
                </div>
                <div class="col-md-9">
                    <textarea id="session-content" name="Session[content]" style="height:100px;max-width:651px"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>Ghi chú</label>
                </div>
                <div class="col-md-9">
                    <textarea id="session-note" name="SessionNote[note]" style="height:100px;max-width:651px"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col col-md-3">
                <span><input type="checkbox" class="teacher-fine-ckbox" value="teacher-fined"><b>&nbsp;Giáo viên bị phạt</b></span>
            </div>
        </div>
        <div class="teacher-fine dpn">
            <div class="row">
                <div class="col-md-3">
                    <label>Điểm phạt <span style="color:red">*</span></label>
                </div>
                <div class="col-md-9">
                    <input id="teacher-fine-points" type="text" name="TeacherFine[points]" style="width:100%">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label>Ghi chú</label>
                </div>
                <div class="col-md-9">
                    <textarea id="teacher-fine-note" name="TeacherFine[notes]" style="max-width:651px;height:100px"></textarea>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    var STATUS_ENDED = <?php echo Session::STATUS_ENDED?>;
    var STATUS_CANCELED = <?php echo Session::STATUS_CANCELED?>;
    var form;
    var popupDialog = new BootstrapDialog({
        title: "Ghi chú buổi học",
        message: function(){
            form = $("#session-form").removeClass("dpn");

            form.find(".teacher-fine-ckbox").change(function(){
                if ($(this).is(":checked")){
                    form.find(".teacher-fine").show().find("input, textarea").prop("disabled", false);
                } else {
                    form.find(".teacher-fine").hide().find("input, textarea").prop("disabled", true);
                }
            });

            form.find("#status").change(function(){
                var $this = $(this);
                if ($this.val() == STATUS_ENDED){
                    form.find(".session-note").show().find("input, textarea, select").prop("disabled", false);
                    form.find("#status-note").hide().prop("disabled", true);
                } else if ($this.val() == STATUS_CANCELED) {
                    form.find(".session-note").hide().find("input, textarea, select").prop("disabled", true);
                    form.find("#status-note").show().prop("disabled", false);
                }
            })

            return form;
        },
        buttons:[
            {
                label:"Lưu lại",
                action:function(dialog){
                    var button = this;
                    button.spin();
                    var sessionId = form.find("#session-id").val();
                    var formData = form.find("#form").serialize();

                    saveSessionNote(sessionId, formData, function(success){
                        button.stopSpin();
                        dialog.close();
                        if (!success){
                            BootstrapDialog.show({
                                title: "Ghi chú chưa được lưu",
                                message: "Đã có lỗi xảy ra, vui lòng thử lại sau",
                                buttons:[{
                                    label:"Đóng",
                                    action:function(errDialog){
                                        errDialog.close();
                                    }
                                }]
                            })
                        }
                    })

                }
            },
            {
                label:"Hủy",
                action:function(dialog){
                    dialog.close();
                }
            }
        ],
        autodestroy:false,
    });
    popupDialog.realize();
	function editSessionNote(formFields){
        // console.log(formFields);
        form.find("#session-id").val(formFields.session_id);

        if (formFields.status == STATUS_ENDED || formFields.status == STATUS_CANCELED){
            form.find("#status").val(formFields.status).prop("disabled", true);
        } else {
            form.find("#status").val(STATUS_ENDED).prop("disabled", false);
        }

        form.find("#status").change();

        //empty or equal to 0
        if (formFields.teacher_paid == 0){
            form.find("#teacher_paid").val(0);
        } else {
            form.find("#teacher_paid").val(1);
        }

        //empty or equal to 0
        if (formFields.using_platform == 0){
            form.find("#using_platform").val(0);
        } else {
            form.find("#using_platform").val(1);
        }

        if (!formFields.note){
            form.find("#session-note").val("");
        } else {
            form.find("#session-note").val(formFields.note);
        }

        if (!formFields.status_note){
            form.find("#session-status-note").val("");
        } else {
            form.find("#session-status-note").val(formFields.status_note);
        }

        if (!formFields.content){
            form.find("#session-content").val("");
        } else {
            form.find("#session-content").val(formFields.content);
        }

        if (formFields.teacher_fine_points){
            form.find(".teacher-fine-ckbox").prop("checked", true).prop("disabled", true).change();
            form.find("#teacher-fine-points").val(formFields.teacher_fine_points);
            if (formFields.taecher_fine_note){
                form.find("#teacher-fine-note").val(formFields.taecher_fine_note);
            }
        } else {
            form.find(".teacher-fine-ckbox").prop("checked", false).prop("disabled", false).change();
            form.find("#teacher-fine-points").val("");
            form.find("#teacher-fine-note").val("");
        }

        popupDialog.open();
	}

    function saveSessionNote(sessionId, formData, callback){
        $.ajax({
            url: "/admin/sessionMonitor/saveSessionNote/id/"+sessionId,
            type:"post",
            data:formData,
            success: function(response){
                if (response.success){
                    $.fn.yiiGridView.update("gridView");
                    callback(true);
                } else {
                    callback(false);
                }
            },
        });
    }
</script>