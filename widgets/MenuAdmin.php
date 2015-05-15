<?php
class MenuAdmin extends CWidget
{

    public  function run()
    {
        $countMessageNotReadFlag        = MessageStatus::model()->countMessageNotReadFlag(1);
        $notification                   =  new ClsNotification();
        $countNotActivatedTeacher       = $notification->countNotActivatedUser(User::ROLE_TEACHER);
        $countNotActivatedStudent       = $notification->countNotActivatedUser(User::ROLE_STUDENT);
        $countPendingPreregisterUser    = $notification->countPendingPreregisterUser();
        $countPendingCourseRequest      = $notification->countPendingCourseRequest();
        $countPendingPresetCourse       = $notification->countPendingPresetCourse();
        $countPendingCourse             = $notification->countPendingCourse();
        $countPendingNearestSession     = $notification->countPendingNearestSession();
        $countPendingReminderSession    = $notification->countPendingNearestSession(true);

        $items = array(
            array(
                'label'=>'Hệ thống',
                'url'=>'#',
                'items'=>array(
                    array('label'=>'Quản lý thẻ','url'=>array('/cart/cart/index'),'visible'=>Yii::app()->user->isAdmin()),
                    array('label'=>'Quản lý gói khóa học','url'=>array('/admin/package/index')),
                    array('label'=>'Cài đặt đầu trang','url'=>array('/admin/headerScript/index')),
                    array('label'=>'Cài đặt chia sẻ','url'=>array('/admin/shareFacebook/index')),
                    array('label'=>'Quyền truy cập','url'=>array('/admin/default/permission'),'visible'=>!Yii::app()->user->isAdmin()),
                    array('label'=>'Lịch sử hoạt động','url'=>array('/admin/UserActionHistory/index')),
                    array('label'=>'Danh mục lớp-môn','url'=>array('/admin/classes/index')),
                    array('label'=>'Gợi ý chủ đề khóa học','url'=>array('/admin/subjectSuggestion/index')),
                    array('label'=>'Người dùng','url'=>array('/admin/user/index','visible'=>Yii::app()->user->isAdmin())),
                    array('label'=>'Mạng xã hội đã kết nối','url'=>array('/admin/socialNetwork/facebook','visible'=>Yii::app()->user->isAdmin())),
                    array('label'=>'Đổi mật khẩu','url'=>array('/admin/account/changePassword')),
                    array('label'=>'Thoát','url'=>array('/site/logout')),
                )
            ),
			/*
            array(
                'label'=>'Trắc nghiệm',
                'url'=>'#',
                'items'=>array(
                    array('label'=>'Chủ đề môn học','url'=>array('/admin/quizTopic/index')),
                    array('label'=>'Đề thi trắc nghiệm','url'=>array('/admin/quizExam/index')),
                    array('label'=>'Câu hỏi trắc nghiệm','url'=>array('/admin/quizItem/index')),
                    array('label'=>'Thống kê trắc nghiệm','url'=>array('/admin/quizHistory/index'))
                )
            ),
			*/
            array(
                'label'=>'Tin nhắn '.$this->renderItemCount($countMessageNotReadFlag),
                'title'=>$this->renderTitleCount("Tin nhắn chưa đọc: ",$countMessageNotReadFlag),
                'url'=>'#',
                'items'=>array(
                    array('label'=>'Tin nhắn đến','url'=>array('/admin/message/inbox')),
                    array('label'=>'Tin nhắn đi','url'=>array('/admin/message/outbox')),
                    array('label'=>'Thông báo','url'=>array('/admin/notification/index')),
                )
            ),
            array(
                'label'=>'Thành viên '.$this->renderItemCount($countNotActivatedStudent),
                'url'=>'#',
                'title'=>$this->renderTitleCount("Thành viên chưa được kích hoạt: ",$countNotActivatedStudent),
                'items'=>array(
                    array('label'=>'Học sinh '.$this->renderItemCount($countNotActivatedStudent),'url'=>array('/admin/student/index')),
                    array('label'=>'Giáo viên '.$this->renderItemCount($countNotActivatedTeacher),'url'=>array('/admin/teacher/index')),
                    array('label'=>'Đăng ký tư vấn '.$this->renderItemCount($countPendingPreregisterUser),'url'=>array('/admin/preregisterUser/index')),
                    array('label'=>'Lịch sử chăm sóc','url'=>array('/admin/userSalesHistory/index')),
                )
            ),
            array(
                'label'=>'Đơn xin học '.$this->renderItemCount($countPendingCourseRequest),
                'url'=>'#',
                'title'=>$this->renderTitleCount("Tin nhắn chưa đọc: ",$countPendingCourseRequest),
                'items'=>array(
                    array('label'=>'Đăng ký lớp nhỏ','url'=>array('/admin/preregisterCourse/index')),
                    array('label'=>'Đăng ký lớp đông','url'=>array('/admin/preregisterCourse/index?type=preset')),
                    array('label'=>'Lịch sử nộp học phí','url'=>array('/admin/preregisterPayment/index')),
                    array('label'=>'Đơn đã hủy/xóa','url'=>array('/admin/preregisterCourse?deleted_flag=1')),
                )
            ),
            array(
                'label'=>'Đơn/Khóa '.$this->renderItemCount($countPendingPresetCourse),
                'url'=>'#',
                'title'=>$this->renderTitleCount("Đơn xin học đang chờ xử lý: ",$countPendingPresetCourse),
                'items'=>array(
                    array('label'=>'Đang chờ','url'=>array('/admin/presetCourse/index?PresetCourse[status]='.PresetCourse::STATUS_PENDING)),
                    array('label'=>'Đang tuyển sinh','url'=>array('/admin/presetCourse/index?PresetCourse[status]='.PresetCourse::STATUS_REGISTERING)),
                    array('label'=>'Đã khai giảng','url'=>array('/admin/presetCourse/index?PresetCourse[status]='.PresetCourse::STATUS_ACTIVATED)),
                    array('label'=>'Đã hủy/xóa','url'=>array('/admin/presetCourse?deleted_flag=1')),
                )
            ),
            array(
                'label'=>'Khóa học '.$this->renderItemCount($countPendingCourse),
                'url'=>'#',
                'title'=>$this->renderTitleCount("Khóa học đang chờ xác nhận: ",$countPendingCourse),
                'items'=>array(
                    array('label'=>'Khóa học thường','url'=>array('/admin/course/index?Course[status]='.Course::STATUS_APPROVED.'&type='.Course::TYPE_COURSE_NORMAL)),
                    array('label'=>'Khóa học tạo trước','url'=>array('/admin/course/index?Course[status]='.Course::STATUS_APPROVED.'&type='.Course::TYPE_COURSE_PRESET)),
                    array('label'=>'Khóa học thử','url'=>array('/admin/course/index?Course[status]='.Course::STATUS_APPROVED.'&type='.Course::TYPE_COURSE_TRAINING)),
                    array('label'=>'Khóa học test','url'=>array('/admin/course/index?Course[status]='.Course::STATUS_APPROVED.'&type='.Course::TYPE_COURSE_TESTING)),
                    array('label'=>'Đã hủy/xóa','url'=>array('/admin/course?deleted_flag=1')),
                )
            ),
            array(
                'label'=>'Buổi học '.$this->renderItemCount($countPendingNearestSession),
                'url'=>'#',
                'title'=>$this->renderTitleCount("Buổi học gần nhất đang chờ được xác nhận: ",$countPendingNearestSession),
                'items'=>array(
                    array('label'=>'Gần nhất','url'=>array('/admin/session/nearest')),
                    array('label'=>'Đang diễn ra','url'=>array('/admin/session/active')),
                    array('label'=>'Đã hoàn thành','url'=>array('/admin/session/ended')),
                    array('label'=>'Đã hủy/xóa','url'=>array('/admin/session/canceled?Session[status]='.Session::STATUS_CANCELED)),
					array('label'=>'Được ghi âm','url'=>array('/admin/session/recorded')),
                )
            ),
            array('title'=>$this->renderTitleCount("Buổi học đang chờ xác nhận: ",$countPendingReminderSession),'label'=>'Nhắc lịch học'.$this->renderItemCount($countPendingReminderSession), 'url'=>array('/admin/session/reminder') )
        );
        $this->widget('application.widgets.bootstrap.Menu', array('items'=>$items));
    }

    /**
     * @var interger $count
     * @return string
     */
    public function renderItemCount($count)
    {
        if($count && $count > 0)
            return CHtml::tag('span',array('class'=>'text-count'),$count);
    }

    /**
     * @var interger $count
     * @return string
     */
    public function renderTitleCount($title,$count)
    {
        if($count && $count > 0)
            return $title.$count;
    }

}
?>