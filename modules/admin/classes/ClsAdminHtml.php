<?php
class ClsAdminHtml
{
	/**
	 * Display Course status
	 */
	public static function displayCourseStatus($course_id, $status)
	{
		$statusOptions = Course::statusOptions();
		if($status!=Course::STATUS_PENDING){
			$labelStatus = isset($statusOptions[$status])? $statusOptions[$status]: 'Chưa xác định';
			echo '<span id="courseStatus'.$course_id.'">'.$labelStatus.'</span>';
		}else{
			echo '<span id="courseStatus'.$course_id.'">'.$statusOptions[$status].' => <a href="javascript: approve('.$course_id.');">Xác nhận</a></span>';
		}
	}

	/**
	 * Display Session status
	 */
	public static function displaySessionStatus($session_id, $status)
	{
		$statusOptions = Session::statusOptions();
		if($status!=Session::STATUS_PENDING){
			$labelStatus = isset($statusOptions[$status])? $statusOptions[$status]: 'Chưa xác định';
			if($status!=Session::STATUS_CANCELED){
				echo '<span id="sessionStatus'.$session_id.'">'.$labelStatus.'</span>';
			}else{
				echo '<span class="error">'.$labelStatus.'</span>';
			}
		}else{
			echo '<span id="sessionStatus'.$session_id.'">'.$statusOptions[$status].' => <a href="javascript: approve('.$session_id.');" onclick="">Xác nhận</a></span>';
		}
	}

    /**
     * Display whiteboard or create Whiteboard
     */
	public static function displayBoard($session, $return=false)
	{
		$displayBoard = "";//Display whiteboard str
		$sessionId = $session->id;//Session Id
		$whiteboard = $session->whiteboard;//Whiteboard
		if(trim($whiteboard)!=""){
			$boardLink = Yii::app()->board->generateUrl($whiteboard);
			//Open to new window & hidden some element of browser
			$displayBoard .= '<div id="whiteboard'.$sessionId.'"><a href="#" onclick=\'window.open("'.$boardLink.'", "Whiteboard", "menubar=no,status=no,toolbar=no,scrollbars=no,directories=no,fullscreen=yes");\'>'.$whiteboard.'</a>';
			//Open to new tab
			//echo '<a target="_blank" href="'.$boardLink.'">'.$whiteboard.'</a>';
			//Delete existed whiteboard
			if($session->type==Session::TYPE_SESSION_TESTING){//Ended session
				$displayBoard .= '<a href="javascript: deleteBoard('.$sessionId.',\''.$whiteboard.'\');" class="fR pR5 clrRed">Xóa lớp ảo </a>';
			}
			$displayBoard .= '</div>';
		}else{
			$displayBoard .= '<div id="whiteboard'.$sessionId.'">'
			.'<a href="javascript: createBoard('.$sessionId.', 0, 1, 0, 2);">Lớp P2P</a> hoặc '
			.'<a href="javascript: createBoard('.$sessionId.', 0, 0, 0, 2);">Lớp thường</a> hoặc '
			// .'<a href="javascript: createBoard('.$sessionId.', 0, 0, 1, 2);">Lớp nhỏ Server lớn Bình thường</a> hoặc '
			// .'<a href="javascript: createBoard('.$sessionId.', 1, 0, 1, 2);">Lớp lớn Bình thường</a>'
                        .'<br>'       
                        /*.'<a href="javascript: createBoard('.$sessionId.', 0, 1, 0, 1);">Lớp nhỏ P2P Đặc biệt</a> hoặc '*/
			.'<a href="javascript: createBoard('.$sessionId.', 0, 0, 0, 1);">Lớp hạn chế</a>'
			// .'<a href="javascript: createBoard('.$sessionId.', 0, 0, 1, 1);">Lớp nhỏ Server lớn Đặc biệt</a> hoặc '
			// .'<a href="javascript: createBoard('.$sessionId.', 1, 0, 1, 1);">Lớp lớn Đặc biệt</a>'       
			.'</div>';
		}
		if($return){
			return $displayBoard;
		}
		echo $displayBoard;
	}


