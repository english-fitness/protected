<script type="text/javascript">
	function changeFilterClass(){
		var quizIndexLink = "<?php echo $quizIndexLink;?>";
		var classId = $('#QuizClassFilter').val();
		var subjectId = $('#QuizSubjectFilter').val();
		if(subjectId!=""){
			window.location = daykemBaseUrl + quizIndexLink + '?subjectId='+subjectId;
		}else if(classId!=""){
			window.location = daykemBaseUrl + quizIndexLink + '?classId='+classId;
		}
	};	
</script>
<div class="col col-lg-12 mT10 pL20 quizSubjectFilter">
	<?php $availableClasses = QuizTopic::model()->getAvailableFilterClasses();?>
	<?php
		$currSubjectId = isset($_SESSION['quizCurrentSubject'])? $_SESSION['quizCurrentSubject']: null;
		$currSubject = Subject::model()->findByPk($currSubjectId);
		$selectedClassId = isset($currSubject->class_id)? $currSubject->class_id: "";
		if(isset($_GET['classId']) && is_numeric($_GET['classId'])){
			$selectedClassId = $_GET['classId'];
		}
	?>
	<div class="fL w250">
		<span class="fL mT15"><b>Chọn khối lớp: </b></span>
		<?php echo CHtml::dropDownList('QuizClassFilter', $selectedClassId, $availableClasses, array('id'=>'QuizClassFilter',"class"=>"w150 fL fs13", "onchange"=>"changeFilterClass()"));?>
	</div>
	<div class="fL w300">
		<span class="fL mT15"><b>Chọn môn học:</b></span>
		<?php $classSubjects = array(""=>"Chọn môn..."); ?>
		<?php
			$selectedSubjectId = "";//Selected subject id
			if($selectedClassId!=""){
				$classSubjects = array(""=>"Chọn môn...") + CHtml::listData(Subject::model()->getAvailableSubjectsToQuiz($selectedClassId), 'id', 'name');
				$selectedSubjectId = isset($currSubject->id)? $currSubject->id: "";
			}
			echo CHtml::dropDownList('QuizSubjectFilter', $selectedSubjectId, $classSubjects, array('id'=>'QuizSubjectFilter', "class"=>"w200 fL fs13", "onchange"=>"changeFilterClass()"));
		?>
	</div>
</div>