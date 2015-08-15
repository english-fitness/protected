<form class="form-inline" role="form" style="margin:0 auto 20px; width:700px">
    <div class="form-group">
        <label class="form-label">Select teacher: </label>
        <input id="teacherSearchBox" type="text" class="form-control" placeholder="Enter teacher name to search" style="width:500px;">
        <input id="searchTeacherId" type="hidden" name="teacher">
        <input type="submit" value="Select" class="btn" style="margin-top: 0px">
    </div>
</form>
<script>
    $(function(){
        bindSearchBoxEvent("teacherSearchBox", searchTeacher);
		$('#cancelChangeSchedule').click(function(){
			toggleChangeSchedule(false);
		});
    });

    function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/schedule/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				searchBoxAutocomplete('teacherSearchBox', data, function(id){$('#searchTeacherId').val(id);});
			}
		});
	}
</script>