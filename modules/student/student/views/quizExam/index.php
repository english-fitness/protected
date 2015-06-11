<div class="page-title">
    <label class="tabPage">Luyện tập trắc nghiệm</label>
</div>
<?php $this->renderPartial('/quiz/quizTab'); ?>
<?php
	$quizIndexLink = "/student/quizExam/index";
	$this->renderPartial('student.views.quiz.quizFilter', array('quizIndexLink'=>$quizIndexLink));
?>
<div class="clearfix h10">&nbsp;</div>
<div class="details-class clearfix">
	<table class="table table-bordered table-striped data-grid">
        <thead>
        <tr>
        	<th class="w200">Lớp/môn học</th>
        	<th>Đề trắc nghiệm</th>
            <th class="w120">Thời lượng đề thi</th>
            <th class="w100">Số câu hỏi</th>
            <th class="w100">Làm bài</th>
        </tr>
        </thead>
        <tbody>
        	<?php if($quizExams): foreach($quizExams as $exam): ?>
        	<tr>
        		<td>
        			<?php echo Subject::model()->displayClassSubject($exam->subject_id);?>
        		</td>
           		<td>
           			<a href="/student/quizExam/view/id/<?php echo $exam->id;?>"><?php echo $exam->name; ?></a>
				</td>
                <td class="text-center"><span><?php echo $exam->duration; ?></span> phút</td>
                <td><b><?php echo $exam->countAssignedItem(); ?></b> Câu hỏi</td>
                <td>
                	<a href="/student/quizExam/view/id/<?php echo $exam->id;?>">
                		<button class="pA5 mR10">Làm bài</button>
                	</a>
                </td>
            </tr>
        	<?php endforeach; else:?>
        	<tr><td colspan="5">Chưa có đề thi trắc nghiệm nào được kích hoạt!</td></tr>
        	<?php endif;?>
        </tbody>
    </table>
    <div class="fR mR15"><?php $this->widget('CustomLinkPager', array('pages' => $pages)); ?></div>
</div>