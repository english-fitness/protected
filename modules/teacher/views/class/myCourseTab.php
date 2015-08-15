<style>
.notification{
    background-color: #f24;
    border-radius: 1000px;
    padding-left: 4px;
    padding-right: 4px;
    color: #fff;
    font-weight: bold;
}
</style>
<?php
$unfilledRemindersCount = SessionComment::countUnfilledReminders(Yii::app()->user->id);
if($unfilledRemindersCount > 0){
    $unfilledRemindersCountText = '&nbsp;<span class="notification">' . $unfilledRemindersCount . '<span>';
} else {
    $unfilledRemindersCountText = "";
}
$baseurl = Yii::app()->baseurl."/teacher";
$menuCourseTeacher  =  array(
	array("label"=>Yii::t('nav','On-going sessions'),"url"=>$baseurl."/class/nearestSession"),
	/* REMOVE
	array("label"=>Yii::t('nav','Joined class '),"url"=>$baseurl."/class/index"),
    array("label"=>Yii::t('nav','Registered class'),"url"=>$baseurl."/presetRequest/index"),
	*/
	array("label"=>Yii::t('nav','Completed sessions'),"url"=>$baseurl."/class/endedSession"),
    array("label"=>Yii::t('nav','Unfilled Reminders' . $unfilledRemindersCountText),"url"=>$baseurl."/sessionComment/unfilled"),
	/* REMOVE
	array("label"=>Yii::t('nav','Rearest hour'),"url"=>$baseurl."/class/attendingSession"),
	*/
	array("label"=>Yii::t('nav','Register schedule'),"url"=>$baseurl."/schedule/registerSchedule"),
	array("label"=>Yii::t('nav','Calendar view'),"url"=>$baseurl."/schedule/calendar"),
);
echo Html::createNavMenu($menuCourseTeacher,array('class'=>'nav nav-tabs'));
?>
