<?php
if (isset($assignedUser)){
    if (!is_object($assignedUser) || get_class($assignedUser) != 'User'){
        if (is_numeric($assignedUser)){
            $assignedUser = User::model()->findByPk($assignedUser);
        } else {
            $assignedUser = null;
        }
    }
}
?>
<style type="text/css">
    .ui-autocomplete { max-height: 200px; overflow-y: scroll;}
</style>
<span class="addUser" style="padding-bottom:10px">
	<span class="loadUser" id="ajaxLoadSelectedUser">
        <?php if (isset($assignedUser)):?>
            <?php 
            if (is_array($assignedUser)):
                foreach($assignedUser as $user):?>
                    <span data-id="<?php echo $user->id?>" title="<?php echo $user->fullname()." (".$user->username.")"?>">
                        <i class="icon-remove removeUser"></i>
                        <a target="_blank" href="<?php echo "/admin/student/view/id/".$user->id?>"><?php echo $user->fullName()?></a>
                        <input type="hidden" name="extraUserIds[]" value="<?php echo $user->id?>">
                    </span>
            <?php 
                endforeach;
            else:?>
                <span data-id="<?php echo $assignedUser->id?>" title="<?php echo $assignedUser->fullname()." (".$assignedUser->username.")"?>">
                    <i class="icon-remove removeUser"></i>
                    <a target="_blank" href="<?php echo "/admin/student/view/id/".$assignedUser->id?>"><?php echo $assignedUser->fullName()?></a>
                    <input type="hidden" name="extraUserIds[]" value="<?php echo $assignedUser->id?>">
                </span>
            <?php endif;?>
        <?php endif;?>
    </span>  
    <span class="input pL5">
        <input id="ajaxSearchUser" class="form-control class_email" style="width:250px" name="ajaxSearchUser" placeholder="Họ tên hoặc Username"/>
        <button type="button" id="ajaxSearch" style="float:left;margin-top:2px"><i class="icon-search"></i></button>
    </span>
</span>

<?php
    if (!isset($userRole)){
        $userRole = null;
    }
    switch ($userRole) {
        case User::ROLE_STUDENT || 'student':
            $ajaxCall = "AjaxCall.searchStudent";
            break;
        case User::ROLE_TEACHER || 'teacher':
            $ajaxCall = "AjaxCall.searchTeacher";
            break;
        default:
            $ajaxCall = "AjaxCall.searchUser";
            break;
    }
?>
<script type="text/javascript">
    SearchBox.bindSearchEvent("#ajaxSearchUser", <?php echo $ajaxCall?>, displayUserSearchResult);

    $("#ajaxSearch").click(function(){
        var searchBox = $("#ajaxSearchUser");
        SearchBox.search(searchBox, <?php echo $ajaxCall?>, function(results){
            displayUserSearchResult(results, true);
            searchBox.focus();
        });
    });

    function displayUserSearchResult(results, search){
        SearchBox.autocomplete({
            searchBox:'#ajaxSearchUser',
            results:results,
            resultLabel:'usernameAndFullName',
            resultValue:'fullName',
            selectCallback:AddUser,
        }, search);
    }

    function AddUser(id, value, label){
        var ajaxLoadSelectedUser =$("#ajaxLoadSelectedUser");
        if(value && !ajaxLoadSelectedUser.find("span[data-id='"+id+"']").html()) {
            var html = '<span data-id="'+id+'" title="'+label+'""><i class="icon-remove removeUser"></i><a target="_blank" href="<?php echo Yii::app()->baseurl; ?>/admin/student/view/id/'+id+'">' +
                value+' </a>' +
                '<input type="hidden" name="extraUserIds[]" value="'+id+'"></span>&#x200b;';
            ajaxLoadSelectedUser.append(html);
        }
        setTimeout(function(){
            $("#ajaxSearchUser").val("").focus();
        }, 0);
    }

    $(document).on("click","#ajaxLoadSelectedUser .removeUser",function(){
        $(this).parent().remove();
    });

</script>