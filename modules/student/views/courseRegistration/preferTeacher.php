<?php $this->renderPartial('step'); ?>
<link href="<?php echo Yii::app()->baseUrl; ?>/media/css/jquery/jquery-ui.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/media/js/jquery/jquery-ui.js"></script>
<script type="text/javascript">
	//Display More teachers
	function displayMoreTeachers(){		
		$('.priorityPreferTeacher').css("display", "");
		$('#divMoreMessage').css("display", "none");
	}
	$(function() {
        $( "#sortable" ).sortable();
        $( "#sortable" ).disableSelection();
        /*$('#sortable .priorityPreferTeacher').bind('mousedown.sortable',function(ev){
            $("#test").html($(this).attr("rel"));
        });*/
    });
	function backStep(){
		window.location = '<?php echo Yii::app()->baseUrl; ?>/student/courseRegistration/review';
	}
</script>
<div class="form">
    <form action="<?php echo $_SERVER['REQUEST_URI']?>" method="post"  class="form-horizontal" role="form">
        <div class="form-group">
            <label class="mL10 mR15">Chọn thứ tự ưu tiên cho giao viên mà bạn muốn học cùng nhất bằng cách kéo/thả profile của các giáo viên lên trên/xuống dưới theo thứ tự ưu tiên!</label><br/>
			<label class="mL10 mR15">Bạn có thể bỏ qua bước này, chúng tôi sẽ chọn giáo viên phù hợp nhất cho bạn (ưu tiên 1 là bạn muốn được học cùng thầy/cô đó nhất)</label>
        </div>
        <div id="test" class="clearfix">&nbsp;</div>
        <div class="form-group">
        	<table class="table table-bordered table-striped data-grid">
        		<tr>
        			<td height="2" width="100"><b>Thứ tự ưu tiên</b></td>
        			<td><b>Danh sách giáo viên</b></td>
        		</tr>
        		<tr>
        			<td width="100">
        				<?php
        					$countTeacher = count($availableTeachers);
        					$maxPriorityIndex = ($countTeacher<3)? $countTeacher: 3;
        				?>
        				<?php for($row=1;$row<=$maxPriorityIndex;$row++):?>
        				<div class="priorityBox">Ưu tiên&nbsp;<?php echo $row;?></div>
        				<?php endfor;?>
        			</td>
        			<td style="border-left:none; padding-left:0px;">
        				<ul id="sortable" style="margin-left:0px;">
			                <?php
			                    if(count($countTeacher)>0):
			                        $index = 1;
			                        foreach($availableTeachers as $teacher):
			                ?>
			                <li class="priorityPreferTeacher" style="<?php echo ($index>5)? "display:none": ""?>" rel="<?php echo $index;?>">
			                    <div class="col-sm-10 fL pL10">
			                        <?php $teacherProfile = Teacher::model()->findByPk($teacher->id);?>
			                        <input type="hidden" name="preferTeachers[]" value="<?php echo $teacher->id?>" class="txtPrefer">
			                        <span><b><?php echo $teacher->fullName();?></b></span><br/>
			                        <span><?php echo $teacherProfile->title;?></span><br/>
			                        <span title="<?php echo $teacherProfile->short_description;?>" style="cursor:default;"><?php echo Common::truncate($teacherProfile->short_description, 200);?></span><br/>
			                    </div>
			                    <div class="col-sm-1 fR"><div class="profile-image "><img style="min-width: 50px; min-height: 50px;" src="<?php echo  Yii::app()->user->getProfilePicture($teacher->id) ?>" alt="data" /></div></div>
			                    <div class="clearfix">&nbsp;</div>
			                </li>
			                <?php
			                    $index++;
			                    endforeach; endif;
			                ?>
			                <?php if($index>5):?>
			                <div id="divMoreMessage"class="text-center"><a href="javascript:displayMoreTeachers();">Xem thêm giáo viên</a></div>
			                <?php endif;?>
			           </ul>
        			</td>
        		</tr>
        	</table>	
        </div>
        <div class="row-form">
            <div class="col-sm-2 label">&nbsp;</div>
            <div class="col-sm-7 value">
                <button class="btn btn-default prev-step" name="prevStep"  type="button" onclick="backStep();">Quay lại</button>
                <button class="btn btn-primary next-step" name="nextStep" type="submit">Hoàn thành</button>
            </div>
        </div>
    </form>
</div>