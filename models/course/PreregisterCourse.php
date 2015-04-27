<?php

/**
 * This is the model class for table "tbl_preregister_course".
 *
 * The followings are the available columns in table 'tbl_preregister_course':
 * @property integer $id
 * @property integer $student_id
 * @property integer $subject_id
 * @property string $title
 * @property string $note
 * @property integer $total_of_student
 * @property string $start_date
 * @property integer $total_of_session
 * @property string $session_per_week
 * @property integer $status
 * @property string $created_date
 */
class PreregisterCourse extends CActiveRecord
{
	const STATUS_PENDING = 0;//Pending status
	const STATUS_APPROVED = 1;//Approved status
	const STATUS_REFUSED = 2;//Refused status
	//Payment type const
	const PAYMENT_TYPE_FREE = 0;//Free course
	const PAYMENT_TYPE_NOT_FREE = 1;//Not free
	//Payment status const
	const PAYMENT_STATUS_PENDING = 0;//Not paid yet
	const PAYMENT_STATUS_PAID = 1;//Paid
	const PAYMENT_STATUS_REFUND = 2;//Refund
	const TOTAL_TRAINING_SESSION = 4;//Total training session

    public static function getTotalTrainingPrice($key)
    {

        $price = CoursePriceOptions::model()->findByAttributes(array('type'=>CoursePriceOptions::TYPE_COURSE,'total_student'=>$key));
        if($price) {
            return $price->hoc_thu;
        } else {
            throw new CHttpException(404,'The requested page does not exist.');
        }
    }

