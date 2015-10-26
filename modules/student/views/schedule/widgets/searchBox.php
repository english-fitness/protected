<form class="form-inline" role="form" method="get" style="padding-bottom:0">
    <div class="form-group">
        <label class="form-label"><?php echo Yii::t('lang', 'search_teacher')?>: </label>
        <input id="teacherSearchBox" type="text" class="form-control" placeholder="<?php echo Yii::t('lang', 'student_search_teacher_placeholder')?>" style="width:500px;">
        <input id="teacherId" type="hidden" name="teacher">
        <input id="searchButton" type="button" value="<?php echo Yii::t('lang', 'search')?>" class="btn" style="margin-top: 0px">
    </div>
</form>
<script>
    $(function(){
        $("#teacherSearchBox").val("");
        SearchBox.bindSearchEvent("#teacherSearchBox", AjaxCall.searchTeacher, displaySearchResults);
        $("#searchButton").click(function(){
            var searchBox = $("#teacherSearchBox");
            searchBox.focus();
            SearchBox.search(searchBox, AjaxCall.searchTeacher, function(results){
                displaySearchResults(results, true);
            });
        });
    });
    
    function displaySearchResults(results, search){
        SearchBox.autocomplete({
            searchBox:'#teacherSearchBox',
            results:results,
            resultLabel:'usernameAndFullName',
            selectCallback:function(id){
                $('#searchTeacherId').val(id);
                window.location.href="/student/schedule/calendar?teacher="+id;
            }
        }, search);
    }
</script>
