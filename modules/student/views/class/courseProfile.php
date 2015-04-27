<?php $this->renderPartial('courseTab',array('course'=>$course)); ?>
<div class="details-class">
    <div class="session">
        <div class="course-content" style="border: none">
            <p><b>Lớp/môn học: </b><?php echo $course->subject->class->name.' - '.$course->subject->name;?></p>
            <p><b>Nội dung khóa học: </b><span class="aCourseContent"><?php echo $course->content; ?></span></p>
            <p><b>Học sinh: </b>
                <?php $courseStudentValues = array_values($course->getAssignedStudentsArrs());
                echo implode(', ', $courseStudentValues);
                ?>
            </p>
            <p><b>Trạng thái khóa học: </b>
                <span><?php	echo $course->getStatus(); ?></span>
            </p>
        </div>
    </div>
</div>