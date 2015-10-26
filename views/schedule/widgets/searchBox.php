<form class="form-inline" role="form" style="margin:0 auto 20px; width:700px">
    <div class="form-group">
        <label class="form-label">Select teacher: </label>
        <input id="teacherSearchBox" type="text" class="form-control" placeholder="Enter teacher name to search" style="width:500px;">
        <input id="searchTeacherId" type="hidden" name="teacher">
        <input id="searchButton" type="button" value="Tìm" class="btn" style="margin-top: 0px">
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
                window.location.href="/schedule/view?teacher="+id;
            }
        }, search);
    }
</script>
