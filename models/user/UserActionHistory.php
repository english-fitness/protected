<?php

/**
 * This is the model class for table "tbl_user_action_history".
 *
 * The followings are the available columns in table 'tbl_user_action_history':
 * @property integer $id
 * @property integer $user_id
 * @property string $table
 * @property string $controller
 * @property string $action
 * @property string $primary_key
 * @property string $description
 * @property string $created_date
 */
class UserActionHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_user_action_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('controller, action, primary_key', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('table_name, controller, action, primary_key', 'length', 'max'=>80),
			array('description,created_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id, table_name, controller, action, primary_key, description, created_date', 'safe', 'on'=>'search'),
			// Set the created and modified dates automatically on insert, update.
			array('created_date', 'default', 'value'=>date('Y-m-d H:i:s'), 'setOnEmpty'=>false, 'on'=>'insert'),
			array('user_id', 'default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false, 'on'=>'insert'),
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
			'id' => 'Thứ tự',
			'user_id' => 'Người thực hiện',
			'table_name' => 'Bảng dữ liệu',
			'controller' => 'Tên điều khiển',
			'action' => 'Tên hành động',
			'primary_key' => 'Mã bản ghi',
			'description' => 'Ghi chú hành động',
			'created_date' => 'Ngày thực hiện',
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
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('primary_key',$this->primary_key,true);
		$criteria->compare('description',$this->description,true);
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
	 * @return UserActionHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * Auto creating user action history
	 */
	public function saveActionLog($tableName, $primaryKey=null, $isNewRecord=false, $isDeleted=false)
	{
		$adminRules = array(User::ROLE_ADMIN, User::ROLE_MONITOR, User::ROLE_SUPPORT);
		if(isset(Yii::app()->params['isUserAction']) && isset(Yii::app()->user->id) 
			&& isset(Yii::app()->user->role) && in_array(Yii::app()->user->role, $adminRules))
		{
			$controllerName = trim(Yii::app()->controller->id);//Controller name
			$actionName = trim(Yii::app()->controller->action->id);//Action name
			$attributes = array(
				'controller'=>$controllerName,
				'action'=>$actionName
			);
			$permission = Permission::model()->findByAttributes($attributes);
			if(isset($permission->id)){
				$attributes['description'] = $permission->title;
			}
			$attributes['table_name'] = $tableName;
			$attributes['primary_key'] = $primaryKey;
			$attributes['action'] = ($isNewRecord)? $actionName.' (create)': $actionName.' (update)';
			if($isDeleted) $attributes['action'] = $actionName.' (delete)';
			$this->attributes = $attributes;
			$this->save();
		}
		return true;
	}
	
	/**
	 * Display style by actual action
	 */
	public function displayAction()
	{
		$displayAction = $this->action;
		if(strpos($this->action, '(delete)')!==false){
			$displayAction = '<span><i class="btn-remove"></i>&nbsp;'.$this->action.'</span>';
		}elseif(strpos($this->action, 'delete')!==false){
			$displayAction = '<span><i class="trash"></i>&nbsp;'.$this->action.'</span>';
		}elseif(strpos($this->action, 'update')!==false){
			$displayAction = '<span><i class="btn-edit"></i>&nbsp;'.$this->action.'</span>';
		}elseif(strpos($this->action, '(create)')!==false){
			$displayAction = '<span><i class="icon-plus"></i>&nbsp;'.$this->action.'</span>';
		}
		return $displayAction;
	}
	
	/**
	 * Count current user's action in today
	 */
	public function countCurrentActions($currentDate)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('created_date', $currentDate, true);
		return $this->count($criteria);
	}
}
