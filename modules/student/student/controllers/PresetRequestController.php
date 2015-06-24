<?php
class PresetRequestController extends Controller{

    //Select class & subject
    public function actionIndex()
    {
        $this->subPageTitle = 'Chọn kiểu khóa học đăng ký';
        $this->render("index", array());
    }
    
	//Get list of preset course in registering
    public function actionList()
    {
        $this->subPageTitle = 'Danh sách khóa học đang tuyển sinh';
        $presetCourses = PresetCourse::model()->getRegisteringCourses();
		$this->render('list',array(
			'presetCourses'=>$presetCourses,
		));
    }
    
	//View preset request course and register
    public function actionView($id)
    {
		$this->subPageTitle = 'Thông tin khóa học đang tuyển sinh';
        $presetCourse = PresetCourse::model()->findByPk($id);
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
    
	//Register preset request course
    public function actionRegister($id)
    {
		$this->subPageTitle = 'Đăng ký khóa học có sẵn';
        $presetCourse = PresetCourse::model()->findByPk($id);
        //Allow student registering preset closed, activated course if has link
        if(!(isset($presetCourse->id) && $presetCourse->status>=PresetCourse::STATUS_REGISTERING)){
        	$this->redirect(array("list"));
        }
        $registeredPreCourse = $presetCourse->checkRegisteredByStudent(Yii::app()->user->id);
        $registration = new ClsRegistration();//Init registration
        if($registeredPreCourse===false){
        	$preCourse = $registration->createPreCourseFromPresetCourse($presetCourse);
        	if(isset($preCourse->id)){
	        	$user = Yii::app()->user->getData();
		        if($user->status<User::STATUS_REGISTERED_COURSE){
		        	$user->status = User::STATUS_REGISTERED_COURSE;
		        	$user->save();//Update status
		        }
        		$this->redirect(array("/student/presetRequest/finish/id/".$preCourse->id));
        	}
        }
        $this->redirect(array("/student/presetRequest/view/id/".$id));
    }
    
	//Finish preset request course
    public function actionFinish($id)
    {
		$this->subPageTitle = 'Hoàn thành đăng ký khóa học';
        $preCourse = PreregisterCourse::model()->findByAttributes(array('id'=>$id, 'student_id'=>Yii::app()->user->id));
        if(!isset($preCourse->id)) $this->redirect(array("list"));
        Settings::shareFacebook(Settings::SHARE_PRESET_REQUEST,$preCourse);
        $this->render("finish", array("preCourse"=>$preCourse));
    }
    
}

?>