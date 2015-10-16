<form class="form-inline" role="form" method="get" style="padding-bottom:0">
    <div class="form-group">
        <label class="form-label"><?php echo Yii::t('lang', 'search_teacher')?>: </label>
        <input id="teacherSearchBox" type="text" class="form-control" placeholder="<?php echo Yii::t('lang', 'student_search_teacher_placeholder')?>" style="width:500px;">
        <input id="teacherId" type="hidden" name="teacher">
        <input type="submit" value="<?php echo Yii::t('lang', 'search')?>" class="btn" style="margin-top: 0px">
    </div>
 </form>
 <script>
$(document).ready(function(){
    SearchBox.bindSearchEvent("#teacherSearchBox", AjaxCall.searchTeacher, displaySearchResults);
});

function displaySearchResults(results){
    SearchBox.autocomplete({
        searchBox:'#teacherSearchBox',
        results:results,
        resultLabel:'fullName',
        selectCallback:function(id){
            $('#teacherId').val(id);
        }
    });
}
 </script>