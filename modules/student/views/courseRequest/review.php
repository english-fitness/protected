<?php
/**
 * @var $this  CourseRequestController
 */
?>
<script type="text/javascript">
    function backStep(){
        window.location = '<?php echo Yii::app()->baseUrl; ?>/student/courseRequest/schedule';
    }
</script>
<!--#page-title-->
<div class="page-title">
    <label class="tabPage">
        <span class="aCourseTitle"><?php echo $titlePage; ?></span>
    </label>
</div>

<?php
    /* render step*/
    $this->renderPartial('step',array("titlePage"=>$titlePage));
    $course = $registration->getSession("course");
    $session = $registration->getSession('session');//Get session
    $package = CoursePackage::model()->findByPk($course['numberOfSession']);

    $packageOptions = $package->getOption(CoursePackage::TYPE_OFFICIAL,$course['total_of_student']);
    $sales = isset($option->sales)?$option->sales:0;
?>

<form method="post" action="<?php echo $this->createUrl('review'); ?>" style="line-height:25px">


        <div class="row">
            <div class=" col-sm-3">Lớp/Môn học:</div>
            <div class=" col-sm-8">
                <?php echo Subject::model()->displayClassSubject($course['subject_id']);?>
            </div>
        </div>


        <div class="row">
            <div class=" col-sm-3">Chủ đề đăng ký:</div>
            <div class=" col-sm-8"><?php echo $course['title']; ?></div>
        </div>


        <div class="row">
            <div class=" col-sm-3">Kiểu lớp:</div>
            <div class=" col-sm-8">
                <?php
                    echo CoursePackageOptions::model()->getClassNumbers($course['total_of_student']);
                ?>
            </div>
        </div>


        <div class="row">
            <div class=" col-sm-3">Tổng số buổi/khóa:</div>
            <div class=" col-sm-8"><?php echo $package->title; ?></div>
        </div>


        <div class="row">
            <div class=" col-sm-3"><label>Học phí khóa học:</div>
            <div class=" col-sm-8"><?php echo Yii::app()->format->formatNumber($sales); ?> VND</div>
        </div>


        <div class="row">
            <div class=" col-sm-3">Ngày bắt đầu dự kiến:</div>
            <div class=" col-sm-8">
                <?php $startDate = isset($session['startDate'])? $session['startDate']: date('Y-m-d');
                    echo Common::formatDate($startDate);
                ?>
            </div>
        </div>


        <div class="form-element-container row">
            <div class="col col-lg-3 pR5"><label>Số buổi học/tuần:</label></div>
            <div class="col col-lg-9">
            <?php
                $registration = new ClsRegistration();
                $daysOfWeek = $registration->daysOfWeek();
            ?>
            <?php if(isset($session['dayOfWeek'])): foreach($session['dayOfWeek'] as $key=>$day): ?>
                <div class="form-element-container row">
                    <div class="col col-lg-3 pL5"><label><?php echo $daysOfWeek[$day]; ?></label></div>
                    <div class="col col-lg-9"><?php echo $session['startHour'][$key];?></div>
                </div>
            <?php endforeach; endif; ?>

            </div>
        </div>


        <div class="row">
            <div class=" col-sm-3">Yêu cầu học tập:</div>
            <div class=" col-sm-8"><?php echo $course['content']; ?></div>
        </div>

        <div class="row ">
            <div class=" col-sm-3">&nbsp;</div>
            <div class=" col-sm-8">
                <button  class="btn btn-default prev-step mR5" name="prevStep " type="button" onclick="backStep();">Quay lại</button>
                <button type="submit" style ="padding:5px 7px; font-size:12px;" class="btn btn-primary" name="nextStep">Hoàn thành</button>
            </div>
        </div>
</form>

