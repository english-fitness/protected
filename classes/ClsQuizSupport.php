<?php
class ClsQuizSupport
{
	/**
	 * Get topic of an item
	 */
	public function getItemTopicId($itemIndex, $topicFile, $baseUrl)
	{
		//File text ghep cac cau hoi vao de thi
		$basePathDir = Yii::app()->params['mediaBasePath'];//Basepath dir
		if(file_exists($basePathDir.$topicFile)){
	        $topicInfo = file_get_contents($baseUrl.'/'.$topicFile);
	        $topicArr = explode("\n", $topicInfo);
	        $itemTopic = null;
	        foreach($topicArr as $topicStr){
	        	$topicStr = str_replace(" ", "", $topicStr);
	        	$topicStr = str_replace(",", '","', $topicStr);
	        	if(strpos($topicStr, '"'.$itemIndex.'"')!==false){
	        		$parseTopic = explode("=", $topicStr);
	        		$itemTopic = trim($parseTopic[0]);
	        		break;
	        	}
	        }
	        if($itemTopic){
	        	$itemTopicCmp = "[".$itemTopic."]";
	        	$criteria=new CDbCriteria;
	        	$criteria->compare('name',$itemTopicCmp,true);
	        	$topic = QuizTopic::model()->find($criteria);
	        	if(isset($topic->id)) return $topic->id;
	        }
		}
		return null;
	}
	
	/**
	 * Generate quizTopic
	 */
	public function generateQuizTopics($subjectId, $folder='vatly12', $baseUrl)
	{
		if($subjectId!=null){
			$topicDir = 'media/quiz/'.$folder.'/chuyende';
			$basePathDir = Yii::app()->params['mediaBasePath'];//Basepath dir
			for($i=1; $i<=20; $i++){//Chuyen de thuoc lop-mon
				$parentTopicFile = $topicDir.'/chuyende'.$i.'/chuyende'.$i.'.txt';
				if(file_exists($basePathDir.$topicDir) && file_exists($basePathDir.$parentTopicFile)){
	        		$parentTopic = new QuizTopic();
	        		$parentTopicName = file_get_contents($baseUrl.'/'.$parentTopicFile);
	        		$parentTopicAttrs = array(
	        			'subject_id' => $subjectId,
	        			'name' => $parentTopicName,
	        			'parent_id' => 0,
	        			'parent_path' => '0',
	        			'content' => '',
	        			'status' => QuizTopic::STATUS_APPROVED,
	        		);
	        		$parentTopic->attributes = $parentTopicAttrs;
	        		if($parentTopic->save()){
	        			echo "$folder : Chu de level 1, ma so ".$parentTopic->id."\n";
	        			for($k=1; $k<=20; $k++){//Chu de
	        				$subTopic1Dir = $topicDir.'/chuyende'.$i.'/chude'.$k;
	        				$subTopic1File = $subTopic1Dir.'/chude'.$k.'.txt';
	        				if(file_exists($basePathDir.$subTopic1File)){
	        					$subTopic1Info = file_get_contents($baseUrl.'/'.$subTopic1File);
	        					$subTopic1Arr = explode("\n", $subTopic1Info);
	        					$subTopic1 = null;//Set sub topic 1 to
	        					for($t=0; $t<=20; $t++){//Dang chu de, thuoc chu de con
	        						$content = "";//Set content
	        						if(file_exists($basePathDir.$subTopic1Dir.'/dang'.$t)){
	        							for($img=1; $img<=30; $img++){//Anh nho trong cac dang ly thuyet
	        								$imageFile = null;//Image file
	        								if(file_exists($basePathDir.$subTopic1Dir.'/dang'.$t.'/'.$img.'.jpg')){
	        									$imageFile = $subTopic1Dir.'/dang'.$t.'/'.$img.'.jpg';
	        								}elseif(file_exists($basePathDir.$subTopic1Dir.'/dang'.$t.'/'.$img.'.png')){
	        									$imageFile = $subTopic1Dir.'/dang'.$t.'/'.$img.'.png';
	        								}
	        								if($imageFile){
	        									$content .= '<img src="'.$baseUrl.'/'.$imageFile.'"/>';
	        								}
	        							}
	        							$subTopicObj = new QuizTopic();
	        							$subTopicObj->attributes = $parentTopicAttrs;
	        							$subTopicObj->name = isset($subTopic1Arr[$t])? strip_tags($subTopic1Arr[$t]): null;
	        							if($t==0){//Neu la chu de con
	        								$subTopicObj->parent_id = $parentTopic->id;//Chu de con cap 1
	        								$subTopicObj->parent_path = $parentTopic->parent_path.'/'.$parentTopic->id;
	        							}elseif(isset($subTopic1->id)){
	        								$subTopicObj->parent_id = $subTopic1->id;//Chu de con cap 2
	        								$subTopicObj->parent_path = $subTopic1->parent_path.'/'.$subTopic1->id;
	        							}
	        							$subTopicObj->content = $content;
	        							$subTopicObj->save();//Luu chu de
	        							if($t==0){
	        								$subTopic1 = $subTopicObj;
	        								echo "$folder : Chu de level 2, ma so ".$subTopicObj->id."\n";
	        							}else{
	        								echo "$folder : Chu de level 3, ma so ".$subTopicObj->id."\n";
	        							}
	        						}
	        					}
	        				}
	        			}
	        		}
	        	}else{
	        		echo "Ko ton tai file chuyen de thu $i \n";
	        	}
			}
		}
	}
	
