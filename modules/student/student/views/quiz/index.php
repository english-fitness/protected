<div class="page-title">
    <label class="tabPage">Ôn tập, kiểm tra kiến thức</label>
</div>
<?php $this->renderPartial('/quiz/quizTab'); ?>
<div class="page_body page_index_body">
	<div class="pA10">
	<h5 style="font-weight: bold">Đề thi đã, đang làm</h5>
	<table class="table table-bordered table-striped data-grid">
		<thead>
		<tr>
			<th class="w200">Lớp/môn học</th>
			<th>Đề trắc nghiệm</th>
			<th class="w150">Thời lượng đề thi</th>
			<th class="w150">Bắt đầu lúc</th>
			<th class="w120">Nộp bài lúc</th>
			<th class="w150">Số điểm</th>
		</tr>
		</thead>
		<tbody>
		<?php if(isset($examHistory) && count($examHistory['models'])>0): ?>
			<?php foreach($examHistory['models'] as $history):
					if(isset($history->exam->id)):
						$quizExam = $history->exam;
			?>
				<tr class="even">
					<td><?php echo Subject::model()->displayClassSubject($quizExam->subject_id);?></td>
					<td><a href="/student/quizExam/view/id/<?php echo $quizExam->id?>"><?php echo $quizExam->name; ?></a></td>
					<td><?php echo $quizExam->duration; ?> phút</td>
					<td><?php echo date('H:i, d/m/Y', strtotime($history->actual_start));?></td>
					<td><?php echo ($history->actual_end)? date('H:i, d/m/Y', strtotime($history->actual_end)):'Chưa nộp bài';?></td>
					<td><?php echo $history->displayScore(true);?> điểm <?php echo ($history->actual_end)? "(".$history->displayScore(false)." câu)": "";?></td>
				</tr>
			<?php endif; endforeach; ?>
		<?php else:?>
			<tr class="even"><td colspan="6">Chưa có đề thi nào bạn đã làm hoặc đang làm bài!</td></tr>
		<?php endif;?>
		</tbody>
	</table>
	<?php $this->widget('CustomLinkPager', array('pages' => $examHistory['pages'])); ?>
</div>
</div>