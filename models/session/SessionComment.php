<?php

/**
 * This is the model class for table "tbl_session_comment".
 *
 * The followings are the available columns in table 'tbl_session_comment':
 * @property integer $id
 * @property integer $session_id
 * @property integer $user_id
 * @property string $comment
 * @property string $created_date
 * @property string $modified_date
 *
 * The followings are the available model relations:
 * @property Session $session
 * @property User $user
 */
class SessionComment extends CActiveRecord
{
    //the date when we started implementing the session comment
    //use this so we can ignore older sessions
    const MIN_DATE = "2015-08-17 00:00:00";
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'tbl_session_comment';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('session_id, user_id, created_date', 'required'),
            array('session_id, user_id', 'numerical', 'integerOnly'=>true),
            array('session_id, user_id, comment, created_date, modified_date', 'safe'),
            array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, session_id, user_id, created_date, modified_date', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'session' => array(self::BELONGS_TO, 'Session', 'session_id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'session_id' => 'Session',
            'user_id' => 'User',
            'comment' => 'Comment',
            'created_date' => 'Created Date',
            'modified_date' => 'Modified Date',
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
        $criteria->compare('session_id',$this->session_id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('comment',$this->comment,true);
        $criteria->compare('created_date',$this->created_date,true);
        $criteria->compare('modified_date',$this->modified_date,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return SessionComment the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * Delete all session comments by UserId
     */
    public function deleteCommentsByUser($userId)
    {
        $criteria = new CDbCriteria();
        $criteria->condition = "user_id = $userId";
        SessionComment::model()->deleteAll($criteria);
    }
    
    public static function findBySession($session){
        if (is_numeric($session)){
            $session = Session::model()->findByPk($session);
        }
        
        if (!$session instanceof Session){
            throw new InvalidArgumentException("findBySession only accept numeric or instance of class Session");
        }
			
        $query = "SELECT * FROM tbl_session_comment ".
                 "WHERE session_id = " . $session->id . " " .
                 "AND user_id = " . $session->teacher_id;
        
        $teacherComment = SessionComment::model()->findBySql($query);
        
        $query = "SELECT c.* FROM tbl_session_comment c JOIN tbl_session_student s " .
                 "ON c.session_id = s.session_id " .
                 "AND c.user_id = s.student_id " .
                 "WHERE c.session_id = " . $session->id;
        
        $studentComment = SessionComment::model()->findAllBySql($query);
        
        return array(
            'teacherComments'=>$teacherComment,
            'studentComments'=>$studentComment,
        );
    }
    
    public static function checkUnfilledReminder($teacherId){
        $query = "SELECT count(*) FROM tbl_session " .
                 "WHERE teacher_id = " . $teacherId . " " .
                 "AND plan_start > '" . self::MIN_DATE . "' ".
                 "AND id NOT IN (" .
                    "SELECT s.id FROM tbl_session s JOIN tbl_session_comment c " .
                    "ON s.id = c.session_id " .
                    "WHERE teacher_id = " . $teacherId . " " .
                 ")";
        return Yii::app()->db->createCommand($query)->queryScalar();
    }
    
    public static function countUnfilledReminders($teacherId){
        $query = "SELECT count(*) FROM tbl_session " .
                 "WHERE teacher_id = " . $teacherId . " " .
                 "AND plan_start > '" . self::MIN_DATE . "' ".
                 "AND status = 3 " .
                 "AND id NOT IN (" .
                    "SELECT s.id FROM tbl_session s JOIN tbl_session_comment c " .
                    "ON s.id = c.session_id " .
                    "AND s.teacher_id = c.user_id " .
                    "WHERE s.teacher_id = " . $teacherId . " " .
                 ")";
                 
        return Yii::app()->db->createCommand($query)->queryScalar();
    }
    
    public static function getUnfilledReminders($teacherId){
        $count = self::countUnfilledReminders($teacherId);
        
        $query = "SELECT * FROM tbl_session " .
                 "WHERE teacher_id = " . $teacherId . " " .
                 "AND plan_start > '" . self::MIN_DATE . "' ".
                 "AND status = 3 " .
                 "AND id NOT IN (" .
                    "SELECT s.id FROM tbl_session s JOIN tbl_session_comment c " .
                    "ON s.id = c.session_id " .
                    "AND s.teacher_id = c.user_id " .
                    "WHERE teacher_id = " . $teacherId . " " .
                 ")";
        
        $sessions = Session::model()->findAllBySql($query);
        
        $pages = new CPagination($count);
        $pages->pageSize = 10;
        
        return array(
            'sessions'=>$sessions,
            'pages'=>$pages,
        );
    }
    
    public static function checkReminderExistence($sessionId){
        $query = "SELECT EXISTS (" . 
                    "SELECT s.id FROM tbl_session s JOIN tbl_session_comment c " .
                    "ON s.id = c.session_id " .
                    "WHERE s.teacher_id = c.user_id " .
                    "AND s.id = " . $sessionId .
                 ")";
        return Yii::app()->db->createCommand($query)->queryScalar();
    }
}