    public static function getTotalTrainingBankPrice($key)
    {
        $price = CoursePriceOptions::model()->findByAttributes(array('type'=>CoursePriceOptions::TYPE_COURSE,'total_student'=>$key));
        if(isset($price)) {
            return $price->hoc_thu_banking;
        } else {
            throw new CHttpException(404,'The requested page does not exist.');
        }
    }


    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_preregister_course';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('student_id, subject_id, title, start_date, total_of_session, session_per_week', 'required'),
			array('student_id, subject_id, preset_course_id, total_of_student, teacher_id, created_user_id, total_of_session, status, course_type, payment_type, payment_status, final_price, mobicard_final_price, deleted_flag', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>256),
			array('note, course_id, preset_course_id, teacher_id, payment_type, payment_status, final_price, payment_note, deleted_flag, payment_id, payment_method, order_code, payment_date, transaction_id, mobicard_final_price, modified_date,modified_user_id', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, student_id, subject_id, preset_course_id, title, note, course_id, created_user_id, total_of_student, start_date, total_of_session, session_per_week, status, course_type, payment_type, payment_status, created_date, deleted_flag, mobicard_final_price,modified_date,modified_user_id', 'safe', 'on'=>'search'),
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
		);
		//Update model rules: modified date, created user, modified user
		if(isset(Yii::app()->params['isUserAction'])){
			$modelRules[] = array('modified_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'update');
			$modelRules[] = array('created_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert');
			$modelRules[] = array('modified_user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'update');
		}
		return $modelRules;//Return model rules
	}


	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
            'package'=>array(self::HAS_ONE, 'CoursePackage',array('id'=>'total_of_session')),
		);
	}

    public function getTotalOfSession()
    {
        return isset($this->package->sessions)?$this->package->sessions:1;
    }

	public function beforeSave()
	{
		//Remove html tags of some fields before save Course
		$this->title = strip_tags($this->title);
		$this->note = strip_tags($this->note);
		return true;
	}

	//After save Preregister course
	public function afterSave()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, $this->isNewRecord);
	}

	//After delete Preregister course
	public function afterDelete()
	{
		$userActionLog = new UserActionHistory();
		return $userActionLog->saveActionLog($this->tableName(), $this->id, true, true);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'student_id' => 'Học sinh',
			'subject_id' => 'Môn học',
			'title' => 'Chủ đề',
			'note' => 'Yêu cầu học tập',
			'total_of_student' => 'Kiểu lớp',
			'start_date' => 'Ngày bắt đầu',
			'total_of_session' => 'Tổng số buổi/khóa',
			'session_per_week' => 'Số buổi/tuần',
			'course_id' => 'Khóa học?',
			'preset_course_id' => 'Đơn/khóa tạo trước',
			'status' => 'Trạng thái',
			'payment_status' => 'Trạng thái thanh toán',
			'payment_type' => 'Kiểu thu phí',
			'final_price' => 'Học phí toàn khóa (VND)',
			'mobicard_final_price' => 'Học phí cào thẻ (nếu áp dụng)',
			'payment_note'=> 'Ghi chú học phí',
			'payment_id' => 'Mã thanh toán (Payment)',
			'transaction_id' => 'Mã giao dịch (TransactionID)',
			'payment_method' => 'Phương thức thanh toán',
			'order_code' => 'Mã đơn xin học',
			'created_user_id' => 'Người tạo',
			'modified_date' => 'Ngày sửa',
			'modified_user_id' => 'Người sửa',
			'payment_date' => 'Ngày nộp học phí',
			'created_date' => 'Ngày đăng ký',
			'deleted_flag' => 'Trạng thái xóa',
		);
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
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('subject_id',$this->subject_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('note',$this->note,true);
		$criteria->compare('total_of_student',$this->total_of_student);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('total_of_session',$this->total_of_session);
		$criteria->compare('payment_status',$this->payment_status);
		$criteria->compare('course_id',$this->course_id);
		$criteria->compare('preset_course_id',$this->preset_course_id);
		$criteria->compare('final_price',$this->final_price, true);
		$criteria->compare('session_per_week',$this->session_per_week,true);
		$criteria->compare('created_user_id',$this->created_user_id);
		$criteria->compare('status',$this->status);
		$criteria->compare('deleted_flag',$this->deleted_flag);
		$criteria->compare('created_date',$this->created_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array('pageVar'=>'page'),
		    'sort'=>array('sortVar'=>'sort'),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PreregisterCourse the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	//Status options
	public function statusOptions(){
        return array(
        	self::STATUS_PENDING => 'Đang chờ',
        	self::STATUS_APPROVED => 'Đã xác nhận',
        	self::STATUS_REFUSED => 'Đã từ chối');
    }

    //Payment types
	public function paymentTypes()
	{
		return array(
			self::PAYMENT_TYPE_FREE => 'Miễn phí',
		 	self::PAYMENT_TYPE_NOT_FREE => 'Có tính phí'
		 );
	}
	//Payment status
	public static function paymentStatuses()
	{
		return array(
			self::PAYMENT_STATUS_PENDING => 'Chưa thanh toán',
			self::PAYMENT_STATUS_PAID => 'Đã thanh toán',
			self::PAYMENT_STATUS_REFUND => 'Hoàn lại tiền',
		);
	}

	/**
	 * Get status of PreregisterCourse
	 */
	public function getStatus()
	{
		$statusOptions = $this->statusOptions();
		if(isset($statusOptions[$this->status])){
			return $statusOptions[$this->status];
		}else{
			return 'Chưa xác định';
		}
	}

	//Get payment status
	public function getPaymentStatus(){
		$paymentStatusOpts = ClsCourse::paymentStatuses();
		if(isset($paymentStatusOpts[$this->payment_status])){
			return $paymentStatusOpts[$this->payment_status];
		}
		return null;
    }

    /**
     * Display payment status & history payment
     */
    public function displayHistoryPaymentLink()
    {
    	$countPayment = PreregisterPayment::model()->countByAttributes(array('precourse_id'=>$this->id));
    	if($countPayment>0){
    		return CHtml::link($this->getPaymentStatus(), Yii::app()->createUrl("admin/preregisterPayment?precourse_id=$this->id"));
    	}else{
    		return CHtml::link($this->getPaymentStatus(), Yii::app()->createUrl("admin/preregisterPayment?precourse_id=$this->id"), array('style'=>'color:red;'));
    	}
    }

	/**
	 * Get created student of preregister course
	 */
	public function getStudent($studentViewLink=null){
		$student = User::model()->findByPk($this->student_id);
		if(isset($student->id)){
			if($studentViewLink!=null){
				$link = '<a href="'.$studentViewLink.'/'.$student->id.'" title="Điện thoại: '.$student->phone.'">'.$student->fullName().'</a>';
				return $link;
			}
			return $student->fullName();
		}
        return NULL;
	}
	/**
	 * Get course of preregister
	 */
	public function displayActualCourse($label=NULL)
	{
		$course = Course::model()->findByPk($this->course_id);
		if(isset($course->id)){
			$assignedStudents = $course->assignedStudents();
			if(!in_array($this->student_id, $assignedStudents)){
				return NULL;
			}
			$title = ($label)? $label: $course->title;
			$courseLink = '<a href="/admin/session?course_id='.$course->id.'">'.$title.'</a>';
			return $courseLink;
		}
		return NULL;
	}

	/**
	 * Get & display total of student as class type(1-1, 2-3,...)
	 */
	public function displayTotalOfStudentStr($totalOfStudent=null, $shortcut=false)
	{
		$nStudent = ($totalOfStudent)? $totalOfStudent: $this->total_of_student;
		if($shortcut) return "1-$nStudent";//Only display shortcut of class type(1-1, 1-2)
		return CoursePackageOptions::model()->getClassNumbers($nStudent);
	}

	/**
	 * Get email of registered student
	 */
	public function getEmail()
	{
		$student = User::model()->findByPk($this->student_id);
		if(isset($student->email)) return $student->email;
		return NULL;
	}

	/**
	 * Get class from subject id
	 */
	public function getClassId()
	{
		$subject = Subject::model()->findByPk($this->subject_id);
		return $subject->class_id;
	}

	/**
	 * Get created user of preset course
	 */
	public function getCreatedUser()
	{
		if($this->created_user_id){
			$createdUser = User::model()->findByPk($this->created_user_id);
			if(isset($createdUser->id)){
				return $createdUser->fullName();
			}
		}
		return NULL;
	}

	/**
	 * Get suggest schedule from Preregister course
	 */
	public function getSuggestSchedules()
	{
		$sessionPerWeek = json_decode($this->session_per_week);
		$sessionSchedules = array();
		if(($sessionPerWeek) && is_array($sessionPerWeek)){
			foreach($sessionPerWeek as $key=>$val){
				$sessionSchedules['dayOfWeek'][] = $key;
				$timeArr = explode('-', $val);
				$planStart = explode(':', trim($timeArr[0]));
				$sessionSchedules['startHour'][] = $planStart[0];
				$sessionSchedules['startMin'][] = $planStart[1];
			}
		}
		return $sessionSchedules;

	}

 	/**
     * Get waiting course to merge students
     */
    public function displayWaitingMergedCourses()
    {
    	$criteria = new CDbCriteria;
    	$criteria->compare('type',$this->course_type);
    	$criteria->compare('total_of_student',$this->total_of_student);
    	$criteria->compare('subject_id',$this->subject_id);
    	$criteria->compare('deleted_flag', 0);
    	$criteria->compare('(SELECT count(student_id) FROM tbl_course_student WHERE course_id=id)',"<".$this->total_of_student);
    	$condition = "status=".self::STATUS_PENDING." OR status=".self::STATUS_APPROVED;
    	$criteria->addCondition($condition);
    	$courses = Course::model()->findAll($criteria);
    	$mergeCourses = array();//Course to merge
    	if(count($courses)>0){
	    	foreach($courses as $course){
	    		$mergeCourses[$course->id] = $course->title;
	    	}
    	}
    	return $mergeCourses;
    }

    /**
     * Check condition to update price && display payment method
     */
    public function checkDisplayNganluongPayment()
    {
    	$remainAmount = $this->getMobicardRemainPaymentAmount();
    	if($this->payment_type==PreregisterCourse::PAYMENT_TYPE_NOT_FREE && $this->status!=self::STATUS_REFUSED
    		&& $this->payment_status==PreregisterCourse::PAYMENT_STATUS_PENDING && $remainAmount>0)
	   	{
	   		return true;
	   	}
	   	return false;
    }

    /**
     * Generate & re-calculate preregister course
     */
    public function updateByCurrentPriceTable()
    {
    	//Only re-update final price if status is pending
    	if($this->status==self::STATUS_PENDING){
	    	$priceCalculator = new PriceCalculator($this->total_of_student);
	    	$user = User::model()->findByPk($this->student_id);
	    	$preCourseValues = array(
	    		'total_of_student' => $this->total_of_student,
	    		'total_of_session' => $this->total_of_session,
	    		'created_date' => date('Y-m-d H:i:s'),
	    	);
	    	$registration = new ClsRegistration();
	    	$priceValues = $priceCalculator->calculate($preCourseValues, $user);//Price values
	    	$this->final_price = $priceValues['total_price'];//Update final price
	    	if(trim($this->payment_note)=="" || $this->payment_note==NULL){
	    		$this->payment_note = $registration->renderPaymentNote(array_merge($priceValues, $preCourseValues));
	    	}
	    	$this->save();//Update by current price config
    	}
    }

    /**
     * Discount price for payment soon for preset course
     */
    public function updatePresetPriceForPaymentSoon()
    {
    	//Only re-update final price if status is pending
    	if($this->status==self::STATUS_PENDING){
    		if(isset($this->preset_course_id) && $this->preset_course_id>0){
    			$presetCourse = PresetCourse::model()->findByPk($this->preset_course_id);
    			$clsCourse = new ClsCourse();//Cls Course
    			if(isset($presetCourse->id) && trim($presetCourse->price_rules)!=""){
    				$stepPriceRules = $clsCourse->calculateStepFinalPrice(json_decode($presetCourse->price_rules, true));
					if($stepPriceRules!==false){
						$this->final_price = $stepPriceRules['bank_price'];
						$this->mobicard_final_price = $stepPriceRules['mobicard_price'];
						$this->payment_note = $stepPriceRules['description'].': <b>'.number_format($stepPriceRules['bank_price']).'</b>';
						$this->save();//Update by current price config
					}
    			}
			}
    	}
    }

    /**
     * Update response from Nganluong with nganluong class(using beta)
     */
    public function updatePaymentFromNganluong($values)
    {
    	$result = array();//Response value
    	$responseFields = array("transaction_info", "order_code", "price", "payment_id", "payment_type", "error_text", "secure_code");
    	foreach($responseFields as $field){
    		if(isset($values[$field])){
    			$result[$field] = $values[$field];
    		}else{
    			$result[$field] = "";
    		}
    	}
		$nganluong = new ClsNganluong();
		$checkPaymentSuccess = $nganluong->nlCheckout->verifyPaymentUrl($result['transaction_info'], $result['order_code'], $result['price'], $result['payment_id'], $result['payment_type'], $result['error_text'], $result['secure_code']);
		if($checkPaymentSuccess){
			$this->order_code = $result['order_code'];//Order code
			$this->payment_id = $result['payment_id'];//Payment Id
			$this->payment_method = 'Nganluong';//Payment method by Nganluong
			$this->payment_status = self::PAYMENT_STATUS_PAID;//Update payment status
			$this->status = self::STATUS_APPROVED;//Approved status
			$this->payment_date = date('Y-m-d H:i:s');//Payment date
			if($this->save()) return $this->payment_status;
		}
		return NULL;
    }


	/**
	 * Save preregister payment from Nganluong online payment
	 */
	public function saveNganluongOnlinePayment($nlResult)
	{
		$payment = new PreregisterPayment();
		$studentId = Yii::app()->user->getId();
		$payment->attributes = array(
			'precourse_id' => $this->id,
			'transaction_id' => $nlResult->transaction_id,
			'paid_amount' => $this->final_price,
			'payment_method' => 'ATM ONLINE',
			'payment_date' => date('Y-m-d H:i:s'),
			'note' => "Thanh toán bằng internet banking qua Ngân lượng",
			'created_user_id' => $studentId,
		);
		$payment->save();//Save mobicard payment
	}

	/**
	 * Save preregister payment from Mobicard
	 */
	public function saveMobicardPayment($nlResult)
	{
		$payment = new PreregisterPayment();
		$mobicardTypes = ClsNganluong::mobiCardOptions();
		$paymentNote = 'Loại thẻ: '.$mobicardTypes[$nlResult->type_card].', Số Seri: '.$nlResult->card_serial;
		$paymentNote .= ', Mã số thẻ: '.$nlResult->pin_card.', Mệnh giá: '.number_format($nlResult->card_amount);
		$studentId = Yii::app()->user->getId();
		$payment->attributes = array(
			'precourse_id' => $this->id,
			'transaction_id' => $nlResult->transaction_id,
			'paid_amount' => $nlResult->card_amount,
			'payment_method' => 'MOBI CARD',
			'payment_date' => date('Y-m-d H:i:s'),
			'note' => $paymentNote,
			'created_user_id' => $studentId,
		);
		$payment->save();//Save mobicard payment
	}

    /**
     * Get all display preregister course for student
     */
    public function getPreCoursesForStudent($userId)
    {
    	$criteria = new CDbCriteria;
    	$criteria->compare('student_id',$userId);
    	$criteria->compare('deleted_flag', 0);
        $criteria->order = 'id desc';
    	//$condition = "(status=".self::STATUS_PENDING." OR status=".self::STATUS_REFUSED.") OR (status=".self::STATUS_REFUSED." AND course_id is NULL)";
    	//$criteria->addCondition($condition);
        return PreregisterCourse::model()->findAll($criteria);
    }

    /**
     * Get preset course of this preregister course if it is preset
     */
    public function getPresetCourse()
    {
    	if($this->preset_course_id>0 && $this->course_type==Course::TYPE_COURSE_PRESET){
    		$presetCourse = PresetCourse::model()->findByPk($this->preset_course_id);
    		if(isset($presetCourse->id)){
    			return $presetCourse;
    		}
    	}
		return false;
    }

	/**
	 * Get total amount of Preregister course
	 */
	public function getTotalFinalPrice()
	{
		if(isset($this->mobicard_final_price) && $this->mobicard_final_price>$this->final_price){
			return $this->mobicard_final_price;
		}else{
			return $this->final_price;
		}
	}

	/**
	 * Get History payment of Preregister course
	 */
	public function getPaymentHistory()
	{
		$criteria = new CDbCriteria;
		$criteria->compare('precourse_id', $this->id);
		$criteria->order = 'created_date ASC';
		$paymentHistory = PreregisterPayment::model()->findAll($criteria);
		return $paymentHistory;
	}

	/**
	 * Get paid amount of Preregister course
	 */
	public function getTotalPaidAmount()
	{
		$payments = $this->getPaymentHistory();
		$totalPaidAmount = 0;//Total paid amount
		if(count($payments)>0){
			foreach($payments as $payment){
				$totalPaidAmount += $payment->paid_amount;
			}
		}
		if($this->payment_status==self::PAYMENT_STATUS_PAID && $totalPaidAmount==0){
			return $this->final_price;
		}
		return $totalPaidAmount;
	}

	/**
	 * Get remain payment amount of Preregister course
	 */
	public function getRemainPaymentAmount()
	{
		$totalPaidAmount = $this->getTotalPaidAmount();
		$remainAmount = $this->final_price - $totalPaidAmount;
		if($remainAmount<0) $remainAmount = 0;//Remain amount
		return $remainAmount;
	}

	/**
	 * Get mobicard remain payment amount of Preregister course
	 */
	public function getMobicardRemainPaymentAmount()
	{
		if($this->status==self::STATUS_APPROVED && $this->payment_status==self::PAYMENT_STATUS_PAID){
			return 0;//if approved & paid
		}else{
			$totalPaidAmount = $this->getTotalPaidAmount();//Total paid amount
			$toalFinalPrice = $this->getTotalFinalPrice();//Total final price
			$mobicardRemainAmount = $toalFinalPrice - $totalPaidAmount;
			if($mobicardRemainAmount<0) $mobicardRemainAmount = 0;//Remain amount
			return $mobicardRemainAmount;
		}
	}

	/**
	 * Delete all pre course by UserId
	 */
	public function deletePreCourseByUser($userId)
	{
		$criteria = new CDbCriteria();
		$criteria->condition = "student_id = $userId OR teacher_id=$userId";
		PreregisterCourse::model()->deleteAll($criteria);
	}

	/**
	 * Check & only display mobicard payment method
	 */
	public function checkPaidMobicardPayment()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('precourse_id', $this->id);
		$criteria->compare('created_user_id', $this->student_id);
		$criteria->addCondition("LOWER(payment_method) LIKE '%mobi card%'");
		$count = PreregisterPayment::model()->count($criteria);//Count total paid mobicard payment
		if($count>0) return true;
		return false;
	}
}
