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
}
?>