<?php

/**
 * This is the model class for table "tbl_permission".
 *
 * The followings are the available columns in table 'tbl_permission':
 * @property integer $id
 * @property string $title
 * @property string $controller
 * @property string $action
 * @property string $description
 */
class Permission extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_permission';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, controller, action', 'required'),
			array('title', 'length', 'max'=>256),
			array('controller, action', 'length', 'max'=>80),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, controller, action, description', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'controller' => 'Controller',
			'action' => 'Action',
			'description' => 'Description',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('description',$this->description,true);

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
	 * @return Permission the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Get permission Ids of user
	 */
	public function getUserPermissions($userId)
	{
		$query = "SELECT permission_id FROM tbl_user_permission WHERE user_id=".$userId;
		$permissionIds = Yii::app()->db->createCommand($query)->queryColumn();
		return $permissionIds;
	}
	
	/**
	 * Update assigning permission for user 
	 */
	public function assignPermissionsToUser($userId, $permissionIds)
	{
		//Remove old permission of user
		UserPermission::model()->deleteAllByAttributes(array('user_id'=>$userId));
		$userPermisions = array();//User permissions
		if(count($permissionIds)>0){
			foreach($permissionIds as $permissionId){
				$userPermisions[] = array('user_id'=>$userId, 'permission_id'=>$permissionId);
			}
		}
		//Save permission for user
		if(count($userPermisions)>0){
			$builder = Yii::app()->db->schema->commandBuilder;
			$command = $builder->createMultipleInsertCommand('tbl_user_permission', $userPermisions)->execute();
		}
	}
	
	/**
	 * Check permission by controller and action of user
	 */
	public function checkAllowUserAccess($userId, $controller, $action)
	{
		$permission = Permission::model()->findByAttributes(array('controller'=>$controller, 'action'=>$action));
		if(isset($permission->id)){
			$userPermission = UserPermission::model()->findByAttributes(array('user_id'=>$userId, 'permission_id'=>$permission->id));
			if(isset($userPermission->user_id)){
				return true;
			}
			return false;
		}
		return true;
	}
	
}
