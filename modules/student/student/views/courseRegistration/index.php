<?php
$this->renderPartial('step');
$course = $registration->getSession("course");
?>
<script type="text/javascript">
	function checkValidStep1(){
		var subject_id, title, checkvalid;
		checkvalid = true;
		subject_id = $("#Course_subject_id").val();
		title = $("#Course_title").val();
		if(subject_id=="" || title==""){
			checkvalid = false;
		}
		return checkvalid;
	}
    function setPriceCourse(CountSession,currentPrice)
    {
        var price = CountSession*currentPrice;
        $(".price").html(price+"đ");
    }
    $("#numberOfSession").click(function(){
        var currentPrince = null;
        $(".price").html();
    });
  	//Auto complete suggest title for course
	function suggestTitles(){
		var data = {'subject_id': $('#Course_subject_id').val()};
		$.ajax({
			url: "<?php echo Yii::app()->baseUrl; ?>" + "/student/courseRegistration/ajaxLoadSuggestion",
			type: "POST", dataType: 'JSON',data:data,
			success: function(data) {
				$( "#Course_title" ).autocomplete({
				      source: data.suggestions
				});
			}
		});
	}
	$(document).ready(function() {
		if($('#Course_subject_id').val()!=""){
			suggestTitles();//Load default subject suggestions
		}
		$('#btnNextStep').click(function(){
			var check_valid = checkValidStep1();
			if(check_valid){
				$("#formStep1").submit();
			}else{
				$("#validMessage").html('<span class="required">Vui lòng kiểm tra lại những trường dữ liệu bắt buộc* (lớp, môn, chủ đề, số buổi)!</span>');
			}
		});
		$('#tutorClasses').change(function(){
			//Load ajax subjects by class
			var data = {'class_id': $(this).val()};
			$.ajax({
				url: "<?php echo Yii::app()->baseUrl; ?>" + "/student/courseRegistration/ajaxLoadSubject",
				type: "POST", dataType: 'html',data:data,
				success: function(data) {
					$('#divDisplaySubject').html(data);
				}
			});
		});
	});

</script>
<div class="form">
    <form id="formStep1" action="<?php echo  $_SERVER['REQUEST_URI']?>" method="post">
        <div class="form_notice" id="validMessage">
        	<?php if(!$checkValid):?>
        	<span class="required">Vui lòng kiểm tra lại những trường dữ liệu bắt buộc* (lớp, môn, chủ đề, số buổi)!</span>
        	<?php endif;?>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Chọn lớp học: <span class="required">*</span></div>
            <div class="value col-sm-7" >
                <?php
                $classes = CHtml::listData(Classes::model()->findAll(array("condition"=>"name<>'daykem123'")), 'id', 'name');
                $classes = array(""=>"Chọn lớp...") + $classes;
                $selectedClassId = isset($course['class_id'])? $course['class_id']: "";
                echo CHtml::dropDownList('Course[class_id]', $selectedClassId, $classes, array('id'=>'tutorClasses',"style"=>"width:auto;"));?>
                <b>Môn:<span class="required">*</span> &nbsp;</b>
                <span id="divDisplaySubject">
                    <?php
                    if(isset($course['class_id']) && $course['class_id']!=""){
                        $classSubjects = CHtml::listData(Subject::model()->findAllByAttributes(array('class_id'=>$course['class_id'])), 'id', 'name');
                    }else{
                        $classSubjects = array(""=>"Chọn môn...");
                    }
                    $selectedSubjectId = isset($course['subject_id'])? $course['subject_id']: "";
                    echo CHtml::dropDownList('Course[subject_id]', $selectedSubjectId, $classSubjects, array('id'=>'Course_subject_id',"style"=>"width:150px", "onchange"=>"suggestTitles()"));?>
                </span>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Chủ đề khóa học: <span class="required">*</span></div>
            <div class="value col-sm-7">
                <input id="Course_title" class="form-control" name="Course[title]" type="text" value="<?php echo $course['title']; ?>"
                       placeholder="VD: Ôn thi môn đại số cơ bản"/>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Mô tả khóa học:</div>
            <div class="value col-sm-7">
                <textarea style="height: 100px;" class="form-control" name="Course[content]" placeholder="VD: Củng cố kiến thức học toán" ><?php echo $course['content']; ?></textarea>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Tổng số buổi học trong khóa:<span class="required">*</span></div>
            <div class="value col-sm-7">
                <select onchange="setPriceCourse(this.value,0)" name="Course[numberOfSession]" id="numberOfSession" style="width: 80px">
                    <?php
                    	$totalOptions = $registration->totalSessionOptions(); 
                    	foreach($totalOptions as $key=>$label):
                        $selected = (isset($course['numberOfSession']) && $course['numberOfSession']==$key)? "selected='selected'": "";
                    ?>
                        <option value="<?php echo $key?>" <?php echo $selected; ?>><?php echo $label;?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Tổng tiền :</div>
            <div class="value col-sm-7">
                <div class="price">0đ</div>
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">Chọn kiểu lớp: </div>
            <div class="value col-sm-7">
                <?php $totalOfStudent = isset($course['total_of_student'])? $course['total_of_student']: 1;?>
                <input type="radio" name="Course[total_of_student]" value="1" <?php if($totalOfStudent==1):?>checked="checked"<?php endif;?>> Lớp 1-1
                <input type="radio" name="Course[total_of_student]" value="2"  <?php if($totalOfStudent==2):?>checked="checked"<?php endif;?>> Lớp 1-2
            </div>
        </div>
        <div class="row-form">
            <div class="label col-sm-3">&nbsp;</div>
            <div class="value col-sm-7">
                <button type="button" id="btnNextStep" name="nextStep" class="btn btn-primary next-step">Tiếp tục</button>
                <div class="clearfix h1">&nbsp;</div>
            </div>
        </div>
    </form>
</div>
<!--.form-->