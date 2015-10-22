<script language="javascript" type="text/javascript">
	function resizeIframe(obj) {
		obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
	}
</script>
<style type="text/css">
	iframe{
		border: none;
	}
</style>
<div class="row">
	<a href="<?php echo Yii::app()->request->urlReferrer?>" class="btn btn-primary fL mL10">Quay lại</a>
	<a href="/admin/student/sendMail/sid/<?php echo $sid?>" class="btn btn-primary fR mR20">Gửi Email</a>
</div>
<div class="col col-lg-3">
	<div class="row">
		<h3>
			<b>Thông tin học viên</b>
			<a href="/admin/student/update/id/<?php echo $sid?>" title="Sửa thông tin"><img src="/media/images/admin/icon/edit.png"></a>
		</h3>
	</div>
	<iframe src="/admin/student/studentWidget/sid/<?php echo $sid?>" width="320" height=0 style="margin-left:-15px" onload='javascript:resizeIframe(this);'></iframe>
</div>
<div class="col col-lg-6" style="border-left:solid 1px black; padding-left:30px">
	<div class="row">
		<h3>
			<b>Thông tin khóa học</b>
			<a href="/admin/course/create?sid=<?php echo $sid?>" title="Thêm khóa học">
				<img style="margin-top:-2px" src="/media/images/admin/icon/add.png">
			</a>
		</h3>
	</div>
	<div class="row">
		<iframe src="/admin/student/courseWidget/sid/<?php echo $sid?>" width="960" height=0 onload='javascript:resizeIframe(this);'></iframe>
	</div>
	<div class="row"><h3><b>Thông tin học phí</b></h3></div>
	<div class="row">
		<iframe src="/admin/student/tuitionWidget/sid/<?php echo $sid?>" width="960" height=0 onload='javascript:resizeIframe(this);'></iframe>
	</div>
</div>
