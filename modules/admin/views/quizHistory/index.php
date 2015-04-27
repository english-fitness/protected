<?php
/* @var $this QuizExamController */
/* @var $model QuizExam */

$this->breadcrumbs=array(
	'Quiz Exams'=>array('index'),
	'Manage',
);
?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-12">
        <h3 class="page-title mT10">Lịch sử làm đề trắc nghiệm</h3>
    </div>
</div>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
		   'header'=>'Mã đề thi',
		   'value'=>'$data->exam->id',
		   'filter'=> false,
		   'htmlOptions'=>array('style'=>'text-align:center;width:80px;'),	
		),
		array(
		   'header'=>'Môn học',
		   'value'=>'Subject::model()->displayClassSubject($data->exam->subject_id)',
		   'htmlOptions'=>array('style'=>'width:250px;'),
		   'filter'=> false,
		),
		array(
		   'header'=>'Tên đề trắc nghiệm',
		   'value'=>'CHtml::link($data->exam->name, "/admin/quizExam/preview/id/".$data->exam->id, array())',
		   'type'=>'raw','filter'=> false,
		),
		array(
		   'header'=>'Số câu trả lời đúng',
		   'value'=>'$data->displayScore()',
		   'htmlOptions'=>array('style'=>'width:100px; text-align:center;'),
		   'type'=>'raw', 'filter'=> false,
		),
		array(
		   'header'=>'Học sinh',
		   'value'=>'$data->getExamUser("/admin/user/view/id")',
		   'type' => 'raw', 'filter'=> false,
		   'htmlOptions'=>array('style'=>'width:200px;'),
		),
		array(
		   'name' => 'actual_start',
		   'value'=>'($data->actual_start)? date("d/m/Y, H:i", strtotime($data->actual_start)): ""',
		   'filter'=> false,
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
		array(
		   'name' => 'actual_end',
		   'value'=>'($data->actual_end)? date("d/m/Y, H:i", strtotime($data->actual_end)): ""',
		   'filter'=> false,
		   'htmlOptions'=>array('style'=>'width:120px;'),
		),
	),
)); ?>
