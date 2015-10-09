<?php
class ClsUser {
    public static function getNextUserIndex($prefix){
        $query = "SELECT username FROM tbl_user
                 WHERE username LIKE '" . $prefix . "%'
                 ORDER BY username DESC
                 LIMIT 1";
        $lastUsername = Yii::app()->db->createCommand($query)->queryScalar();
        $lastUsernameIndex = preg_replace("/[^0-9]/", "", $lastUsername);
        return $lastUsernameIndex + 1;
    }

    public static function getViewLink($id){
    	$user = User::model()->findByPk($id);
    	switch ($user->role){
			case User::ROLE_STUDENT:
				return '<a href="' . Yii::app()->baseUrl . '/admin/student/view/id/' . $user->id . '">' . $user->fullname() . '</a>';
				break;
			case User::ROLE_TEACHER:
				return '<a href="' . Yii::app()->baseUrl . '/admin/teacher/view/id/' . $user->id . '">' . $user->fullname() . '</a>';
				break;
			default:
				break;
		}
    }
}
?>