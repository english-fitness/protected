<?php
/* @var $this SessionController */
/* @var $model Session */
?>
<?php if(isset($error) && $error == 'no_session_available'):?>
<p style="color:red">Khóa học này không còn buổi học nào</p>
<a href="/admin/session?course_id=<?php echo $course_id?>">Quay lại</a>
<?php else:?>
<?php $this->renderPartial('_form', array('model'=>$model, 'modelCourse'=>$modelCourse)); ?>
<?php endif;?>
