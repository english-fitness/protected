<?php
/* @var $this QuizExamController */
/* @var $model QuizExam */

$this->breadcrumbs=array(
	'Quiz Exams'=>array('index'),
	$model->name,
);
?>
<?php if($model->isActivatedWritingExam()):?>
<script type="text/javascript">
	$(function() {
        $( "#sortable" ).sortable();
        $( "#sortable" ).disableSelection();
    });
	//Update new order index of item in exam
	function updateNewOrder(){
		var checkConfirm = confirm("Bạn có chắc chắn muốn cập nhật lại thứ tự câu hỏi trong đề thi này?");
		if(checkConfirm){
			$("#previewForm").submit();
		}
	}
	//Unassign item in exam
	function unAssignItem(itemId){
		var checkConfirm = confirm("Bạn có chắc chắn loại câu hỏi này ra khỏi đề thi?");
		if(checkConfirm){
			window.location.href = "/admin/quizExam/preview/id/<?php echo $model->id;?>?unasign_item_id="+itemId;
		}
	}
</script>
<?php endif;?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/quiz.css" />
<form id="previewForm" action="/admin/quizExam/preview/id/<?php echo $model->id?>" method="post">
	<span class="fL"><a target="_blank" href="/student/quiz/viewExam/id/<?php echo $model->id;?>"><b>[Xem trước đề thi như một học sinh]</b></a></span>
	<?php if($model->isActivatedWritingExam()):?>
		<span class="fR"><a href="javascript:updateNewOrder();"><b>[Cập nhật lại thứ tự sắp xếp mới]</b></a></span>
		<input name="checkResetIndex" type="hidden" value="0" />
	<?php endif;?>
	<h3 class="text-center">
		<a href="/admin/quizExam/update/id/<?php echo $model->id;?>"><?php echo $model->name;?></a>
	</h3>
	<div class="listTopic" id="sortable">
		<?php
		if(count($assignedItems)>0):
			foreach($assignedItems as $key=>$item):
		?>
		<div class="question mL25">
			<div class="question_id">
				<span><?php echo ($key+1);?></span><br/>
				<a class="btn-edit" target="_blank" title="" href="/admin/quizItem/update/id/<?php echo $item->id;?>"></a><br/>
				<a class="btn-view" target="_blank" title="" href="/admin/quizItem/view/id/<?php echo $item->id;?>"></a>
			</div>
			<input type="hidden" name="itemOrderIndex[]" value="<?php echo $item->id?>" class="txtOrderIndex">
			<div class="question-content"><?php echo $item->content; ?>
				<div class="answer">
				    <div class="hind"><label>Chọn đáp án đúng</label></div>
				    <?php
				    	$answer = json_decode($item->answers);
				    	if(is_object($answer)): foreach($answer as $key=>$value):
				        ?>
				        <div class="row">
				            <input type="radio" name="answer[<?php echo $item->id; ?>]" value="<?php echo $key; ?>">
				            <span class="answerLabel"><?php echo $key.'. '.$value; ?></span>
				        </div>
				    <?php endforeach; endif; ?>
				</div>
			</div>
			<?php if($model->isActivatedWritingExam() && $item->parent_id==0):?>
			<div class="fR"><a href="javascript: unAssignItem(<?php echo $item->id;?>)" class="clrRed">Gỡ câu hỏi ra khỏi đề thi?</a></div>
			<div class="clearfix">&nbsp;</div>
			<?php endif;?>
		</div>
		<?php endforeach; else: ?>
				<div class="pA15 error">Chưa có câu hỏi nào được xác nhận trong đề thi này!</div>
		<?php endif; ?>
	</div>
</form>