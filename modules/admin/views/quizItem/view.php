<?php
/* @var $this QuizItemController */
/* @var $model QuizItem */

$this->breadcrumbs=array(
	'Quiz Items'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List QuizItem', 'url'=>array('index')),
	array('label'=>'Create QuizItem', 'url'=>array('create')),
	array('label'=>'Update QuizItem', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete QuizItem', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage QuizItem', 'url'=>array('admin')),
);
?>

<h2>Chi tiết câu hỏi</h2>
<p><a href="/admin/quizItem/update/id/<?php echo $model->id;?>"><span class="btn-edit"></span>&nbsp;Sửa nội dung, thông tin câu hỏi</a></p>
<?php 
	$itemAnswers = (array)json_decode($model->answers);
	$displayItemAnswers = ""; $displaySubItemAnswers="";//Display item answers
	foreach($itemAnswers as $key=>$value){
		$correctClass = ($key==$model->correct_answer)? 'class="itemCorrectanswer"': "";
		$displayItemAnswers .= '<span '.$correctClass.'><b>'.$key.'. </b>'.$value.'</span><br/>';
	}
	$subItems = $model->getSubItems();
	if(count($subItems)>0){
		foreach($subItems as $subItem){
			$subItemAnswers = (array)json_decode($subItem->answers);
			$displaySubItemAnswers .= '<b>Nội dung: </b>'.$subItem->content.'<br/>';
			foreach($subItemAnswers as $subKey=>$subValue){
				$correctClass = ($subKey==$subItem->correct_answer)? 'class="itemCorrectanswer"': "";
				$displaySubItemAnswers .= '<span '.$correctClass.'><b>'.$subKey.'. </b>'.$subValue.'</span><br/>';
			}
			$displaySubItemAnswers .= "<hr/>";
		}
	}
?>
<?php if(!$model->isNewRecord && isset($assignedQuizExams)):?>
<table class="detail-view" id="yw0"><tbody>
	<tr class="odd"><th>Đã ghép vào đề thi</th>
		<td><?php $this->renderPartial("/quizExam/widget/assignedExam", array("assignedExams"=>$assignedQuizExams)); ?></td>
	</tr>
</tbody>
</table>
<?php endif;?>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
		   'name'=>'subject_id',
		   'value'=>Subject::model()->displayClassSubject($model->subject_id),
		),		
		array(
		   'name'=>'content',
		   'value'=>$model->content,
		   'type'=>'raw',
		),
		array(
		   'name'=>'answers',
		   'value'=>$displayItemAnswers,
		   'type'=>'raw',
		),
		array(
		   'name'=>'Các câu hỏi con(nếu có)',
		   'value'=>$displaySubItemAnswers,
		   'type'=>'raw',
		),
		array(
		   'name'=>'level',
		   'value'=>$model->levelOptions($model->level),
		   'type'=>'raw',
		),
		array(
		   'name'=>'status',
		   'value'=>$model->statusOptions($model->status),
		   'type'=>'raw',
		),
		'tags',
		array(
		   'name'=>'suggestion',
		   'value'=>$model->suggestion,
		   'type'=>'raw',
		),
		array(
		   'name'=>'explaination',
		   'value'=>$model->explaination,
		   'type'=>'raw',
		),
		array(
		   'name'=>'created_user_id',
		   'value'=>($model->created_user_id)? User::model()->displayUserById($model->created_user_id):"",
		),
		array(
		   'name'=>'created_date',
		   'value'=>($model->created_date)? date('d/m/Y H:i', strtotime($model->created_date)):"",
		),
		array(
		   'name'=>'modified_user_id',
		   'value'=>($model->modified_user_id)? User::model()->displayUserById($model->modified_user_id):"",
		),		
		array(
		   'name'=>'modified_date',
		   'value'=>($model->modified_date)? date('d/m/Y H:i', strtotime($model->modified_date)):"",
		),
		'deleted_flag',
	),
)); ?>
