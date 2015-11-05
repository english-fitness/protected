<?php

class UtmSaleStat extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_utm_sale_stat';
	}
    
    public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		$modelRules = array(
			array('register_id, utm_source, utm_medium, utm_campaign', 'required'),
			array('utm_term, utm_content', 'safe'),
		);
		//Update model rules: modified date, created user, modified user
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
			'preregisterUser' => array(self::BELONGS_TO, 'preregisterUser', 'register_id'),
		);
	}
    
    public function afterSave(){
        $query = "";
        $utmParams = $this->attributes;
        unset($utmParams['register_id']);
        foreach($utmParams as $key=>$value){
            if ($value != null && $value != ""){
                $query .= "INSERT IGNORE INTO tbl_utm_sale_params (name, value) VALUES ('".$key."', '".$value."');";
            }
        }
        Yii::app()->db->createCommand($query)->execute();
    }


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Session the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
    
    public static function getRegisterIds($params){
        $queries = array();
        foreach ($params as $key=>$value){
            $queries[] = $key."='".$value."'";
        }
        $query = "SELECT register_id FROM tbl_utm_sale_stat WHERE " . implode(" AND ", $queries);
        
        return Yii::app()->db->createCommand($query)->queryColumn();
    }
    
    public static function getFilterValues($name){
        $query = "SELECT value FROM tbl_utm_sale_params WHERE name = '" . $name . "'";
        return Yii::app()->db->createCommand($query)->queryColumn();
    }
}
