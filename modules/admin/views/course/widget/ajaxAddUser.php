<style type="text/css">
    .ui-autocomplete { max-height: 200px; overflow-y: scroll;}
</style>
<span class="addUser">
	<span class="loadUser" id="ajaxLoadSelectedUser"></span>  
    <span class="input pL5">
        <input id="ajaxSearchUser" class="form-control class_email" name="ajaxSearchUser" placeholder="Họ tên hoặc Email" value="<?php echo isset($ajaxSearchUser)? $ajaxSearchUser: "";?>"/>
        <button type="button" id="ajaxSearch"><i class="icon-search"></i></button>
        <button type="button" id="ajaxAddUser" name="addUser">Thêm</button>
    </span>
</span>

<script type="text/javascript">
    var availableTags = [];
    /* ready */
    $(document).ready(function(){
        /*formatAvailableTags */
        function formatAvailableTags(availableTags){
            var resultAvailableTags = [];
            availableTags.forEach(function(value,key){
                resultAvailableTags[resultAvailableTags.length] = value.emailAndFullName;
            });
            return resultAvailableTags;
        }
        /*load autocomplete */
        function loadAutoComplete(availableTags) {
            /* auto complete */
            $("#ajaxSearchUser").autocomplete({
                source: formatAvailableTags(availableTags),
                height:"50"
            });
        }

        /* key up */
        $("#ajaxSearchUser").keyup(function(){
            var value =  $(this).val();
            if(value.length<=3 && value.length>0) {
                ajaxLoadUsersByValue(value);
            }
        });

        /* ajax Load Users By Value */

        function ajaxLoadUsersByValue(value) {
            $.ajax({
                url:"<?php echo Yii::app()->baseurl; ?>/admin<?php echo isset($ajaxBaseUrl)?$ajaxBaseUrl:"/course/AjaxLoadUser"; ?>/keyword/"+value,
                type:"get",
                success: function(users) {
                    availableTags = users[0];
                    loadAutoComplete(availableTags);
                    $( "#ajaxSearchUser" ).autocomplete( "search", value );
                }
            });
        }

        /* render Object By Email */
        function renderObjectByEmail(availableTags,email){
            var renderObjectByEmail = [];
            availableTags.forEach(function(value,key){
                if(value.emailAndFullName == email) {
                    renderObjectByEmail = value;
                }
            });
            return renderObjectByEmail;
        }

        /* ajax Add User */
        $("#ajaxSearch").click(function(){
            var value =  $("#ajaxSearchUser").val();
            var object = renderObjectByEmail(availableTags, value);
            if(object.email)
                return ajaxLoadUsersByValue(renderObjectByEmail(availableTags,value).email);
            return ajaxLoadUsersByValue(value);
        });

        /*addUser submit */
        $("#ajaxAddUser").click(function(){
            var email = $("#ajaxSearchUser").val();
            var object = renderObjectByEmail(availableTags, email);
            var ajaxLoadSelectedUser =$("#ajaxLoadSelectedUser");
            if(object.email && !ajaxLoadSelectedUser.find("span[data='"+object.id+"']").html()) {
                var html = '<span data="'+object.id+'"><i class="icon-remove removeUser"></i><a target="_blank" href="<?php echo Yii::app()->baseurl; ?>/admin/student/view/id/'+object.id+'">' +
                    object.fullName+' </a>' +
                    '<input type="hidden" name="extraUserIds[]" value="'+object.id+'"></span>';
                ajaxLoadSelectedUser.append(html);
            }
            $("#ajaxSearchUser").val("");
            $("#ajaxSearchUser").focus();
        });

        /* remove addUser*/
        $(document).on("click","#ajaxLoadSelectedUser .removeUser",function(){
            $(this).parent().remove();
        });
    });
</script>