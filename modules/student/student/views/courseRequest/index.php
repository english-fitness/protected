<?php
    $baseModule =Yii::app()->baseurl.'/'.$this->getModule()->id;
    $user= Yii::app()->user->getData();
    $disableForm = null; $nextStepBtnClass = "btn-primary";
?>
<?php
$this->renderPartial('step',array("titlePage"=>$titlePage));
$course = $registration->getSession("course");
?>
<?php if($user->status < User::STATUS_ENOUGH_PROFILE):?>
	<?php $disableForm = 'disabled="disabled"';
		$nextStepBtnClass = 'btn-default'; 
	?>
    <div class="content pT25 pL100"><i class="icon-warning-sign"></i>
    	<?php if($user->status < User::STATUS_ENOUGH_PROFILE):?>
        	<b class="error">Vui lòng cập nhật đầy đủ thông tin cá nhân trước khi đăng ký khóa học <a href="<?php echo $baseModule?>/account/index">( Cập nhật thông tin cá nhân )</a></b>
        <?php else:?>
        	<b class="error">Vui lòng kiểm tra loa, micrô trước khi đăng ký khóa học <a href="<?php echo $baseModule?>/testCondition/index">( Tiến hành kiểm tra loa, micrô )</a></b>
        <?php endif;?>
    </div>
<?php endif;?>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/registration.js"></script>
<script type="text/javascript" >
	$(document).ready(function() {
		if($('#Course_subject_id').val()!=""){
			suggestTitles();//Load default subject suggestions
		}
	});	
</script>	
    <form class="form-horizontal" id="formStep1" action="<?php echo  $_SERVER['REQUEST_URI']?>" method="post">
        <div class="form_notice" id="validMessage">
        	<?php if(!$checkValid):?>
        	<label class="alert alert-danger">Vui lòng kiểm tra lại những trường dữ liệu bắt buộc* (lớp, môn, chủ đề)!</label>
        	<?php endif;?>
        </div>
        <div class="row">
            <label class="col-sm-2 control-label">Chọn lớp học: <span class="required">*</span></label>
            <div class=" col-sm-2" >
                <?php
                $classes = CHtml::listData(Classes::model()->getAll(false), 'id', 'name');
                $classes = array(""=>"Chọn lớp...") + $classes;
                $selectedClassId = isset($course['class_id'])? $course['class_id']: "";
                echo CHtml::dropDownList('Course[class_id]', $selectedClassId, $classes, array('id'=>'tutorClasses','class'=>'form-control'));?>
            </div>
            <div class=" col-sm-8" >
                <?php
                    if(isset($course['class_id']) && $course['class_id']!=""){
                        $classSubjects = CHtml::listData(Subject::model()->findAllByAttributes(array('class_id'=>$course['class_id'], 'allow_to_teach'=>1)), 'id', 'name');
                    }else{
                        $classSubjects = array(""=>"Chọn môn...");
                    }
                    $selectedSubjectId = isset($course['subject_id'])? $course['subject_id']: "";
                ?>
                <label class="col-sm-3 control-label">Chọn lớp học: <span class="required">*</span></label>
                <div class=" col-sm-4" id="divDisplaySubject">
                    <?php  echo CHtml::dropDownList('Course[subject_id]', $selectedSubjectId, $classSubjects, array('id'=>'Course_subject_id',"class"=>"form-control", "onchange"=>"suggestTitles()")); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 control-label">Chủ đề khóa học: <span class="required">*</span></label>
            <div class=" col-sm-7">
                <input id="Course_title" class="form-control" name="Course[title]" type="text" value="<?php echo $course['title']; ?>"
                       placeholder="VD: Ôn thi môn đại số cơ bản" <?php echo $disableForm;?>/>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 control-label">Yêu cầu học tập:</label>
            <div class=" col-sm-7">
                <textarea <?php echo $disableForm;?> style="height:150px;" class="form-control" name="Course[content]" placeholder="Vui lòng ghi rõ các yêu cầu về nội dung bạn muốn học, giáo viên, cách học. 
VD: Luyện tập chuyên sâu phần Hình học không gian và khảo sát hàm số, giáo viên Nam" ><?php echo $course['content']; ?></textarea>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 control-label">&nbsp;</label>
            <div class="value col-sm-7 mT15">
                <button <?php echo $disableForm; ?> type="button" id="btnNextInStep1" name="nextStep" class="btn <?php echo $nextStepBtnClass;?> next-step w100">Tiếp tục</button>
            </div>
        </div>
    </form>