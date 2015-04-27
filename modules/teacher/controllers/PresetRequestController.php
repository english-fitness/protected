<?php
class PresetRequestController extends Controller{

    //Select class & subject
    public function actionIndex()
    {
       	$this->subPageTitle = 'Danh sách khóa học đã đăng ký';
       	$teacherId = Yii::app()->user->id;
       	$attributes = array('teacher_id'=>$teacherId, 'deleted_flag'=>0, 'created_user_id'=>$teacherId);
       	$params = array('order'=>'status ASC, start_date ASC');//Order params
        $presetCourses = PresetCourse::model()->findAllByAttributes($attributes, $params);
		$this->render('index',array(
			'presetCourses'=>$presetCourses,
		));
    }
    
	//View preset request course and register
    public function actionView($id)
    {
		$this->subPageTitle = 'Thông tin khóa học đã đăng ký';
		$teacherId = Yii::app()->user->id;
		$attributes = array('teacher_id'=>$teacherId, 'deleted_flag'=>0, 'id'=>$id);
        $presetCourse = PresetCourse::model()->findByAttributes($attributes);
        if(!isset($presetCourse->id)) $this->redirect(array("list"));
        $this->render("view", array(
        	"presetCourse" => $presetCourse,
        ));
    }
    
	//View teacher of preset request course
    public function actionViewTeacher($id)
    {
		$this->subPageTitle = 'Thông tin giáo viên';
        $presetCourse = PresetCourse::model()->findByPk($id);
        if(!isset($presetCourse->id)) $this->redirect(array("list"));
        $teacher = User::model()->findByPk($presetCourse->teacher_id);
        $teacherProfile = Teacher::model()->findByPk($presetCourse->teacher_id);
        $this->render("viewTeacher", array(
        	"presetCourse" => $presetCourse,
        	'teacher' => $teacher,
        	'teacherProfile' => $teacherProfile,
        ));
    }
    
	//Create preset course for teacher
    public function actionCreate()
    {
		$this->subPageTitle = 'Đăng ký thông tin khóa học';
		$registration = new ClsRegistration();//Registration class
		$model = new PresetCourse();
		$teacherId = Yii::app()->user->id;//Teacher id
		$teacher = Teacher::model()->findByPk($teacherId);
        $abilitySubjectIds = $teacher->abilitySubjects();//Subjects ability of teacher
        $abilitySubjects = Subject::model()->generateSubjectFilters('ASC', $abilitySubjectIds);
		if(isset($_POST['PresetCourse'])){
			$model->attributes = $_POST['PresetCourse'];
			$model->min_student = 1;//Min student in session
			$model->teacher_id = $teacherId;//Teacher id
			$model->created_user_id = $teacherId;//Teacher is created user
			$model->status = PresetCourse::STATUS_PENDING;//Pending status
			if(isset($_POST['Session']['dayOfWeek']) && isset($_POST['Session']['startHour'])){
				$model->session_per_week = $registration->convertSessionSchedules($_POST['Session']['dayOfWeek'], $_POST['Session']['startHour']);
			}
			if($model->save()){
				$this->redirect(array('/teacher/presetRequest/index'));
			}
		}
		$this->render('create',array(
			'model'=>$model,
			'abilitySubjects'=>$abilitySubjects,
		));
    }
    
	/**
	 * Allow teacher can delele their pending preset course
	 */
    public function actionDelete($id)
    {
    	$this->subPageTitle = 'Giáo viên hủy đăng ký khóa học';
    	$teacherId = Yii::app()->user->id;
        $attributes = array('teacher_id'=>$teacherId, 'deleted_flag'=>0, 'created_user_id'=>$teacherId, 'id'=>$id);
        $presetCourse = PresetCourse::model()->findByAttributes($attributes);
        if(isset($presetCourse->id) && $presetCourse->status==PresetCourse::STATUS_PENDING){
        	$presetCourse->deleted_flag = 1;//Deleted flag = 1
        	$presetCourse->save();
        	$this->redirect(array('/teacher/presetRequest/index'));
        }else{
        	$error = array('code'=>403, 'message'=>'Bạn không có quyền để thực hiện hành động này!');
            $this->render('//site/error', $error); die();
        }
    }
    
    /**
     * Ajax load generate schedule suggestion
     */
    public function actionAjaxCreateSchedules()
    {
        $nPerWeek = $_REQUEST['nPerWeek'];//Session per week
        $planDuration = $_REQUEST['planDuration'];//Plan duration option
        $registration = new ClsRegistration();
        $suggestedDays = $registration->suggestSessionDayInWeek($nPerWeek);
        echo $this->renderPartial('schedule', array(
        	'suggestedDays'=>$suggestedDays,
        	'planDuration'=>$planDuration
        ));
    }

}

?>