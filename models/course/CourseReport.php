<?php

class CourseReport extends CActiveRecord
{
    const REPORT_UPLOAD_DIR = "media/uploads/documents";

    const REPORT_TYPE_ENTRY = 0;
    const REPORT_TYPE_PROGRESS = 1;
    
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_course_report';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules =  array(
			array('course_id, student_id, reporting_teacher, report_date, report_type', 'required'),
			array('report_file', 'requireReportFile'),
			array('course_id, student_id, reporting_teacher', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, course_id, user_id, comment, created_date, modified_date', 'safe', 'on'=>'search'),
            //set attributes on insert
            array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
            array('created_user', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert'),
            array('last_modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
            array('last_modified_user', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert'),
		);
        //Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('last_modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
            $modelRules[] = array('last_modified_user', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'update');
		}
        return $modelRules;
	}

	public function requireReportFile(){
		if ($this->isNewRecord && empty($this->report_file)){
			$this->addError('report_file', 'Hãy chọn file báo cáo');
		}
	}

	public function reportTypeOptions($type=null){
		$typeOptions = array(
			self::REPORT_TYPE_ENTRY=>"Đánh giá đầu vào",
			self::REPORT_TYPE_PROGRESS=>"Đánh giá khóa học",
		);

		if ($type != null){
			return $typeOptions[$type];
		}

		return $typeOptions;
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'course' => array(self::BELONGS_TO, 'Course', 'course_id'),
            'student'=>array(self::BELONGS_TO, 'User', 'student_id'),
			'reportingTeacher' => array(self::HAS_ONE, 'User', array('id'=>'reporting_teacher')),
            'lastModifiedUser' => array(self::HAS_ONE, 'User', array('id'=>'last_modified_user')),
            'createdUser' => array(self::HAS_ONE, 'User', array('id'=>'created_user')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'course_id' => 'Khóa học',
            'student_id'=>'Học viên',
			'reporting_teacher' => 'Giáo viên báo cáo',
			'report_date' => 'Ngày báo cáo',
			'report_type'=>'Loại đánh giá',
            'student_comment' => 'Nhận xét của học sinh',
            'report_file' => 'File báo cáo',
            'last_modified_user' => 'Người sửa cuối',
            'last_modified_date' => 'Ngày sửa cuối',
            'created_user' => 'Người tạo',
            'created_date' => 'Ngày tạo',
		);
	}

	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('course_id',$this->course_id);
        $criteria->compare('report_date',$this->report_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
    
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CourseComment the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
    public function handleReportFileUpload($uploadedFile){
    	if (empty($this->course_id) || empty($this->student_id) || empty($this->report_date)){
    		return false;
    	}

        if(isset($uploadedFile['name']) &&  !$uploadedFile["error"])
        {
        	if (empty($this->course_id) || empty($this->student_id) || empty($this->report_date)){
        		return false;
        	}
            $uploadedFileName = $uploadedFile['name'];
            
            $allowableTypes = array('.doc','.docx','.pdf','.odt');
            $extension = ".".strtolower(pathinfo($uploadedFileName, PATHINFO_EXTENSION));
            
            if(in_array($extension,$allowableTypes)){
            	$studentName = str_replace(' ', '-', $this->student->fullname());
            	$fileName = "ProgressReport_".$studentName."_".date("d-m-Y", strtotime($this->report_date));

                $saveFileName = $fileName.$extension;
                $uploadDir = self::REPORT_UPLOAD_DIR."/".$this->course_id;
                if (!file_exists($uploadDir)){
                	mkdir(self::REPORT_UPLOAD_DIR."/".$this->course_id, 0755);
                } else {
                	if ($this->isNewRecord || strpos(basename($this->report_file), $fileName) === false){
                		if (file_exists($uploadDir."/".$saveFileName)){
                			$fileCount = 2;
                			while(file_exists($uploadDir.'/'.$fileName.'_'.$fileCount.$extension)){
                				$fileCount++;
                			}
                			$saveFileName = $fileName.'_'.$fileCount.$extension;
                		}
                	} else {
                		$saveFileName = $this->report_file;
                	}
                }
                $fileFullPath = $uploadDir."/".$saveFileName;
                if (move_uploaded_file($uploadedFile['tmp_name'], $fileFullPath)){
                	if ($this->report_file != $saveFileName && !$this->isNewRecord){
                		$oldReport = $uploadDir.'/'.$this->report_file;
                		if (file_exists($oldReport)){
                			unlink($oldReport);
                		}
                	}
                    $this->report_file = $saveFileName;
                    return true;
                }
            }
        }
        
        return false;
    }
    
    public function getGoogleDocsViewerUrl($params=array()){
        //note in case google docs viewer discontinue
        //ms also have online viewer at http://view.officeapps.live.com/op/view.aspx?src=<encoded url>
        $gdocsUrl = "https://docs.google.com/viewer";
        if (!empty($params)){
            $getParams = "&" . http_build_query($params);
        } else {
            $getParams = "";
        }
        return $gdocsUrl."?url=".$this->getReportUrl().$getParams;
    }
    
    public function getReportUrl(){
        $https = isset($_SERVER['HTTPS']) && (strcasecmp('off', $_SERVER['HTTPS']) !== 0);
        $protocol = $https ? "https://" : "http://";
        return $protocol . $_SERVER['HTTP_HOST']. '/' . self::REPORT_UPLOAD_DIR . '/' . $this->course_id . '/' . $this->report_file;
    }
}
