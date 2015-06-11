<?php
$baseurl = Yii::app()->baseurl."/student";
$menuQuizStudent  =  array(
	array("label"=>Yii::t('nav','Luyện tập trắc nghiệm'),"url"=>$baseurl."/quizExam/index"),
	array("label"=>Yii::t('nav','Ôn tập lý thuyết'), "url"=>$baseurl."/quizTopic/index",'controllers'=>array('quizTopic')),
	array("label"=>Yii::t('nav','Đề thi đã, đang làm'),"url"=>$baseurl."/quiz/index"),        
);
echo Html::createNavMenu($menuQuizStudent,array('class'=>'nav nav-tabs'));
?>