	/**
	 * Display Course status
	 */
	public function generateExamItems($subjectId, $type=1, $folder='vatly12', $duration=90, $baseUrl, $maxExam=50)
	{
		if($subjectId!=null){
			if($type==QuizExam::TYPE_EXAMINING) $folder .= '/dethi/thidh';
			if($type==QuizExam::TYPE_TRAINING) $folder .= '/dethi/ontap';
			for($i=1; $i<=$maxExam; $i++){
				$examIndex = ($i<10)? '0'.$i: $i;
				$examDir = 'media/quiz/'.$folder.'/'.$examIndex;
				$basePathDir = Yii::app()->params['mediaBasePath'];//Basepath dir
				if(file_exists($basePathDir.$examDir) && file_exists($basePathDir.$examDir.'/dethi.txt'))
	        	{
	        		//File text ten de thi & dap an de thi
	        		$examInfo = file_get_contents($baseUrl.'/'.$examDir.'/dethi.txt');
	        		$examInfoArr = explode("\n", $examInfo);
	        		//Tao de thi
					$exam = new QuizExam();
					$exam->attributes = array(
						'subject_id' => $subjectId,
						'name' => $examInfoArr[0],
						'type' => $type,
						'duration' => $duration,
						'level' => QuizExam::LEVEL_GOOD,
						'status' => QuizExam::STATUS_COMPLETED,
					);
					if($exam->save()){
						echo "---$examDir: De thi so ".$i."---\n";
						for($k=1; $k<=80; $k++){
							$itemIndex = ($k<10)? '0'.$k: $k;
							//Check dethi image
							$content = "";//Init content of item
							if(file_exists($basePathDir.$examDir.'/dethi_img/'.$k.'.jpg')){
								$content = '<p><img src="'.$baseUrl.'/'.$examDir.'/dethi_img/'.$k.'.jpg'.'" /></p>';
							}elseif(file_exists($basePathDir.$examDir.'/dethi_img/'.$k.'.png')){
								$content = '<p><img src="'.$baseUrl.'/'.$examDir.'/dethi_img/'.$k.'.png'.'" /></p>';
							}
							//Check goiy image
							$suggestion = "";//Init explaination of item
							if(file_exists($basePathDir.$examDir.'/goiy_img/'.$k.'.jpg')){
								$suggestion = '<p><img src="'.$baseUrl.'/'.$examDir.'/goiy_img/'.$k.'.jpg'.'" /></p>';
							}elseif(file_exists($basePathDir.$examDir.'/goiy_img/'.$k.'.png')){
								$suggestion = '<p><img src="'.$baseUrl.'/'.$examDir.'/goiy_img/'.$k.'.png'.'" /></p>';
							}
							//Check loigiai image
							$explaination = "";//Init explaination of item
							if(file_exists($basePathDir.$examDir.'/loigiai_img/'.$k.'.jpg')){
								$explaination = '<p><img src="'.$baseUrl.'/'.$examDir.'/loigiai_img/'.$k.'.jpg'.'" /></p>';
							}elseif(file_exists($basePathDir.$examDir.'/loigiai_img/'.$k.'.png')){
								$explaination = '<p><img src="'.$baseUrl.'/'.$examDir.'/loigiai_img/'.$k.'.png'.'" /></p>';
							}
							//Save item to exam
							$correctAnswer = 'A';
							if($content!=""){
								if(isset($examInfoArr[$k])){
									$parseAnswer = explode("=", $examInfoArr[$k]);
									if(isset($parseAnswer[1])) $correctAnswer = $parseAnswer[1];
									$correctAnswer = str_replace("'", "", $correctAnswer);
								}
								$answers = array("A"=>"Đáp án A","B"=>"Đáp án B","C"=>"Đáp án C","D"=>"Đáp án D");
								$item = new QuizItem();
								$item->attributes = array(
									'subject_id' => $subjectId,
									'content' => $content,
									'correct_answer' => trim($correctAnswer),
									'suggestion' => $suggestion,
									'explaination' => $explaination,
									'type' => $type,
									'answers' => json_encode($answers),
									'level' => QuizItem::LEVEL_GOOD,
									'status' => QuizItem::STATUS_APPROVED,
								);
								if($item->save()){
									echo "$examDir: Cau hoi thu ".$k."\n";
									$item->assignItemToExams(array($exam->id), $item->id);
									$topicId = $this->getItemTopicId($itemIndex, $examDir.'/chude.txt', $baseUrl);
									$item->assignItemToTopic($topicId);//Assign item to topic
								}
							}
						}
						echo "---$examDir: Tao thanh cong de thi so ".$i."---\n";
					}
	        	}else{
	        		echo "$examDir: Ko ton tai de thi $i \n";
	        	}
			}
		}
	}

	
}
?>