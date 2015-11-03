<div class="form-inline" role="form" style="margin:0 auto 10px; width:700px">
    <div class="form-group">
        <label class="form-label">Tìm giáo viên: </label>
        <input id="teacherSearchBox" type="text" class="form-control" placeholder="Nhập tên giáo viên để tìm kiếm" style="width:500px;">
        <input id="searchTeacherId" type="hidden" name="teacher">
        <input id="searchButton" type="button" value="Tìm" class="btn" style="margin-top: 0px">
    </div>
</div>
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
                window.location.href="/admin/schedule/view?teacher="+id;
            }
        }, search);
    }
</script>
