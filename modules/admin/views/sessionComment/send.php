<?php
/* @var $this SessionCommentController */
/* @var $model SessionComment */
?>

<style>
#teacherComment{
    margin-top: 10px;
    margin-left: 15px;
    padding-bottom: 15px;
    border-bottom: solid 1px rgba(0,0,0,0.1);
}
#studentCommentTable{
	width:100%;
	border:1px solid black;
	text-align:center;
}
#studentCommentTable th{
	padding:4px;
	border:1px solid black;
	text-align:center;
}
#studentCommentTable td{
	padding:8px;
	border:1px solid black;
    vertical-align:top;
}
.tick{
    background: url("/media/images/icon/tick.png") center / 30px no-repeat;
    height: 30px;
    width: 30px;
    margin-top: -5px;
    display: inline-block;
    vertical-align: middle;
}
</style>
<div style="margin-left:15px">
    <a href="/admin/session/ended"><< Quay lại danh sách</a>
</div>
<div id="teacherComment" class="clearfix">
	<?php if($teacherComment == null):?>
	<p>Chưa có ghi chú</p>
	<?php else:?>
    <div style="float:left; width:50%;">
        <p><b>Nội dung</b></p>
        <textarea id="content" style="max-width:637px; margin-bottom:10px"><?php echo $teacherComment->comment?></textarea>
        <p><b>Bản dịch</b></p>
        <textarea id="translation" style="max-width:637px"></textarea>
    </div>
    <div style="float:right; margin-left:30px; width:47.6%">
        <?php if($students != null):?>
        <p><b>Gửi nhận xét của giáo viên cho các học sinh</b></p>
        <?php foreach($students as $student):?>
            <input type="checkbox" name="students" value=<?php echo $student->id?> checked>
                <?php echo $student->fullname() . " (" . $student->email . ")"?>
            </input>
        <?php endforeach;?>
        <div style="padding-top:10px">
            <button id="sendToStudents" class="btn btn-primary">Gửi</button>
        </div>
        <?php else:?>
            <p><b>Chưa có học sinh</b></p>
        <?php endif;?>
    </div>
	<?php endif;?>
</div>
<div id="studentComment" style ="margin-top:10px; margin-left:15px">
	<p><b>Nhận xét của học sinh</b></p>
	<?php if(count($studentComment) <= 0):?>
	<p>Chưa có nhận xét</p>
	<?php else:?>
	<table id="studentCommentTable">
		<thead>
			<th style="width:200px">Học sinh</th>
			<th style="width:100px">Đánh giá</th>
			<th>Nhận xét</th>
		</thead>
		<tbody>
		<?php foreach($studentComment as $comment):?>
			<tr>
				<td><?php echo Yii::app()->user->getFullNameById($comment->user_id)?></td>
				<td><?php echo $comment->rating?></td>
				<td style="text-align:left"><?php echo ($comment->comment != null && $comment->comment != "") ? nl2br($comment->comment) : "Chưa có nhận xét"?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
    <div style="padding-top:10px">
        <button id="sendStudentComments" class="btn btn-primary">Gửi</button>
    </div>
	<?php endif;?>
</div>
<script>
    var sending = false;
    $('#sendToStudents').click(function(){
        if (sending){
            return false;
        }
        
        sending = true;
        var button = $(this);
        button.removeClass('btn-primary');
        button.after('<img style="margin-left:5px" src="/media/images/icon/fb-loader.gif"/>')
        
        var students = [];
        $("input:checkbox[name=students]:checked").each(function(){
            students.push(this.value);
        });
        
        var content = document.getElementById('content').value;
        var translation = document.getElementById('translation').value;
        
        if (translation == ''){
            alert("Hãy điền bản dịch cho nội dung ghi chú");
            button.addClass('btn-primary');
            button.next().remove();
            sending = false;
            return false;
        }
        
        $.ajax({
            url:'/admin/sessionComment/sendToStudents',
            type:"post",
            data:{
                students:students,
                content:content,
                translation:translation,
                date:'<?php echo date('d/m/Y', strtotime($session->plan_start))?>',
                time:'<?php 
                        echo date('H:i', strtotime($session->plan_start)) . 
                             ' - ' .
                             date('H:i', strtotime('+' . $session->plan_duration . ' minute', strtotime($session->plan_start)))
                    ?>',
            },
            success:function(response){
                if (response.success){
                    var parent = button.parent();
                    parent.empty();
                    parent.append('<div class="tick"></div>');
                    parent.append('<span>Đã gửi</span>');
                } else {
                    alert("Đã có lỗi xảy ra. Vui lòng thử lại sau");
                    button.addClass('btn-primary');
                    button.next().remove();
                    sending = false;
                }
            },
            error:function(){
                alert("Đã có lỗi xảy ra. Vui lòng thử lại sau");
                button.addClass('btn-primary');
                button.next().remove();
                sending = false;
            }
            
        });
    });
</script>