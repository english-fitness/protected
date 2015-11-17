<?php
	if (isset($template)){
		$template = $template;
	} else if (isset($_GET['template'])){
		$template = $_GET['template'];
	} else {
		switch (true){
			case $student->status == Student::STATUS_L5:
				$template = "testSchedule";
				break;
			case $student->status == Student::STATUS_L6:
				$template = "trialSchedule";
				break;
			case $student->status >= Student::STATUS_L8A:
				$template = "classSchedule";
				break;
			default:
				$template = 'testSchedule';
				break;
		}
	}
?>
<div class="container-fluid">
	<form id="mail-form" method="post">
		<div id="input" class="col col-lg-5 form">
			<div class="row">
				<div class="col col-lg-2" style="line-height:36px;vertical-align:middle">
					<label>Gửi từ</label>
				</div>
				<div class="col-lg-10">
					<select id="sender-select" name="Mail[sender]">
						<?php
							$availableSenders = ClsMailer::availableSenders();
							foreach ($availableSenders as $key => $value) {
								echo '<option value="'.$key.'">'.$value.'</option>';
							}
						?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="col col-lg-2" style="line-height:36px;vertical-align:middle">
					<label>Mẫu</label>
				</div>
				<div class="col col-lg-10">
					<select id="template-select" name="Mail[template]">
						<!--<option value="welcome">Thư ngỏ</option>-->
						<option value="testSchedule">Thông báo test hệ thống</option>
						<option value="trialSchedule">Thông báo học thử</option>
						<option value="classSchedule">Thông báo lịch học</option>
					</select>
				</div>
			</div>
			<div class="row form-element-container">
				<div class="col col-lg-2" style="line-height:36px;vertical-align:middle">
					<label>Email</label>
				</div>
				<div class="col col-lg-10">
					<input type="text" name="Mail[email]" class="attention-required" value="<?php echo $student->email?>" readonly>
				</div>
			</div>
			<div style="margin-top:15px">
				<div class="mail-form form" id="mail-input-form">
					<?php
						try {
							$this->renderPartial('/mailTemplate/form/'.$template, array('student'=>$student));
							echo '<input type="submit" id="send-btn" class="btn btn-primary fR">';
						} catch (Exception $e) {
							echo '<span style="color:red">Cannot dislay input form for this email template</span>';
						}
					?>
					<div style="text-align:right; font-size:1.25em">
						<div id="sending" style="display:none">
							<img style="margin-top:-3px;height:20px" src="/media/images/icon/fb-loader-blue.gif"/>&nbsp;Sending...
						</div>
						<div id="sent" style="display:none">
							<img style="margin-top:-3px;height:20px" src="/media/images/icon/tick.png"/>&nbsp;Message Sent
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	<div id="preview" class="col-lg-7" style="border-left:1px solid black">
		<div id="preview-body">
			<div class="preview-template" data-template="0">
				<?php
					try {
						$this->renderPartial('/mailTemplate/mail/'.$template, array(
							'name'=>$student->fullname(),
							'username'=>$student->username
						));
					} catch (Exception $e) {
						echo "Cannot render this email template";
					}
				?>
			</div>
		</div>
		<div id="signature">
			<?php
				try {
					$this->renderPartial("/mailTemplate/mail/signature");
				} catch (Exception $e) {
					echo "Cannot render signature";
				}
			?>
		</div>
	</div>
</div>
<script type="text/javascript">
	var requesting = false;

	$(document).ready(function(){
        $(document).on("click",".datepicker",function(){
            $(this).datepicker({
                dateFormat:"dd/mm/yy",
                firstDay:1,
            }).datepicker("show");;
        });

        $('.mail-input').bind('input', function(){
        	var $this = $(this);
        	$('.mail-attr[data-attribute="'+$this.data('attribute')+'"]').html($this.val());
        });
        $('.mail-input-auto').bind('change', function(){
        	var $this = $(this);
        	$('.mail-attr[data-attribute="'+$this.data('attribute')+'"]').html($this.val());
        });
        $('.attention-required:read-only').on('dblclick', function(){
        	$(this).prop('readonly', false);
        })
        $('#template-select').val('<?php echo $template?>');
        $('#template-select').change(function(){
        	window.location.href="/admin/student/sendMail/sid/<?php echo $student->id?>?template="+this.value;
        })
    });

    function getFormData($form){
	    var unindexed_array = $form.serializeArray();
	    var indexed_array = {};

	    $.map(unindexed_array, function(n, i){
	        indexed_array[n['name']] = n['value'];
	    });

	    return indexed_array;
	}

	function sendMail(data){
		$("#send-btn").hide();
		$("#sending").show();
		requesting = true;
		$.ajax({
			url:"/admin/student/ajaxSendMail/sid/<?php echo $student->id?>",
			type:"post",
			data:data,
			success:function(response){
				if (response.success){
					$("#sending").hide();
					$("#sent").show();
				}
			},
			error:function(){
				requesting = false;
				$("#send-btn").show();
				$("#sending").hide();
				alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
			}
		})
	}
</script>