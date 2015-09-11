<form class="form-inline" role="form" style="margin:0 auto; width:700px">
    <div class="form-group">
        <label class="form-label">Tìm giáo viên: </label>
        <input id="teacherSearchBox" type="text" class="form-control" placeholder="Nhập tên giáo viên để tìm kiếm" style="width:500px;">
        <input id="searchTeacherId" type="hidden" name="teacher">
        <input type="submit" value="Tìm" class="btn" style="margin-top: 0px">
    </div>
</form>
<script>
    $(function(){
        bindSearchBoxEvent("teacherSearchBox", searchTeacher);
    });

    function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				searchBoxAutocomplete('teacherSearchBox', data, function(id){$('#searchTeacherId').val(id);});
			}
		});
	}
</script>