	/**
	 * Display inline edit subject of session
	 */
	public static function displayInlineEdit($sessionId, $subject)
	{
		echo '<span id="spanEditSubject'.$sessionId.'" ondblclick="displayInlineEdit('.$sessionId.');">'.$subject.'</span>';
		echo '<input type="text" id="txtEditSubject'.$sessionId.'" value="'.$subject.'"  style="display:none;" onKeyPress="editSubject('.$sessionId.',event);"/>';
	}

	/**
	 * Display social netwwork of User(Phone, Facebook, Google)
	 */
	public static function displayContactIcons($phone=null, $fbId=null, $gmail=null, $hmUser=null)
	{
		if($phone!=NULL){
			echo '<span class="fL pR10">'.Common::formatPhoneNumber($phone).'</span>';
		}
		if($fbId!=NULL){
			echo '<a  class="fL pR10" target="_blank" href="https://www.facebook.com/profile.php?id='.$fbId.'"><span class="facebook"></span></a>&nbsp;&nbsp;';
		}
		if($gmail!=NULL){
			echo '<span  class="gmail fL mR10" title="'.$gmail.'"></span>';
		}
		if($hmUser!=NULL){
			echo '<span  class="hocmai fL" title="'.$hmUser.'"></span>';
		}
	}

	/**
	 * Display connected user by user id
	 */
	public static function displayConnectedUser($user)
	{
		$userController = 'user'; $label = 'Người dùng';
		if($user->role==User::ROLE_STUDENT || $user->role==User::ROLE_TEACHER){
			$userController =  str_replace('role_', '', $user->role);
			$label = ($userController=='student')?'Học sinh': 'Giáo viên';
		}
		echo '<b>'.$label.':</b>&nbsp;<a href="/admin/'.$userController.'/view/id/'.$user->id.'"><b>'.$user->fullName().'</b> ('.$user->email.')</a>';
	}

	/**
	 *
	 * Display session per week from json str
	 */
	public static function displaySessionPerWeek($jsonStr)
	{
		$sessionPerWeek = json_decode($jsonStr);
		$registration = new ClsRegistration();
		$daysOfWeek = $registration->daysOfWeek();//Session per week
		$displayStr = "";
		if(is_array($sessionPerWeek) || is_object($sessionPerWeek)){
			foreach($sessionPerWeek as $key=>$val){
				$displayStr .= "<b>".$daysOfWeek[$key]."</b>: ".$val.",&nbsp;&nbsp;";
			}
			return $displayStr;
		}
		return $jsonStr;
	}

	/**
	 * Display read flag of inbox message in admin
	 */
	public static function displayInboxMessageStatus($messageId, $count=0)
	{
		if($count==0){
			echo '<span id="messageReadFlag'.$messageId.'"><span class="error">Chưa xử lý</span> => <a href="javascript: markRead('.$messageId.');" onclick="">Đã đọc & xử lý</a></span>';
		}else{
			echo '<span id="messageReadFlag'.$messageId.'">Đã xử lý</span>';
		}
	}

	/**
	 * Display Preregister course status
	 */
	public static function displayPreregisterCourseStatus($preCourseId, $status, $paymentStatus)
	{
		$statusOptions = PreregisterCourse::model()->statusOptions();
		if(!($status==PreregisterCourse::STATUS_PENDING && $paymentStatus==PreregisterCourse::PAYMENT_STATUS_PENDING)){
			$labelStatus = isset($statusOptions[$status])? $statusOptions[$status]: 'Chưa xác định';
			if($status!=PreregisterCourse::STATUS_REFUSED){
				echo '<span id="preCourseStatus'.$preCourseId.'">'.$labelStatus.'</span>';
			}else{
				echo '<span class="error">'.$labelStatus.'</span>';
			}
		}else{
			echo '<span id="preCourseStatus'.$preCourseId.'">'.$statusOptions[$status].' => <a href="javascript: refuse('.$preCourseId.');" onclick="">Từ chối</a></span>';
		}
	}
}
?>